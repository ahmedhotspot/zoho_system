<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Exception;

class ZohoBooksService
{
    protected $clientId;
    protected $clientSecret;
    protected $refreshToken;
    protected $organizationId;
    protected $apiDomain;
    protected $cacheKey = 'zoho_books_access_token';

    public function __construct()
    {
        $this->clientId       = config('services.zoho.client_id');
        $this->clientSecret   = config('services.zoho.client_secret');
        $this->refreshToken   = config('services.zoho.refresh_token');
        $this->organizationId = config('services.zoho.books_org_id');
        $this->apiDomain      = config('services.zoho.books_api_domain', 'https://www.zohoapis.com/books/v3');
    }

    public function getAccessToken(): string
    {
        if (Cache::has($this->cacheKey)) {
            return Cache::get($this->cacheKey);
        }

        $tokenData = $this->refreshAccessToken();

        if (!isset($tokenData['access_token'])) {
            throw new Exception('Failed to obtain Zoho Books access token.');
        }

        $expiresIn = isset($tokenData['expires_in']) ? (int)$tokenData['expires_in'] : 3600;
        $ttl = max(300, $expiresIn - 60);
        Cache::put($this->cacheKey, $tokenData['access_token'], $ttl);

        return $tokenData['access_token'];
    }

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
            throw new Exception("Failed to refresh Zoho Books token. Response: {$body}");
        }

        $json = $response->json();

        if (!isset($json['access_token'])) {
            throw new Exception("Zoho returned unexpected payload: " . json_encode($json));
        }

        return $json;
    }

    /**
     * Make API request to Zoho Books
     */
    protected function apiRequest(string $method, string $path, array $params = [], $body = null)
    {
        $accessToken = $this->getAccessToken();

        // Books always requires organization_id
        $params['organization_id'] = $this->organizationId;

        $headers = [
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Accept' => 'application/json',
        ];

        $url = rtrim($this->apiDomain, '/') . $path;
        $method = strtolower($method);

        if (!in_array($method, ['get', 'post', 'put', 'delete', 'patch'])) {
            throw new Exception('Unsupported HTTP method: ' . $method);
        }

        // Prepare client - use asForm() for POST/PUT/PATCH with body
        $client = Http::withHeaders($headers);

        if (in_array($method, ['post', 'put', 'patch']) && $body !== null) {
            $client = $client->asForm();
        }

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

            if (in_array($method, ['post', 'put', 'patch']) && $body !== null) {
                $client = $client->asForm();
            }

            if ($method === 'get' || $method === 'delete') {
                $response = $client->{$method}($url, $params);
            } else {
                $fullUrl = $url . ($params ? ('?' . http_build_query($params)) : '');
                $response = $client->{$method}($fullUrl, $body ?? []);
            }
        }

        if (!$response->successful()) {
            $payload = $response->json() ?? $response->body();
            throw new Exception("Zoho Books API error ({$response->status()}): " . json_encode($payload));
        }

        return $response->json();
    }

    // ==========================================
    // ORGANIZATIONS
    // ==========================================

    public function getOrganizations()
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withHeaders([
            'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
            'Accept' => 'application/json',
        ])->get($this->apiDomain . '/organizations');

        if (!$response->successful()) {
            throw new Exception("Failed to get organizations: " . $response->body());
        }

        return $response->json();
    }

    // ==========================================
    // INVOICES
    // ==========================================

    public function getInvoices(array $params = [])
    {
        return $this->apiRequest('GET', '/invoices', $params);
    }

    public function getInvoice($invoiceId)
    {
        return $this->apiRequest('GET', "/invoices/{$invoiceId}");
    }

    public function createInvoice(array $data)
    {
        $body = [
            'JSONString' => json_encode($data)
        ];

        return $this->apiRequest('POST', '/invoices', [], $body);
    }

    public function updateInvoice($invoiceId, array $data)
    {
        $body = [
            'JSONString' => json_encode($data)
        ];

        return $this->apiRequest('PUT', "/invoices/{$invoiceId}", [], $body);
    }

    public function deleteInvoice($invoiceId)
    {
        return $this->apiRequest('DELETE', "/invoices/{$invoiceId}");
    }
public function sendInvoice($invoiceId, array $emailData = [])
{
    // إذا لم يتم تمرير بيانات، استخدم القيم الافتراضية
    $data = $emailData ?: new \stdClass(); // Empty object

    $body = [
        'JSONString' => json_encode($data)
    ];


    return $this->apiRequest('POST', "/invoices/{$invoiceId}/email", [], $body);
}
    public function markInvoiceAsSent($invoiceId)
    {
        return $this->apiRequest('POST', "/invoices/{$invoiceId}/status/sent");
    }

    public function voidInvoice($invoiceId)
    {
        return $this->apiRequest('POST', "/invoices/{$invoiceId}/status/void");
    }

    // ==========================================
    // CUSTOMERS / CONTACTS
    // ==========================================

    public function getCustomers(array $params = [])
    {
        return $this->apiRequest('GET', '/contacts', $params);
    }

    public function getCustomer($customerId)
    {
        return $this->apiRequest('GET', "/contacts/{$customerId}");
    }

    public function createCustomer(array $data)
    {
        $body = [
            'JSONString' => json_encode($data)
        ];

        return $this->apiRequest('POST', '/contacts', [], $body);
    }

    public function updateCustomer($customerId, array $data)
    {
        $body = [
            'JSONString' => json_encode($data)
        ];

        return $this->apiRequest('PUT', "/contacts/{$customerId}", [], $body);
    }

    public function deleteCustomer($customerId)
    {
        return $this->apiRequest('DELETE', "/contacts/{$customerId}");
    }




    // ==========================================
    // ITEMS / PRODUCTS
    // ==========================================

    public function getItems(array $params = [])
    {
        return $this->apiRequest('GET', '/items', $params);
    }

    public function getItem($itemId)
    {
        return $this->apiRequest('GET', "/items/{$itemId}");
    }

    public function createItem(array $data)
    {
        $body = [
            'JSONString' => json_encode($data)
        ];

        return $this->apiRequest('POST', '/items', [], $body);
    }

    public function updateItem($itemId, array $data)
    {
        $body = [
            'JSONString' => json_encode($data)
        ];

        return $this->apiRequest('PUT', "/items/{$itemId}", [], $body);
    }

    public function deleteItem($itemId)
    {
        return $this->apiRequest('DELETE', "/items/{$itemId}");
    }

    // ==========================================
    // ESTIMATES
    // ==========================================

    public function getEstimates(array $params = [])
    {
        return $this->apiRequest('GET', '/estimates', $params);
    }

    public function getEstimate(string $estimateId)
    {
        return $this->apiRequest('GET', "/estimates/{$estimateId}");
    }

    public function createEstimate(array $data)
    {
        $body = [
            'JSONString' => json_encode($data)
        ];

        return $this->apiRequest('POST', '/estimates', [], $body);
    }

    public function updateEstimate($estimateId, array $data)
    {
        $body = [
            'JSONString' => json_encode($data)
        ];

        return $this->apiRequest('PUT', "/estimates/{$estimateId}", [], $body);
    }

    public function deleteEstimate(string $estimateId)
    {
        return $this->apiRequest('DELETE', "/estimates/{$estimateId}");
    }

    // ==========================================
    // BILLS
    // ==========================================

    public function getBills(array $params = [])
    {
        return $this->apiRequest('GET', '/bills', $params);
    }

    public function getBill(string $billId)
    {
        return $this->apiRequest('GET', "/bills/{$billId}");
    }

    public function createBill(array $data)
    {
        $body = [
            'JSONString' => json_encode($data)
        ];

        return $this->apiRequest('POST', '/bills', [], $body);
    }

    public function updateBill($billId, array $data)
    {
        $body = [
            'JSONString' => json_encode($data)
        ];
        return $this->apiRequest('PUT', "/bills/{$billId}", [], $body);
    }

    public function deleteBill(string $billId)
    {
        return $this->apiRequest('DELETE', "/bills/{$billId}");
    }



// ==========================================
//  ACCOUNTS
// ==========================================

/**
 * Get all accounts
 */
public function getAccounts(array $params = [])
{
    return $this->apiRequest('GET', '/chartofaccounts', $params);
}

/**
 * Get specific account
 */
public function getAccount($accountId)
{
    return $this->apiRequest('GET', "/chartofaccounts/{$accountId}");
}

/**
 * Get expense accounts only
 */
public function getExpenseAccounts()
{
    return $this->apiRequest('GET', '/chartofaccounts', [
        'filter_by' => 'AccountType.Expense'
    ]);
}

    // ==========================================
    // EXPENSES
    // ==========================================

    public function getExpenses(array $params = [])
    {
        return $this->apiRequest('GET', '/expenses', $params);
    }

    public function getExpense(string $expenseId)
    {
        return $this->apiRequest('GET', "/expenses/{$expenseId}");
    }

    public function createExpense(array $data)
    {
        $body = [
            'JSONString' => json_encode($data)
        ];

        return $this->apiRequest('POST', '/expenses', [], $body);
    }

    public function updateExpense($expenseId, array $data)
    {
        $body = [
            'JSONString' => json_encode($data)
        ];
        return $this->apiRequest('PUT', "/expenses/{$expenseId}", [], $body);
    }

    public function deleteExpense(string $expenseId)
    {
        return $this->apiRequest('DELETE', "/expenses/{$expenseId}");
    }

    // ==========================================
    // PAYMENTS
    // ==========================================

    public function getPayments(array $params = [])
    {
        return $this->apiRequest('GET', '/customerpayments', $params);
    }

    public function createPayment(array $data)
    {
        $body = [
            'JSONString' => json_encode($data)
        ];

        return $this->apiRequest('POST', '/customerpayments', [], $body);
    }

    public function deletePayment(string $paymentId)
    {
        return $this->apiRequest('DELETE', "/customerpayments/{$paymentId}");
    }

    // ========================================
    // Contact Methods (Customers/Vendors)
    // ========================================

    /**
     * Create a new contact (customer/vendor)
     */
    public function createContact(array $data)
    {
        $body = [
            'JSONString' => json_encode($data)
        ];

        return $this->apiRequest('POST', '/contacts', [], $body);
    }

    /**
     * Update a contact
     */
    public function updateContact(string $contactId, array $data)
    {
        $body = [
            'JSONString' => json_encode($data)
        ];

        return $this->apiRequest('PUT', "/contacts/{$contactId}", [], $body);
    }

    /**
     * Delete a contact
     */
    public function deleteContact(string $contactId)
    {
        return $this->apiRequest('DELETE', "/contacts/{$contactId}");
    }
}
