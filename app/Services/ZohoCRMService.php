<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Exception;

class ZohoCRMService
{
    protected $clientId;
    protected $clientSecret;
    protected $refreshToken;
    protected $apiDomain;
    protected $cacheKey = 'zoho_crm_access_token';

    public function __construct()
    {
        $this->clientId       = config('services.zoho.client_id');
        $this->clientSecret   = config('services.zoho.client_secret');
        $this->refreshToken   = config('services.zoho.refresh_token');
        $this->apiDomain      = config('services.zoho.crm_api_domain', 'https://www.zohoapis.com/crm/v3');
    }

    /**
     * Get access token from cache or refresh if expired
     */
    public function getAccessToken(): string
    {
        if (Cache::has($this->cacheKey)) {
            return Cache::get($this->cacheKey);
        }

        $tokenData = $this->refreshAccessToken();

        if (!isset($tokenData['access_token'])) {
            throw new Exception('Failed to obtain Zoho CRM access token.');
        }

        $expiresIn = isset($tokenData['expires_in']) ? (int)$tokenData['expires_in'] : 3600;
        $ttl = max(300, $expiresIn - 60);
        Cache::put($this->cacheKey, $tokenData['access_token'], $ttl);

        return $tokenData['access_token'];
    }

    /**
     * Refresh access token using refresh_token
     */
    public function refreshAccessToken(): array
    {
        $accountsUrl = 'https://accounts.zoho.com/oauth/v2/token';

        $response = Http::asForm()->post($accountsUrl, [
            'refresh_token' => $this->refreshToken,
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type'    => 'refresh_token',
        ]);

        if (!$response->successful()) {
            $body = $response->body();
            throw new Exception("Failed to refresh Zoho CRM token. Response: {$body}");
        }

        $json = $response->json();

        if (!isset($json['access_token'])) {
            throw new Exception("Zoho returned unexpected payload: " . json_encode($json));
        }

        return $json;
    }

    /**
     * Get default fields for a module
     */
    protected function getDefaultFields($module)
    {
        $defaultFields = [
            'Leads' => 'id,First_Name,Last_Name,Company,Email,Phone,Mobile,Lead_Status,Lead_Source,Industry,Website,Created_Time,Modified_Time,Owner',
            'Contacts' => 'id,First_Name,Last_Name,Email,Phone,Mobile,Account_Name,Title,Department,Mailing_Street,Mailing_City,Mailing_State,Created_Time,Modified_Time,Owner',
            'Accounts' => 'id,Account_Name,Phone,Website,Account_Type,Industry,Annual_Revenue,Employees,Rating,Billing_Street,Billing_City,Billing_State,Created_Time,Modified_Time,Owner',
            'Deals' => 'id,Deal_Name,Amount,Stage,Closing_Date,Account_Name,Contact_Name,Type,Probability,Next_Step,Lead_Source,Created_Time,Modified_Time,Owner',
            'Tasks' => 'id,Subject,Status,Priority,Due_Date,Description,Created_Time,Modified_Time,Owner',
            'Calls' => 'id,Subject,Call_Type,Call_Start_Time,Call_Duration,Description,Created_Time,Modified_Time,Owner',
            'Events' => 'id,Event_Title,Start_DateTime,End_DateTime,Venue,Participants,Description,Created_Time,Modified_Time,Owner',
            'Notes' => 'id,Note_Title,Note_Content,Parent_Id,Created_Time,Modified_Time,Owner',
        ];

        return $defaultFields[$module] ?? 'id';
    }

    /**
     * Ensure fields parameter exists
     */
    protected function ensureFields($module, array &$params)
    {
        if (!isset($params['fields'])) {
            $params['fields'] = $this->getDefaultFields($module);
        }
    }

    /**
     * Make API request to Zoho CRM
     */
    protected function apiRequest(string $method, string $path, array $params = [], $body = null)
    {
        $accessToken = $this->getAccessToken();

        $headers = [
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $url = rtrim($this->apiDomain, '/') . $path;
        $method = strtolower($method);

        if (!in_array($method, ['get', 'post', 'put', 'delete', 'patch'])) {
            throw new Exception('Unsupported HTTP method: ' . $method);
        }

        $client = Http::withHeaders($headers);

        // Make request
        if ($method === 'get' || $method === 'delete') {
            $response = $client->{$method}($url, $params);
        } else {
            $fullUrl = $url . ($params ? ('?' . http_build_query($params)) : '');
            $response = $client->{$method}($fullUrl, $body ?? []);
        }

        // Retry on 401
        if ($response->status() === 401) {
            Cache::forget($this->cacheKey);
            $accessToken = $this->getAccessToken();
            $headers['Authorization'] = 'Zoho-oauthtoken ' . $accessToken;

            $client = Http::withHeaders($headers);

            if ($method === 'get' || $method === 'delete') {
                $response = $client->{$method}($url, $params);
            } else {
                $fullUrl = $url . ($params ? ('?' . http_build_query($params)) : '');
                $response = $client->{$method}($fullUrl, $body ?? []);
            }
        }

        if (!$response->successful()) {
            $payload = $response->json() ?? $response->body();
            throw new Exception("Zoho CRM API error ({$response->status()}): " . json_encode($payload));
        }

        return $response->json();
    }

    // ==========================================
    // LEADS
    // ==========================================

    /**
     * Get all leads
     */
    public function getLeads(array $params = [])
    {
        $this->ensureFields('Leads', $params);
        return $this->apiRequest('GET', '/Leads', $params);
    }

    /**
     * Get specific lead by ID
     */
    public function getLead($leadId, array $params = [])
    {
        $this->ensureFields('Leads', $params);
        return $this->apiRequest('GET', "/Leads/{$leadId}", $params);
    }

    /**
     * Create new lead
     */
    public function createLead(array $data)
    {
        $body = [
            'data' => [$data]
        ];

        return $this->apiRequest('POST', '/Leads', [], $body);
    }

    /**
     * Update lead
     */
    public function updateLead($leadId, array $data)
    {
        $body = [
            'data' => [$data]
        ];

        return $this->apiRequest('PUT', "/Leads/{$leadId}", [], $body);
    }

    /**
     * Delete lead
     */
    public function deleteLead($leadId)
    {
        return $this->apiRequest('DELETE', "/Leads/{$leadId}");
    }

/**
 * Convert lead to contact/account/deal
 */
public function convertLead($leadId, array $conversionData = [])
{
    // إذا لم يتم تمرير data، استخدم conversion بسيط
    if (empty($conversionData)) {
        $conversionData = [
            'data' => [
                [
                    'overwrite' => true,
                    'notify_lead_owner' => true,
                    'notify_new_entity_owner' => true,
                ]
            ]
        ];
    } else {
        // تأكد من وجود data wrapper
        if (!isset($conversionData['data'])) {
            $conversionData = [
                'data' => [$conversionData]
            ];
        }
    }

    return $this->apiRequest('POST', "/Leads/{$leadId}/actions/convert", [], $conversionData);
}
    // ==========================================
    // CONTACTS
    // ==========================================

    /**
     * Get all contacts
     */
    public function getContacts(array $params = [])
    {
        $this->ensureFields('Contacts', $params);
        return $this->apiRequest('GET', '/Contacts', $params);
    }

    /**
     * Get specific contact by ID
     */
    public function getContact($contactId, array $params = [])
    {
        $this->ensureFields('Contacts', $params);
        return $this->apiRequest('GET', "/Contacts/{$contactId}", $params);
    }

    /**
     * Create new contact
     */
    public function createContact(array $data)
    {
        $body = [
            'data' => [$data]
        ];

        return $this->apiRequest('POST', '/Contacts', [], $body);
    }

    /**
     * Update contact
     */
    public function updateContact($contactId, array $data)
    {
        $body = [
            'data' => [$data]
        ];

        return $this->apiRequest('PUT', "/Contacts/{$contactId}", [], $body);
    }

    /**
     * Delete contact
     */
    public function deleteContact($contactId)
    {
        return $this->apiRequest('DELETE', "/Contacts/{$contactId}");
    }

    // ==========================================
    // ACCOUNTS
    // ==========================================

    /**
     * Get all accounts
     */
    public function getAccounts(array $params = [])
    {
        $this->ensureFields('Accounts', $params);
        return $this->apiRequest('GET', '/Accounts', $params);
    }

    /**
     * Get specific account by ID
     */
    public function getAccount($accountId, array $params = [])
    {
        $this->ensureFields('Accounts', $params);
        return $this->apiRequest('GET', "/Accounts/{$accountId}", $params);
    }

    /**
     * Create new account
     */
    public function createAccount(array $data)
    {
        $body = [
            'data' => [$data]
        ];

        return $this->apiRequest('POST', '/Accounts', [], $body);
    }

    /**
     * Update account
     */
    public function updateAccount($accountId, array $data)
    {
        $body = [
            'data' => [$data]
        ];

        return $this->apiRequest('PUT', "/Accounts/{$accountId}", [], $body);
    }

    /**
     * Delete account
     */
    public function deleteAccount($accountId)
    {
        return $this->apiRequest('DELETE', "/Accounts/{$accountId}");
    }

    // ==========================================
    // DEALS
    // ==========================================

    /**
     * Get all deals
     */
    public function getDeals(array $params = [])
    {
        $this->ensureFields('Deals', $params);
        return $this->apiRequest('GET', '/Deals', $params);
    }

    /**
     * Get specific deal by ID
     */
    public function getDeal($dealId, array $params = [])
    {
        $this->ensureFields('Deals', $params);
        return $this->apiRequest('GET', "/Deals/{$dealId}", $params);
    }

    /**
     * Create new deal
     */
    public function createDeal(array $data)
    {
        $body = [
            'data' => [$data]
        ];

        return $this->apiRequest('POST', '/Deals', [], $body);
    }

    /**
     * Update deal
     */
    public function updateDeal($dealId, array $data)
    {
        $body = [
            'data' => [$data]
        ];

        return $this->apiRequest('PUT', "/Deals/{$dealId}", [], $body);
    }

    /**
     * Delete deal
     */
    public function deleteDeal($dealId)
    {
        return $this->apiRequest('DELETE', "/Deals/{$dealId}");
    }

    // ==========================================
    // TASKS
    // ==========================================

    /**
     * Get all tasks
     */
    public function getTasks(array $params = [])
    {
        $this->ensureFields('Tasks', $params);
        return $this->apiRequest('GET', '/Tasks', $params);
    }

    /**
     * Get specific task by ID
     */
    public function getTask($taskId, array $params = [])
    {
        $this->ensureFields('Tasks', $params);
        return $this->apiRequest('GET', "/Tasks/{$taskId}", $params);
    }

    /**
     * Create new task
     */
    public function createTask(array $data)
    {
        $body = [
            'data' => [$data]
        ];

        return $this->apiRequest('POST', '/Tasks', [], $body);
    }

    /**
     * Update task
     */
    public function updateTask($taskId, array $data)
    {
        $body = [
            'data' => [$data]
        ];

        return $this->apiRequest('PUT', "/Tasks/{$taskId}", [], $body);
    }

    /**
     * Delete task
     */
    public function deleteTask($taskId)
    {
        return $this->apiRequest('DELETE', "/Tasks/{$taskId}");
    }

    // ==========================================
    // CALLS
    // ==========================================

    /**
     * Get all calls
     */
    public function getCalls(array $params = [])
    {
        $this->ensureFields('Calls', $params);
        return $this->apiRequest('GET', '/Calls', $params);
    }

    /**
     * Get specific call by ID
     */
    public function getCall($callId, array $params = [])
    {
        $this->ensureFields('Calls', $params);
        return $this->apiRequest('GET', "/Calls/{$callId}", $params);
    }

    /**
     * Create new call
     */
    public function createCall(array $data)
    {
        $body = [
            'data' => [$data]
        ];

        return $this->apiRequest('POST', '/Calls', [], $body);
    }

    /**
     * Update call
     */
    public function updateCall($callId, array $data)
    {
        $body = [
            'data' => [$data]
        ];

        return $this->apiRequest('PUT', "/Calls/{$callId}", [], $body);
    }

    /**
     * Delete call
     */
    public function deleteCall($callId)
    {
        return $this->apiRequest('DELETE', "/Calls/{$callId}");
    }

    // ==========================================
    // MEETINGS / EVENTS
    // ==========================================

    /**
     * Get all meetings
     */
    public function getMeetings(array $params = [])
    {
        $this->ensureFields('Events', $params);
        return $this->apiRequest('GET', '/Events', $params);
    }

    /**
     * Get specific meeting by ID
     */
    public function getMeeting($eventId, array $params = [])
    {
        $this->ensureFields('Events', $params);
        return $this->apiRequest('GET', "/Events/{$eventId}", $params);
    }

    /**
     * Create new meeting
     */
    public function createMeeting(array $data)
    {
        $body = [
            'data' => [$data]
        ];

        return $this->apiRequest('POST', '/Events', [], $body);
    }

    /**
     * Update meeting
     */
    public function updateMeeting($eventId, array $data)
    {
        $body = [
            'data' => [$data]
        ];

        return $this->apiRequest('PUT', "/Events/{$eventId}", [], $body);
    }

 

    // ==========================================
    // NOTES
    // ==========================================

    /**
     * Get notes for a record
     */
    public function getNotes($module, $recordId, array $params = [])
    {
        $this->ensureFields('Notes', $params);
        return $this->apiRequest('GET', "/{$module}/{$recordId}/Notes", $params);
    }

    /**
     * Create note for a record
     */
    public function createNote($module, $recordId, $noteContent, $noteTitle = null)
    {
        $noteData = [
            'Note_Content' => $noteContent,
            'Parent_Id' => [
                'id' => $recordId
            ]
        ];

        if ($noteTitle) {
            $noteData['Note_Title'] = $noteTitle;
        }

        $body = [
            'data' => [$noteData]
        ];

        return $this->apiRequest('POST', '/Notes', [], $body);
    }

    /**
     * Update note
     */
    public function updateNote($noteId, array $data)
    {
        $body = [
            'data' => [$data]
        ];

        return $this->apiRequest('PUT', "/Notes/{$noteId}", [], $body);
    }

    /**
     * Delete note
     */
    public function deleteNote($noteId)
    {
        return $this->apiRequest('DELETE', "/Notes/{$noteId}");
    }

    // ==========================================
    // SEARCH
    // ==========================================

    /**
     * Search records by criteria
     */
    public function search($module, $criteria, $params = [])
    {
        $this->ensureFields($module, $params);
        $params['criteria'] = $criteria;
        return $this->apiRequest('GET', "/{$module}/search", $params);
    }

    /**
     * Search by email
     */
    public function searchByEmail($module, $email, $params = [])
    {
        return $this->search($module, "(Email:equals:{$email})", $params);
    }

    /**
     * Search by phone
     */
    public function searchByPhone($module, $phone, $params = [])
    {
        return $this->search($module, "(Phone:equals:{$phone})", $params);
    }

    /**
     * Search by name
     */
    public function searchByName($module, $name, $params = [])
    {
        if ($module === 'Leads' || $module === 'Contacts') {
            return $this->search($module, "(Last_Name:equals:{$name})", $params);
        } elseif ($module === 'Accounts') {
            return $this->search($module, "(Account_Name:equals:{$name})", $params);
        } elseif ($module === 'Deals') {
            return $this->search($module, "(Deal_Name:equals:{$name})", $params);
        }

        throw new Exception("Search by name not supported for module: {$module}");
    }

    // ==========================================
    // BULK OPERATIONS
    // ==========================================

    /**
     * Create multiple records
     */
    public function bulkCreate($module, array $records)
    {
        $body = [
            'data' => $records
        ];

        return $this->apiRequest('POST', "/{$module}", [], $body);
    }

    /**
     * Update multiple records
     */
    public function bulkUpdate($module, array $records)
    {
        $body = [
            'data' => $records
        ];

        return $this->apiRequest('PUT', "/{$module}", [], $body);
    }

    /**
     * Delete multiple records
     */
    public function bulkDelete($module, array $ids)
    {
        $params = ['ids' => implode(',', $ids)];
        return $this->apiRequest('DELETE', "/{$module}", $params);
    }

    // ==========================================
    // RELATED RECORDS
    // ==========================================

    /**
     * Get related records
     */
    public function getRelatedRecords($module, $recordId, $relatedModule, array $params = [])
    {
        $this->ensureFields($relatedModule, $params);
        return $this->apiRequest('GET', "/{$module}/{$recordId}/{$relatedModule}", $params);
    }

    /**
     * Associate related records
     */
    public function associateRelatedRecords($module, $recordId, $relatedModule, array $relatedIds)
    {
        $body = [
            'data' => array_map(function($id) {
                return ['id' => $id];
            }, $relatedIds)
        ];

        return $this->apiRequest('PUT', "/{$module}/{$recordId}/{$relatedModule}", [], $body);
    }

    /**
     * Dissociate related records
     */
    public function dissociateRelatedRecords($module, $recordId, $relatedModule, array $relatedIds)
    {
        $params = ['ids' => implode(',', $relatedIds)];
        return $this->apiRequest('DELETE', "/{$module}/{$recordId}/{$relatedModule}", $params);
    }

    // ==========================================
    // METADATA
    // ==========================================

    /**
     * Get module metadata
     */
    public function getModuleMetadata($module)
    {
        return $this->apiRequest('GET', "/settings/modules/{$module}");
    }

    /**
     * Get all modules
     */
    public function getAllModules()
    {
        return $this->apiRequest('GET', '/settings/modules');
    }

    /**
     * Get fields metadata
     */
    public function getFieldsMetadata($module)
    {
        return $this->apiRequest('GET', "/settings/fields", ['module' => $module]);
    }

    // ==========================================
    // USERS
    // ==========================================

    /**
     * Get all users
     */
    public function getUsers(array $params = [])
    {
        return $this->apiRequest('GET', '/users', $params);
    }

    /**
     * Get specific user
     */
    public function getUser($userId)
    {
        return $this->apiRequest('GET', "/users/{$userId}");
    }

    /**
     * Get current user
     */
    public function getCurrentUser()
    {
        return $this->apiRequest('GET', '/users?type=CurrentUser');
    }
}
