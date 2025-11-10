<?php

namespace App\Http\Controllers\Api;

use App\Services\ZohoCRMService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CRMController extends Controller
{
    protected $crm;

    public function __construct(ZohoCRMService $crm)
    {
        $this->crm = $crm;
    }

    // ==========================================
    // LEADS
    // ==========================================

    public function getLeads(Request $request)
    {
        try {
            $params = $request->only(['page', 'per_page', 'fields']);
            $leads = $this->crm->getLeads($params);
            return response()->json($leads);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getLead($id)
    {
        try {
            $lead = $this->crm->getLead($id);
            return response()->json($lead);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createLead(Request $request)
    {
        try {
            $request->validate([
                'Last_Name' => 'required',
                'Company' => 'required',
            ]);

            $lead = $this->crm->createLead([
                'Last_Name' => $request->Last_Name,
                'First_Name' => $request->First_Name,
                'Company' => $request->Company,
                'Email' => $request->Email,
                'Phone' => $request->Phone,
                'Lead_Source' => $request->Lead_Source,
                'Lead_Status' => $request->Lead_Status ?? 'Not Contacted',
            ]);

            return response()->json([
                'success' => true,
                'data' => $lead
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateLead(Request $request, $id)
    {
        try {
            $lead = $this->crm->updateLead($id, $request->all());
            return response()->json($lead);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteLead($id)
    {
        try {
            $result = $this->crm->deleteLead($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

/**
 * Convert lead to contact/account/deal
 */
public function convertLead(Request $request, $id)
{
    try {
        $conversionData = [];

        // Base conversion settings
        $conversionData['overwrite'] = $request->input('overwrite', true);
        $conversionData['notify_lead_owner'] = $request->input('notify_lead_owner', true);
        $conversionData['notify_new_entity_owner'] = $request->input('notify_new_entity_owner', true);

        // Optional: Create deal during conversion
        if ($request->has('create_deal') && $request->create_deal) {
            $conversionData['Deals'] = [
                'Deal_Name' => $request->input('deal_name'),
                'Closing_Date' => $request->input('closing_date'),
                'Stage' => $request->input('stage', 'Qualification'),
                'Amount' => $request->input('amount'),
            ];
        }

        // Optional: Associate with existing account
        if ($request->has('account_id')) {
            $conversionData['Accounts'] = [
                'id' => $request->account_id
            ];
        }

        // Optional: Create new account
        if ($request->has('account_name') && !$request->has('account_id')) {
            $conversionData['Accounts'] = [
                'Account_Name' => $request->account_name
            ];
        }

        $result = $this->crm->convertLead($id, $conversionData);

        return response()->json([
            'success' => true,
            'message' => 'Lead converted successfully',
            'data' => $result
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}
    // ==========================================
    // CONTACTS
    // ==========================================

    public function getContacts(Request $request)
    {
        try {
            $params = $request->only(['page', 'per_page', 'fields']);
            $contacts = $this->crm->getContacts($params);
            return response()->json($contacts);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createContact(Request $request)
    {
        try {
            $request->validate([
                'Last_Name' => 'required',
            ]);

            $contact = $this->crm->createContact([
                'Last_Name' => $request->Last_Name,
                'First_Name' => $request->First_Name,
                'Email' => $request->Email,
                'Phone' => $request->Phone,
                'Account_Name' => $request->Account_Name,
            ]);

            return response()->json([
                'success' => true,
                'data' => $contact
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // ACCOUNTS
    // ==========================================

    public function getAccounts(Request $request)
    {
        try {
            $params = $request->only(['page', 'per_page', 'fields']);
            $accounts = $this->crm->getAccounts($params);
            return response()->json($accounts);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createAccount(Request $request)
    {
        try {
            $request->validate([
                'Account_Name' => 'required',
            ]);

            $account = $this->crm->createAccount([
                'Account_Name' => $request->Account_Name,
                'Phone' => $request->Phone,
                'Website' => $request->Website,
                'Account_Type' => $request->Account_Type,
            ]);

            return response()->json([
                'success' => true,
                'data' => $account
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // DEALS
    // ==========================================

    public function getDeals(Request $request)
    {
        try {
            $params = $request->only(['page', 'per_page', 'fields']);
            $deals = $this->crm->getDeals($params);
            return response()->json($deals);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

  /**
 * Create deal
 */
public function createDeal(Request $request)
{
    try {
        $request->validate([
            'Deal_Name' => 'required',
            'Stage' => 'required',
            'Closing_Date' => 'required|date',
        ]);

        $dealData = [
            'Deal_Name' => $request->Deal_Name,
            'Stage' => $request->Stage,
            'Closing_Date' => $request->Closing_Date,
        ];

        // Amount (optional)
        if ($request->has('Amount')) {
            $dealData['Amount'] = $request->Amount;
        }

        // Account Name - must be ID
        if ($request->has('Account_Name')) {
            // إذا كان string، نعتبره ID
            if (is_string($request->Account_Name)) {
                $dealData['Account_Name'] = [
                    'id' => $request->Account_Name
                ];
            } else {
                $dealData['Account_Name'] = $request->Account_Name;
            }
        }

        // Contact Name - must be ID
        if ($request->has('Contact_Name')) {
            // إذا كان string، نعتبره ID
            if (is_string($request->Contact_Name)) {
                $dealData['Contact_Name'] = [
                    'id' => $request->Contact_Name
                ];
            } else {
                $dealData['Contact_Name'] = $request->Contact_Name;
            }
        }

        // Optional fields
        if ($request->has('Type')) {
            $dealData['Type'] = $request->Type;
        }

        if ($request->has('Probability')) {
            $dealData['Probability'] = $request->Probability;
        }

        if ($request->has('Next_Step')) {
            $dealData['Next_Step'] = $request->Next_Step;
        }

        if ($request->has('Lead_Source')) {
            $dealData['Lead_Source'] = $request->Lead_Source;
        }

        if ($request->has('Description')) {
            $dealData['Description'] = $request->Description;
        }

        $deal = $this->crm->createDeal($dealData);

        return response()->json([
            'success' => true,
            'data' => $deal
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => 'Validation failed',
            'messages' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}

    // ==========================================
    // SEARCH
    // ==========================================

    public function search(Request $request)
    {
        try {
            $module = $request->input('module', 'Leads');
            $criteria = $request->input('criteria');

            $results = $this->crm->search($module, $criteria);
            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


 public function getTasks(Request $request)
    {
        try {
            $params = $request->only(['page', 'per_page', 'fields']);
            $tasks = $this->crm->getTasks($params);
            return response()->json($tasks);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTask($id)
    {
        try {
            $task = $this->crm->getTask($id);
            return response()->json($task);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createTask(Request $request)
    {
        try {
            $request->validate([
                'Subject' => 'required',
            ]);

            $task = $this->crm->createTask([
                'Subject' => $request->Subject,
                'Status' => $request->Status ?? 'Not Started',
                'Priority' => $request->Priority ?? 'Normal',
                'Due_Date' => $request->Due_Date,
                'Description' => $request->Description,
            ]);

            return response()->json([
                'success' => true,
                'data' => $task
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateTask(Request $request, $id)
    {
        try {
            $task = $this->crm->updateTask($id, $request->all());
            return response()->json($task);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteTask($id)
    {
        try {
            $result = $this->crm->deleteTask($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // CALLS
    // ==========================================

    public function getCalls(Request $request)
    {
        try {
            $params = $request->only(['page', 'per_page', 'fields']);
            $calls = $this->crm->getCalls($params);
            return response()->json($calls);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCall($id)
    {
        try {
            $call = $this->crm->getCall($id);
            return response()->json($call);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createCall(Request $request)
    {
        try {
            $request->validate([
                'Subject' => 'required',
                'Call_Type' => 'required',
            ]);

            $call = $this->crm->createCall([
                'Subject' => $request->Subject,
                'Call_Type' => $request->Call_Type,
                'Call_Start_Time' => $request->Call_Start_Time,
                'Call_Duration' => $request->Call_Duration,
                'Description' => $request->Description,
            ]);

            return response()->json([
                'success' => true,
                'data' => $call
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateCall(Request $request, $id)
    {
        try {
            $call = $this->crm->updateCall($id, $request->all());
            return response()->json($call);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteCall($id)
    {
        try {
            $result = $this->crm->deleteCall($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // MEETINGS
    // ==========================================

    public function getMeetings(Request $request)
    {
        try {
            $params = $request->only(['page', 'per_page', 'fields']);
            $meetings = $this->crm->getMeetings($params);
            return response()->json($meetings);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getMeeting($id)
    {
        try {
            $meeting = $this->crm->getMeeting($id);
            return response()->json($meeting);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createMeeting(Request $request)
    {
        try {
            $request->validate([
                'Event_Title' => 'required',
                'Start_DateTime' => 'required',
                'End_DateTime' => 'required',
            ]);

            $meeting = $this->crm->createMeeting([
                'Event_Title' => $request->Event_Title,
                'Start_DateTime' => $request->Start_DateTime,
                'End_DateTime' => $request->End_DateTime,
                'Venue' => $request->Venue,
                'Participants' => $request->Participants,
                'Description' => $request->Description,
            ]);

            return response()->json([
                'success' => true,
                'data' => $meeting
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateMeeting(Request $request, $id)
    {
        try {
            $meeting = $this->crm->updateMeeting($id, $request->all());
            return response()->json($meeting);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

 

    // ==========================================
    // NOTES
    // ==========================================

    public function getNotes($module, $recordId)
    {
        try {
            $notes = $this->crm->getNotes($module, $recordId);
            return response()->json($notes);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createNote(Request $request, $module, $recordId)
    {
        try {
            $request->validate([
                'Note_Content' => 'required',
            ]);

            $note = $this->crm->createNote(
                $module,
                $recordId,
                $request->Note_Content,
                $request->Note_Title
            );

            return response()->json([
                'success' => true,
                'data' => $note
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateNote(Request $request, $id)
    {
        try {
            $note = $this->crm->updateNote($id, $request->all());
            return response()->json($note);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteNote($id)
    {
        try {
            $result = $this->crm->deleteNote($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // SEARCH
    // ==========================================



    public function searchByEmail(Request $request)
    {
        try {
            $module = $request->input('module', 'Leads');
            $email = $request->input('email');

            if (!$email) {
                return response()->json([
                    'error' => 'email parameter is required'
                ], 400);
            }

            $results = $this->crm->searchByEmail($module, $email);
            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function searchByPhone(Request $request)
    {
        try {
            $module = $request->input('module', 'Leads');
            $phone = $request->input('phone');

            if (!$phone) {
                return response()->json([
                    'error' => 'phone parameter is required'
                ], 400);
            }

            $results = $this->crm->searchByPhone($module, $phone);
            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function searchByName(Request $request)
    {
        try {
            $module = $request->input('module', 'Leads');
            $name = $request->input('name');

            if (!$name) {
                return response()->json([
                    'error' => 'name parameter is required'
                ], 400);
            }

            $results = $this->crm->searchByName($module, $name);
            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // BULK OPERATIONS
    // ==========================================

    public function bulkCreate(Request $request, $module)
    {
        try {
            $request->validate([
                'records' => 'required|array|min:1|max:100',
            ]);

            $result = $this->crm->bulkCreate($module, $request->records);
            return response()->json([
                'success' => true,
                'data' => $result
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function bulkUpdate(Request $request, $module)
    {
        try {
            $request->validate([
                'records' => 'required|array|min:1|max:100',
            ]);

            $result = $this->crm->bulkUpdate($module, $request->records);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function bulkDelete(Request $request, $module)
    {
        try {
            $request->validate([
                'ids' => 'required|array|min:1|max:100',
            ]);

            $result = $this->crm->bulkDelete($module, $request->ids);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // RELATED RECORDS
    // ==========================================

    public function getRelatedRecords($module, $recordId, $relatedModule)
    {
        try {
            $results = $this->crm->getRelatedRecords($module, $recordId, $relatedModule);
            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function associateRelatedRecords(Request $request, $module, $recordId, $relatedModule)
    {
        try {
            $request->validate([
                'ids' => 'required|array|min:1',
            ]);

            $result = $this->crm->associateRelatedRecords($module, $recordId, $relatedModule, $request->ids);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function dissociateRelatedRecords(Request $request, $module, $recordId, $relatedModule)
    {
        try {
            $request->validate([
                'ids' => 'required|array|min:1',
            ]);

            $result = $this->crm->dissociateRelatedRecords($module, $recordId, $relatedModule, $request->ids);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // METADATA
    // ==========================================

    public function getAllModules()
    {
        try {
            $modules = $this->crm->getAllModules();
            return response()->json($modules);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getModuleMetadata($module)
    {
        try {
            $metadata = $this->crm->getModuleMetadata($module);
            return response()->json($metadata);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getFieldsMetadata($module)
    {
        try {
            $fields = $this->crm->getFieldsMetadata($module);
            return response()->json($fields);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // USERS
    // ==========================================

    public function getUsers(Request $request)
    {
        try {
            $params = $request->only(['page', 'per_page']);
            $users = $this->crm->getUsers($params);
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUser($id)
    {
        try {
            $user = $this->crm->getUser($id);
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCurrentUser()
    {
        try {
            $user = $this->crm->getCurrentUser();
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



}
