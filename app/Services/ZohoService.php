<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Exception;

class ZohoService
{
    protected $clientId;
    protected $clientSecret;
    protected $refreshToken;
    protected $apiDomain;
    protected $orgId;    // optional: orgId header for Desk

    // cache key for token
    protected $cacheKey = 'zoho_access_token';

    public function __construct()
    {
        $this->clientId     =  config('services.zoho.client_id');
        $this->clientSecret =  config('services.zoho.client_secret');
        $this->refreshToken =  config('services.zoho.refresh_token');
        $this->apiDomain    = 'https://desk.zoho.com/';
        $this->orgId        =  config('services.zoho.org_id', null);
    }

    /**
     * Get access token from cache or refresh if expired.
     *
     * @return string
     * @throws Exception
     */
    public function getAccessToken(): string
    {
        // // if cached and not expired, return
        // if (Cache::has($this->cacheKey)) {
        //     return Cache::get($this->cacheKey);
        // }

        // else refresh
        $tokenData = $this->refreshAccessToken();
        dd($tokenData);
        if (!isset($tokenData['access_token'])) {
            throw new Exception('Failed to obtain Zoho access token.');
        }

        // store in cache slightly less than expiry
        $expiresIn = isset($tokenData['expires_in']) ? (int)$tokenData['expires_in'] : 3600;
        $ttl = max(300, $expiresIn - 60); // at least 5 minutes TTL
        Cache::put($this->cacheKey, $tokenData['access_token'], $ttl);
        return $tokenData['access_token'];
    }

    /**
     * Use refresh_token to get new access token.
     * Calls Zoho accounts endpoint.
     *
     * @return array
     * @throws Exception
     */
    public function refreshAccessToken(): array
    {
        $accountsUrl = 'https://accounts.zoho.com/oauth/v2/token';

        $response = Http::asForm()->post($accountsUrl, [
            'refresh_token' => env('ZOHO_REFRESH_TOKEN'),
            'client_id'     => env('ZOHO_CLIENT_ID'),
            'client_secret' => env('ZOHO_CLIENT_SECRET'),
            'grant_type'    => 'refresh_token',
        ]);

        $body = $response->body();

        if (!$response->successful()) {
            throw new \Exception("Failed to obtain Zoho access token. Response: {$body}");
        }

        $json = $response->json();

        if (!isset($json['access_token'])) {
            throw new \Exception("Zoho returned unexpected payload: " . json_encode($json));
        }

        return $json;
    }


    /**
     * Make authenticated API request to Zoho Desk API.
     *
     * @param string $method GET|POST|PUT|DELETE
     * @param string $path API path after /api (e.g. /v1/agents/me)
     * @param array $query
     * @param array|null $body
     * @param array $headers extra headers
     * @return array|object|null
     * @throws Exception
     */
    public function apiRequest(string $method, string $path, array $query = [], $body = null, array $headers = [])
    {
        $accessToken =$this->getAccessToken();

        // Zoho Desk expects Authorization in header: "Zoho-oauthtoken <access_token>"
        $authHeader = 'Zoho-oauthtoken ' . $accessToken;

        $defaultHeaders = [
            'Authorization' => $authHeader,
            'Accept' => 'application/json',
        ];

        if ($this->orgId) {
            $defaultHeaders['orgId'] = $this->orgId;
        }

        $allHeaders = array_merge($defaultHeaders, $headers);

        $url = rtrim($this->apiDomain, '/') . '/api' . $path;

        $client = Http::withHeaders($allHeaders);

        $method = strtolower($method);
        if (!in_array($method, ['get','post','put','delete','patch'])) {
            throw new Exception('Unsupported HTTP method: ' . $method);
        }


        $response = null;
        if ($method === 'get' || $method === 'delete') {
            $response = $client->{$method}($url, $query);
        } else {
            // for post/put/patch include query in url and send body as json
            $response = $client->{$method}($url . ($query ? ('?' . http_build_query($query)) : ''), $body ?? []);
        }

        // if token expired server may return 401 -> try one refresh and retry once
        if ($response->status() === 401) {
            // clear cache and retry once
            Cache::forget($this->cacheKey);
            $accessToken = $this->getAccessToken(); // will refresh
            $allHeaders['Authorization'] = 'Zoho-oauthtoken ' . $accessToken;
            $client = Http::withHeaders($allHeaders);

            if ($method === 'get' || $method === 'delete') {
                $response = $client->{$method}($url, $query);
            } else {
                $response = $client->{$method}($url . ($query ? ('?' . http_build_query($query)) : ''), $body ?? []);
            }
        }

        if (!$response->successful()) {
            // optional: decode json to show message
            $payload = $response->json() ?? $response->body();
            throw new Exception("Zoho API error ({$response->status()}): " . json_encode($payload));
        }

        return $response->json();
    }
    // get Tickets

    public function getTickets()
    {
        return $this->apiRequest('GET', '/v1/tickets');
    }

    public function saveTicket(array $data)
{
    // Validate required fields
    $required = ['subject'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            throw new Exception("Field '{$field}' is required.");
        }
    }

    $contact = $this->createContact([
        'firstName' => $data['firstName'],
        'lastName' => $data['lastName'],
        'email' => $data['email'],
    ]);
    $body = [
        'subject' => $data['subject'],
        'contactId' => $contact['id'],
        'departmentId' => '908816000000006907',
    ];

    return $this->apiRequest('POST', '/v1/tickets', [], $body);
}

public function createContact(array $contactData)
{
    if (empty($contactData['email']) && empty($contactData['lastName'])) {
        throw new Exception('To create a contact you must provide at least email or lastName.');
    }

    $body = [];
    if (!empty($contactData['firstName'])) $body['firstName'] = $contactData['firstName'];
    if (!empty($contactData['lastName']))  $body['lastName']  = $contactData['lastName'];
    if (!empty($contactData['email']))     $body['email']     = $contactData['email'];
    if (!empty($contactData['phone']))     $body['phone']     = $contactData['phone'];

    return $this->apiRequest('POST', '/v1/contacts', [], $body);
}


// billing
public function Invoice()
{
    //https://www.zohoapis.com/books/v3/invoices
    $http = Http::withHeaders([
        'Authorization' => 'Zoho-oauthtoken ' . $this->getAccessToken(),
        'Accept' => 'application/json',
    ])->get('https://www.zohoapis.com/books/v3/invoices');

    return $http->json();
}




    /**
     * Determine accounts base url depending on domain.
     * You may want to support eu/in/com etc. Default to accounts.zoho.com
     *
     * @return string
     */
    protected function getAccountsBaseUrl(): string
    {

        return 'https://accounts.zoho.com';
    }
}
