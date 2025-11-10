# Invoice Management System - Setup Guide

## Overview
This Laravel application integrates with Zoho Books API to provide a comprehensive invoice management system. The system allows you to view, create, edit, send, and manage invoices directly from your Laravel dashboard.

## Features Implemented

### 1. Invoice Index Page
- **Location**: `resources/views/dashboard/invoice/index.blade.php`
- **Route**: `/invoices` (GET)
- **Controller**: `InvoiceController@index`

**Features:**
- Display all invoices from Zoho Books API
- Search functionality
- Status filtering (Draft, Sent, Paid, Overdue, Void)
- Responsive table with invoice details
- Action menu for each invoice (View, Edit, Send, Mark as Sent, Void, Delete)
- Real-time AJAX operations
- Multi-language support (English/Arabic)

### 2. Invoice Controller
- **Location**: `app/Http/Controllers/InvoiceController.php`
- **Methods**:
  - `index()` - List all invoices
  - `create()` - Show create form
  - `store()` - Create new invoice
  - `show()` - View invoice details
  - `edit()` - Show edit form
  - `update()` - Update invoice
  - `send()` - Send invoice via email
  - `markAsSent()` - Mark invoice as sent
  - `void()` - Void invoice
  - `destroy()` - Delete invoice

### 3. Zoho Books Integration
- **Service**: `app/Services/ZohoBooksService.php`
- **API Endpoints**: All major Zoho Books API endpoints implemented
- **Authentication**: OAuth 2.0 with refresh token
- **Caching**: Access token caching for performance

### 4. Routes
- **File**: `routes/web.php`
- **Prefix**: `/invoices`
- **Middleware**: `auth`
- **RESTful**: Full CRUD operations + custom actions

### 5. Translations
- **English**: `lang/en/dashboard.php`
- **Arabic**: `lang/ar/dashboard.php`
- **Coverage**: All invoice-related terms and messages

## Configuration Required

### 1. Environment Variables
Add these variables to your `.env` file:

```env
# Zoho API Configuration
ZOHO_CLIENT_ID=your_client_id_here
ZOHO_CLIENT_SECRET=your_client_secret_here
ZOHO_REFRESH_TOKEN=your_refresh_token_here
ZOHO_BOOKS_ORG_ID=your_organization_id_here
ZOHO_BOOKS_API_DOMAIN=https://www.zohoapis.com/books/v3
ZOHO_ORG_ID=your_org_id_for_desk_api
```

### 2. Zoho Books API Setup
1. Go to [Zoho Developer Console](https://api-console.zoho.com/)
2. Create a new application
3. Generate Client ID and Client Secret
4. Set up OAuth 2.0 and get refresh token
5. Get your Organization ID from Zoho Books

### 3. Service Configuration
The Zoho configuration is already added to `config/services.php`:

```php
'zoho' => [
    'client_id' => env('ZOHO_CLIENT_ID'),
    'client_secret' => env('ZOHO_CLIENT_SECRET'),
    'refresh_token' => env('ZOHO_REFRESH_TOKEN'),
    'books_org_id' => env('ZOHO_BOOKS_ORG_ID'),
    'books_api_domain' => env('ZOHO_BOOKS_API_DOMAIN', 'https://www.zohoapis.com/books/v3'),
    'org_id' => env('ZOHO_ORG_ID'),
],
```

## File Structure

```
app/
├── Http/Controllers/
│   └── InvoiceController.php          # Main invoice controller
├── Services/
│   └── ZohoBooksService.php           # Zoho Books API service
config/
└── services.php                       # Service configurations
lang/
├── en/dashboard.php                   # English translations
└── ar/dashboard.php                   # Arabic translations
resources/views/dashboard/
└── invoice/
    └── index.blade.php                # Invoice listing page
routes/
└── web.php                           # Web routes
```

## API Integration Details

### Zoho Books API Endpoints Used
- `GET /invoices` - List invoices
- `GET /invoices/{id}` - Get invoice details
- `POST /invoices` - Create invoice
- `PUT /invoices/{id}` - Update invoice
- `DELETE /invoices/{id}` - Delete invoice
- `POST /invoices/{id}/email` - Send invoice
- `POST /invoices/{id}/status/sent` - Mark as sent
- `POST /invoices/{id}/status/void` - Void invoice
- `GET /contacts` - Get customers
- `GET /items` - Get items/products

### Data Structure
The system handles Zoho Books invoice data structure including:
- Invoice ID and number
- Customer information
- Line items
- Dates (created, due)
- Status (draft, sent, paid, overdue, void)
- Amounts and currency
- Custom fields

## Usage

### Accessing Invoice Management
1. Navigate to `/invoices` in your browser
2. You'll see the invoice listing page with all invoices from Zoho Books
3. Use the search bar to find specific invoices
4. Filter by status using the dropdown
5. Click on action menu (⋮) for each invoice to perform operations

### Available Actions
- **View**: See invoice details
- **Edit**: Modify invoice (only for draft invoices)
- **Send**: Email invoice to customer
- **Mark as Sent**: Update status to sent
- **Void**: Cancel invoice
- **Delete**: Remove invoice (only for draft invoices)

## Next Steps

### Recommended Enhancements
1. **Create Invoice Views**: Implement create, show, and edit views
2. **PDF Generation**: Add PDF export functionality
3. **Email Templates**: Customize email templates for sending invoices
4. **Payment Integration**: Add payment processing
5. **Reporting**: Create invoice reports and analytics
6. **Bulk Operations**: Add bulk actions for multiple invoices
7. **Notifications**: Add real-time notifications for invoice updates

### Testing
1. Ensure Zoho Books API credentials are properly configured
2. Test all CRUD operations
3. Verify AJAX functionality works correctly
4. Test multi-language support
5. Check responsive design on different devices

## Troubleshooting

### Common Issues
1. **API Authentication Errors**: Check your Zoho credentials and refresh token
2. **Empty Invoice List**: Verify organization ID and API permissions
3. **AJAX Errors**: Check CSRF token and route definitions
4. **Translation Issues**: Ensure language files are properly loaded

### Debug Mode
Enable Laravel debug mode to see detailed error messages:
```env
APP_DEBUG=true
```

## Support
For issues related to:
- **Zoho Books API**: Check [Zoho Books API Documentation](https://www.zoho.com/books/api/v3/)
- **Laravel**: Refer to [Laravel Documentation](https://laravel.com/docs)
- **This Implementation**: Review the code comments and structure
