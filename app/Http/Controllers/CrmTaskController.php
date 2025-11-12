<?php

namespace App\Http\Controllers;

use App\Jobs\SyncTasksFromZohoCRM;
use App\Models\CrmTask;
use App\Services\ZohoCRMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CrmTaskController extends Controller
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
        $query = CrmTask::query();

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        $tasks = $query->latest()->paginate(20);

        return view('dashboard.crm.task.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.crm.task.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string',
            'priority' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        try {
            // Create in Zoho CRM first
            $zohoData = [
                'Subject' => $validated['subject'],
            ];

            if (!empty($validated['due_date'])) {
                $zohoData['Due_Date'] = $validated['due_date'];
            }
            if (!empty($validated['status'])) {
                $zohoData['Status'] = $validated['status'];
            }
            if (!empty($validated['priority'])) {
                $zohoData['Priority'] = $validated['priority'];
            }
            if (!empty($validated['description'])) {
                $zohoData['Description'] = $validated['description'];
            }

            $response = $this->crm->createTask($zohoData);

            if (isset($response['data'][0]['details']['id'])) {
                $zohoTaskId = $response['data'][0]['details']['id'];

                // Create in local database
                $validated['zoho_task_id'] = $zohoTaskId;
                $validated['last_synced_at'] = now();
                CrmTask::create($validated);

                return redirect()->route('crm.tasks.index')
                    ->with('success', __('dashboard.task_created_successfully'));
            }

            throw new \Exception('Failed to get Zoho task ID from response');

        } catch (\Exception $e) {
            Log::error('Error creating task: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_creating_task') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CrmTask $task)
    {
        return view('dashboard.crm.task.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CrmTask $task)
    {
        return view('dashboard.crm.task.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CrmTask $task)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string',
            'priority' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        try {
            // Update in Zoho CRM if synced
            if ($task->zoho_task_id) {
                $zohoData = [
                    'Subject' => $validated['subject'],
                    'Due_Date' => $validated['due_date'],
                    'Status' => $validated['status'],
                    'Priority' => $validated['priority'],
                    'Description' => $validated['description'],
                ];

                $this->crm->updateTask($task->zoho_task_id, $zohoData);
                $validated['last_synced_at'] = now();
            }

            // Update in local database
            $task->update($validated);

            return redirect()->route('crm.tasks.index')
                ->with('success', __('dashboard.task_updated_successfully'));

        } catch (\Exception $e) {
            Log::error('Error updating task: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_updating_task') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CrmTask $task)
    {
        try {
            // Delete from Zoho CRM if synced
            if ($task->zoho_task_id) {
                $this->crm->deleteTask($task->zoho_task_id);
            }

            // Delete from local database
            $task->delete();

            return redirect()->route('crm.tasks.index')
                ->with('success', __('dashboard.task_deleted_successfully'));

        } catch (\Exception $e) {
            Log::error('Error deleting task: ' . $e->getMessage());
            return back()->with('error', __('dashboard.error_deleting_task') . ': ' . $e->getMessage());
        }
    }

    /**
     * Sync tasks from Zoho CRM
     */
    public function sync()
    {
        try {
            set_time_limit(0);

            // Run sync synchronously instead of dispatching to queue
            $job = new SyncTasksFromZohoCRM();
            $job->handle(app(\App\Services\ZohoCRMService::class));

            return response()->json([
                'success' => true,
                'message' => __('dashboard.tasks_synced_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing tasks: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_syncing_tasks') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
