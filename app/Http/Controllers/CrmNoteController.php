<?php

namespace App\Http\Controllers;

use App\Models\CrmNote;
use App\Jobs\SyncNotesFromZohoCRM;
use App\Services\ZohoCRMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CrmNoteController extends Controller
{
    protected $crm;

    public function __construct(ZohoCRMService $crm)
    {
        $this->crm = $crm;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CrmNote::query();

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by parent module
        if ($request->filled('parent_module')) {
            $query->byParentModule($request->parent_module);
        }

        $notes = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('dashboard.crm.note.index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.crm.note.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'note_title' => 'nullable|string|max:255',
            'note_content' => 'required|string',
            'parent_module' => 'required|string',
            'parent_id' => 'required|string',
            'parent_name' => 'nullable|string',
        ]);

        try {
            // Create note in Zoho CRM
            $response = $this->crm->createNote(
                $validated['parent_module'],
                $validated['parent_id'],
                $validated['note_content'],
                $validated['note_title'] ?? null
            );

            if (isset($response['data'][0]['code']) && $response['data'][0]['code'] === 'SUCCESS') {
                $zohoNote = $response['data'][0]['details'];

                // Save locally with Zoho ID
                $validated['zoho_note_id'] = $zohoNote['id'];
                $validated['last_synced_at'] = now();
                $note = CrmNote::create($validated);
                return redirect()->route('crm.notes.show', $note)
                    ->with('success', __('dashboard.note_created_successfully'));
            } else {
                throw new \Exception($response['data'][0]['message'] ?? 'Unknown error');
            }
        } catch (\Exception $e) {
            Log::error('Error creating note: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_creating_note') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CrmNote $note)
    {
        return view('dashboard.crm.note.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CrmNote $note)
    {
        return view('dashboard.crm.note.edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CrmNote $note)
    {
        $validated = $request->validate([
            'note_title' => 'nullable|string|max:255',
            'note_content' => 'required|string',
            'parent_module' => 'nullable|string',
            'parent_name' => 'nullable|string',
        ]);

        try {
            // Update in Zoho CRM if it has zoho_note_id
            if ($note->zoho_note_id) {
                $noteData = [
                    'Note_Title' => $validated['note_title'] ?? '',
                    'Note_Content' => $validated['note_content'],
                ];

                $this->crm->updateNote($note->zoho_note_id, $noteData);
                $validated['last_synced_at'] = now();
            }

            // Update locally
            $note->update($validated);

            return redirect()->route('crm.notes.show', $note)
                ->with('success', __('dashboard.note_updated_successfully'));

        } catch (\Exception $e) {
            Log::error('Error updating note: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_updating_note') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CrmNote $note)
    {
        try {
            // Delete from Zoho CRM if it has zoho_note_id
            if ($note->zoho_note_id) {
                $this->crm->deleteNote($note->zoho_note_id);
            }

            // Delete locally
            $note->delete();

            return redirect()->route('crm.notes.index')
                ->with('success', __('dashboard.note_deleted_successfully'));

        } catch (\Exception $e) {
            Log::error('Error deleting note: ' . $e->getMessage());
            return back()->with('error', __('dashboard.error_deleting_note') . ': ' . $e->getMessage());
        }
    }

    /**
     * Sync notes from Zoho CRM
     */
    public function sync()
    {
        try {
            set_time_limit(0);

            $job = new SyncNotesFromZohoCRM();
            $job->handle($this->crm);

            return response()->json([
                'success' => true,
                'message' => __('dashboard.notes_synced_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing notes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_syncing_notes') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
