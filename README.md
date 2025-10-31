# Invoice App API

A lightweight **stateless REST API** built with **Laravel** for managing invoices and their related items.  
This project is structured as a clean, backend-only API service — no frontend, no authentication.

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [API Overview](#api-overview)
- [Invoice API Reference](#api-overview)
- [Testing the API](#invoice-api-reference)
- [Deployment]()

---

## Features

- RESTful endpoints for managing **Invoices** and **Invoice Items**
- JSON-based stateless API responses
- Basic CRUD operations:
    - CREATE, READ, UPDATE, and DELETE invoices, along with their invoice items
- Seeder support for demo data

---

## Tech Stack

**Framework:** [Laravel](https://laravel.com/) 12.0  
**Language:** PHP ≥ 8.1  
**Database:** MySQL / MariaDB (or any supported by Laravel’s query builder)

### Common Laravel Packages Used

- `laravel/framework` — Core Laravel
- `fakerphp/faker` — Data seeding
- `laravel/tinker` — Artisan REPL

---

## Installation

```bash
# 1. Clone the repository
git clone https://github.com/DimitarSamarov07/invoice-app.git
cd invoice-app

# 2. Install PHP dependencies
composer install

# 3. Copy the example environment file
cp .env.example .env

# 5. Configure your .env file (see next section)
```

---

## Configuration

Edit your `.env` file to match your local database setup:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=invoice_app
DB_USERNAME=root
DB_PASSWORD=secret
```

Make sure your database exists and credentials are valid.

---

## Database Setup

Run the migrations:

```bash
php artisan migrate
```

(Optional) Populate the database with sample data:

```bash
php artisan db:seed
```

Or combine both:

```bash
php artisan migrate --seed
```

If you ever need to reset the database:

```bash
php artisan migrate:fresh --seed
```

---

## API Overview

All endpoints return **JSON** and are prefixed (e.g. `/api/invoices`, `/api/invoice-items`).

### Endpoints

#### Invoices

| Method   | Endpoint             | Description                                              |
|----------|----------------------|----------------------------------------------------------|
| `GET`    | `/api/invoices`      | List all invoices                                        |
| `GET`    | `/api/invoices/{id}` | Retrieve invoice by ID                                   |
| `POST`   | `/api/invoices`      | Create a new invoice                                     |
| `PUT`    | `/api/invoices/{id}` | Update invoice details(replacing values)                 |
| `PATCH`  | `/api/invoices/{id}` | Update invoice details(only 'patch' the existing object) |
| `DELETE` | `/api/invoices/{id}` | Delete invoice(soft delete)                              |

# Invoice API Reference

## Base URL

```
http://localhost:8000/api
```

## Response Format

All responses are returned in JSON format with appropriate HTTP status codes.

## Headers

### Required Headers

```
Content-Type: application/json
```

---

## Endpoints

### 1. List Invoices

Retrieve a paginated list of all invoices with optional filtering and sorting.

**Endpoint:** `GET /invoices`

**Query Parameters:**

| Parameter | Type    | Required | Description                            |
|-----------|---------|----------|----------------------------------------|
| `status`  | string  | No       | Filter by invoice status               |
| `search`  | string  | No       | Filter by customer name                |
| `page`    | integer | No       | Get the Nth page of the paginated data |

**Example Request:**

```bash
curl -X GET "http://localhost:8000/api/invoices"
```

**Success Response:** `200 OK`

```json
{
    "current_page": 1,
    "data": [
        {
            "id": 2,
            "number": "INVOICE-2317794461",
            "customer_name": "Erdman-Kub",
            "customer_email": "billing@websolutions.com",
            "date": "2025-10-04",
            "due_date": "2025-11-03",
            "subtotal": "1050.00",
            "vat": "210.00",
            "total": "1260.00",
            "status": "unpaid",
            "created_at": "2025-10-31T10:18:24.000000Z",
            "updated_at": "2025-10-31T13:16:11.000000Z",
            "deleted_at": null,
            "items": [
                {
                    "id": 8,
                    "invoice_id": 2,
                    "description": "Porro alias facilis et.",
                    "quantity": 2,
                    "unit_price": "375.00",
                    "total": "750.00",
                    "created_at": "2025-10-31T10:18:24.000000Z",
                    "updated_at": "2025-10-31T13:14:48.000000Z"
                },
                {
                    "id": 88,
                    "invoice_id": 2,
                    "description": "New Monthly Support Retainer",
                    "quantity": 1,
                    "unit_price": "150.00",
                    "total": "150.00",
                    "created_at": "2025-10-31T13:16:11.000000Z",
                    "updated_at": "2025-10-31T13:16:11.000000Z"
                },
                {
                    "id": 89,
                    "invoice_id": 2,
                    "description": "New Monthly Support Retainer",
                    "quantity": 1,
                    "unit_price": "150.00",
                    "total": "150.00",
                    "created_at": "2025-10-31T13:16:11.000000Z",
                    "updated_at": "2025-10-31T13:16:11.000000Z"
                }
            ]
        },
             
        ......................
        
        {
            "id": 16,
            "number": "INVOICE-5991824506",
            "customer_name": "Bauch, Wuckert and Murphy",
            "customer_email": "arnaldo.bergstrom@hotmail.com",
            "date": "2025-10-12",
            "due_date": "2025-11-28",
            "subtotal": "6395.02",
            "vat": "1279.00",
            "total": "7674.02",
            "status": "draft",
            "created_at": "2025-10-31T10:18:24.000000Z",
            "updated_at": "2025-10-31T10:18:24.000000Z",
            "deleted_at": null,
            "items": [
                {
                    "id": 62,
                    "invoice_id": 16,
                    "description": "Eos et neque nulla aspernatur perferendis sit qui.",
                    "quantity": 1,
                    "unit_price": "220.39",
                    "total": "220.39",
                    "created_at": "2025-10-31T10:18:24.000000Z",
                    "updated_at": "2025-10-31T10:18:24.000000Z"
                },
                {
                    "id": 63,
                    "invoice_id": 16,
                    "description": "Ut et dolor voluptatem.",
                    "quantity": 5,
                    "unit_price": "485.14",
                    "total": "2425.70",
                    "created_at": "2025-10-31T10:18:24.000000Z",
                    "updated_at": "2025-10-31T10:18:24.000000Z"
                },
                {
                    "id": 64,
                    "invoice_id": 16,
                    "description": "Fuga temporibus impedit corrupti.",
                    "quantity": 1,
                    "unit_price": "361.16",
                    "total": "361.16",
                    "created_at": "2025-10-31T10:18:24.000000Z",
                    "updated_at": "2025-10-31T10:18:24.000000Z"
                },
                {
                    "id": 65,
                    "invoice_id": 16,
                    "description": "Aut dolor rerum rerum.",
                    "quantity": 3,
                    "unit_price": "435.23",
                    "total": "1305.69",
                    "created_at": "2025-10-31T10:18:24.000000Z",
                    "updated_at": "2025-10-31T10:18:24.000000Z"
                },
                {
                    "id": 66,
                    "invoice_id": 16,
                    "description": "Voluptate voluptas facere iure illum odio dolorem.",
                    "quantity": 7,
                    "unit_price": "297.44",
                    "total": "2082.08",
                    "created_at": "2025-10-31T10:18:24.000000Z",
                    "updated_at": "2025-10-31T10:18:24.000000Z"
                }
            ]
        }
    ],
    "first_page_url": "http://127.0.0.1:8000/api/invoices?page=1",
    "from": 1,
    "last_page": 2,
    "last_page_url": "http://127.0.0.1:8000/api/invoices?page=2",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "page": null,
            "active": false
        },
        {
            "url": "http://127.0.0.1:8000/api/invoices?page=1",
            "label": "1",
            "page": 1,
            "active": true
        },
        {
            "url": "http://127.0.0.1:8000/api/invoices?page=2",
            "label": "2",
            "page": 2,
            "active": false
        },
        {
            "url": "http://127.0.0.1:8000/api/invoices?page=2",
            "label": "Next &raquo;",
            "page": 2,
            "active": false
        }
    ],
    "next_page_url": "http://127.0.0.1:8000/api/invoices?page=2",
    "path": "http://127.0.0.1:8000/api/invoices",
    "per_page": 15,
    "prev_page_url": null,
    "to": 15,
    "total": 24
}
```

---

### 2. Get Single Invoice

Retrieve a specific invoice by ID with all its items.

**Endpoint:** `GET /invoices/{id}`

**URL Parameters:**

| Parameter | Type    | Required | Description |
|-----------|---------|----------|-------------|
| `id`      | integer | Yes      | Invoice ID  |

**Example Request:**

```bash
curl -X GET "http://localhost:8000/api/invoices/1" \
  -H "Accept: application/json"
```

**Success Response:** `200 OK`

```json
{
    "id": 1,
    "number": "INV-00001",
    "customer_name": "John Doe",
    "customer_email": "john@example.com",
    "date": "2025-10-15",
    "due_date": "2025-11-15",
    "subtotal": "1500.00",
    "vat": "300",
    "total": "1800.00",
    "status": "paid",
    "created_at": "2025-10-15T10:00:00.000000Z",
    "updated_at": "2025-10-20T14:30:00.000000Z",
    "items": [
        {
            "id": 1,
            "invoice_id": 1,
            "description": "Web Development Services",
            "quantity": 10,
            "unit_price": "150.00",
            "total": "1500.00",
            "created_at": "2025-10-15T10:00:00.000000Z",
            "updated_at": "2025-10-15T10:00:00.000000Z"
        }
    ]
}
```

**Error Response:** `404 Not Found`

```json
{
    "message": "Invoice not found"
}
```

---

### 3. Create Invoice

Create a new invoice with line items.

**Endpoint:** `POST /invoices`

**Request Body:**

| Field                 | Type    | Required | Description                                                |
|-----------------------|---------|----------|------------------------------------------------------------|
| `customer_name`       | string  | Yes      | Customer's full name (max: 255 chars)                      |
| `customer_email`      | string  | Yes      | Customer's email address (valid email format)              |
| `date`                | date    | Yes      | Invoice date (YYYY-MM-DD format)                           |
| `due_date`            | date    | Yes      | Payment due date (YYYY-MM-DD format, must be after 'date') |
| `status`              | string  | No       | Invoice status (default: "draft")                          |
| `items`               | array   | Yes      | Array of invoice items (at least 1 item required)          |
| `items.*.description` | string  | Yes      | Item description (max: 500 chars)                          |
| `items.*.quantity`    | integer | Yes      | Item quantity (min: 1)                                     |
| `items.*.unit_price`  | number  | Yes      | Price per unit (min: 0)                                    |

**Example Request:**

```bash
curl -X POST "http://localhost:8000/api/invoices" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "number": "INV-001234",
    "customer_name": "Jane Smith",
    "customer_email": "jane@example.com",
    "date": "2025-10-31",
    "due_date": "2025-11-30",
    "status": "draft",
    "items": [
      {
        "description": "Logo Design",
        "quantity": 1,
        "unit_price": 500.00
      },
      {
        "description": "Business Cards",
        "quantity": 500,
        "unit_price": 0.50
      }
    ]
}'
```

**Success Response:** `201 Created`

```json
{
    "id": 26,
    "number": "INV-001234",
    "customer_name": "Jane Smith",
    "customer_email": "jane@example.com",
    "date": "2025-10-31",
    "due_date": "2025-11-30",
    "status": "draft",
    "subtotal": 750,
    "vat": 150,
    "updated_at": "2025-10-31T15:00:11.000000Z",
    "created_at": "2025-10-31T15:00:11.000000Z",
    "items": [
        {
            "id": 96,
            "invoice_id": 26,
            "description": "Logo Design",
            "quantity": 1,
            "unit_price": "500.00",
            "total": "500.00",
            "created_at": "2025-10-31T15:00:11.000000Z",
            "updated_at": "2025-10-31T15:00:11.000000Z"
        },
        {
            "id": 97,
            "invoice_id": 26,
            "description": "Business Cards",
            "quantity": 500,
            "unit_price": "0.50",
            "total": "250.00",
            "created_at": "2025-10-31T15:00:11.000000Z",
            "updated_at": "2025-10-31T15:00:11.000000Z"
        }
    ]
}
```

**Error Response:** `422 Unprocessable Entity`

```json
{
    "message": "The customer email field is required. (and 2 more errors)",
    "errors": {
        "customer_email": [
            "The customer email field is required."
        ],
        "date": [
            "The invoice date field is required."
        ],
        "items": [
            "The items field is required."
        ]
    }
}
```

**Error Response:** `500 Internal Server Error`

```json
{
    "message": "Error creating invoice"
}
```

---

### 4. Update Invoice (Full Update)

Replace all fields of an existing invoice using PUT method.

**Endpoint:** `PUT /invoices/{id}`

**URL Parameters:**

| Parameter | Type    | Required | Description |
|-----------|---------|----------|-------------|
| `id`      | integer | Yes      | Invoice ID  |

**Request Body:** Same as Create Invoice (all fields required)

**Example Request:**

```bash
curl -X PUT "http://localhost:8000/api/invoices/2" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "number": "INV-001234",
    "customer_name": "Jane Smith Updated",
    "customer_email": "jane.smith@example.com",
    "date": "2025-10-31",
    "due_date": "2025-12-15",
    "status": "unpaid",
    "items": [
      {
        "description": "Logo Design - Revised",
        "quantity": 1,
        "unit_price": 600.00
      }
    ]
  }'
```

**Success Response:** `200 OK`

```json
{
    "id": 2,
    "number": "INV-001234",
    "customer_name": "Jane Smith Updated",
    "customer_email": "jane.smith@example.com",
    "date": "2025-10-31",
    "due_date": "2025-12-15",
    "subtotal": "600.00",
    "vat": "120.00",
    "total": "720.00",
    "status": "unpaid",
    "created_at": "2025-10-31T15:45:00.000000Z",
    "updated_at": "2025-10-31T16:00:00.000000Z",
    "items": [
        {
            "id": 4,
            "invoice_id": 2,
            "description": "Logo Design - Revised",
            "quantity": 1,
            "unit_price": "600.00",
            "total": "600.00",
            "created_at": "2025-10-31T16:00:00.000000Z",
            "updated_at": "2025-10-31T16:00:00.000000Z"
        }
    ]
}
```

---

### 5. Update Invoice (Partial Update)

Update specific fields of an existing invoice using PATCH method.

**Endpoint:** `PATCH /invoices/{id}`

**URL Parameters:**

| Parameter | Type    | Required | Description |
|-----------|---------|----------|-------------|
| `id`      | integer | Yes      | Invoice ID  |

**Request Body:** Any subset of invoice fields (all fields optional), including the items array.

| Field            | Type   | Required | Description              |
|------------------|--------|----------|--------------------------|
| `customer_name`  | string | No       | Customer's full name     |
| `customer_email` | string | No       | Customer's email address |
| `date`           | date   | No       | Invoice date             |
| `due_date`       | date   | No       | Payment due date         |
| `status`         | string | No       | Invoice status           |
| `items`          | array  | No       | Array of invoice items.  |

**Notes**
If you want to modify an existing item, you need to provide the item ID in the `items` array and only the value pairs
you wish to change.
For those you wish to newly create, you just need to provide the item description, quantity and price.
See the example below.
The total, subtotal and VAT are automatically recalculated based on the updated items.

**Example Request:**

```bash
curl -X PATCH "http://localhost:8000/api/invoices/2" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "status": "paid",
    "items":[
    {
      "id": 4,
      "description": "Logo Design - Revised",
      "quantity": 1,
      "unit_price": 600.00
    },
    {
      "description": "Business Cards",
      "quantity": 500,
      "unit_price": 0.50
    }
    ]
  }'
```

**Success Response:** `200 OK`

```json
{
    "id": 2,
    "number": "INV-001234",
    "customer_name": "Jane Smith Updated",
    "customer_email": "jane.smith@example.com",
    "date": "2025-10-31",
    "due_date": "2025-12-15",
    "subtotal": "850.00",
    "vat": "170.00",
    "total": "1020.00",
    "status": "paid",
    "created_at": "2025-10-31T15:45:00.000000Z",
    "updated_at": "2025-10-31T16:15:00.000000Z",
    "items": [
        {
            "id": 4,
            "invoice_id": 2,
            "description": "Logo Design - Revised",
            "quantity": 1,
            "unit_price": "600.00",
            "total": "600.00",
            "created_at": "2025-10-31T13:00:00.000000Z",
            "updated_at": "2025-10-31T16:00:00.000000Z"
        },
        {
            "id": 10,
            "invoice_id": 2,
            "description": "Business Cards",
            "quantity": 500,
            "unit_price": "0.5",
            "total": "250",
            "created_at": "2025-10-31T16:00:00.000000Z",
            "updated_at": "2025-10-31T16:00:00.000000Z"
        }
    ]
}
```

---

### 6. Delete Invoice

Delete an existing invoice and all its associated items. This action is implemented as a soft delete, meaning the
invoice is marked as deleted but not actually deleted from the database.

**Endpoint:** `DELETE /invoices/{id}`

**URL Parameters:**

| Parameter | Type    | Required | Description |
|-----------|---------|----------|-------------|
| `id`      | integer | Yes      | Invoice ID  |

**Example Request:**

```bash
curl -X DELETE "http://localhost:8000/api/invoices/2" \
  -H "Accept: application/json"
```

**Success Response:** `204 No content`

**Error Response:** `404 Not Found`

```json
{
    "message": "Invoice not found"
}
```

---

## Data Models

### Invoice Object

| Field            | Type      | Description                                     |
|------------------|-----------|-------------------------------------------------|
| `id`             | integer   | Unique invoice identifier                       |
| `number`         | string    | Unique invoice number. Indexed in the database. |
| `customer_name`  | string    | Customer's full name                            |
| `customer_email` | string    | Customer's email address                        |
| `date`           | date      | Date the invoice was issued (YYYY-MM-DD)        |
| `due_date`       | date      | Payment due date (YYYY-MM-DD)                   |
| `subtotal`       | decimal   | Total base amount, calculated from items        |
| `vat`            | decimal   | VAT, configured to 20%                          |
| `total`          | decimal   | Total invoice amount (subtotal + vat)           |
| `status`         | string    | Invoice status (draft, unpaid, paid)            |
| `created_at`     | timestamp | Invoice creation timestamp                      |
| `updated_at`     | timestamp | Last update timestamp                           |
| `items`          | array     | Array of invoice items                          |

### Invoice Item Object

| Field         | Type      | Description                                     |
|---------------|-----------|-------------------------------------------------|
| `id`          | integer,  | Unique item identifier                          |
| `invoice_id`  | integer   | Foreign key for the invoice the item belongs to |
| `description` | string    | Item description                                |
| `quantity`    | decimal   | Item quantity                                   |
| `unit_price`  | decimal   | Price per unit                                  |
| `total`       | decimal   | Calculated total (quantity × unit_price)        |
| `created_at`  | timestamp | Item creation timestamp                         |
| `updated_at`  | timestamp | Last update timestamp                           |

---

## Invoice Status Values

| Status   | Description                       |
|----------|-----------------------------------|
| `draft`  | Invoice is being prepared         |
| `unpaid` | Invoice has been sent to customer |
| `paid`   | Invoice has been paid             |

---

## HTTP Status Codes

| Code  | Description                                           |
|-------|-------------------------------------------------------|
| `200` | OK - Request successful                               |
| `201` | Created - Resource created successfully               |
| `204` | No Content - Request successful with no response body |
| `400` | Bad Request - Invalid request format                  |
| `404` | Not Found - Resource not found                        |
| `422` | Unprocessable Entity - Validation failed              |
| `500` | Internal Server Error - Server error                  |

---

## Error Response Format

All error responses linked with validation follow this structure:

```json
{
    "message": "Error message summary",
    "errors": {
        "field_name": [
            "Detailed error message for this field"
        ]
    }
}
```

### Common Validation Errors

**Missing Required Fields:**

```json
{
    "message": "The customer name field is required. (and 1 more error)",
    "errors": {
        "customer_name": [
            "The customer name field is required."
        ],
        "customer_email": [
            "The customer email field is required."
        ]
    }
}
```

**Invalid Data Format:**

```json
{
    "message": "The customer email field must be a valid email address.",
    "errors": {
        "customer_email": [
            "The customer email field must be a valid email address."
        ]
    }
}
```

**Business Logic Violations:**

```json
{
    "message": "The due date field must be a date after invoice date.",
    "errors": {
        "due_date": [
            "The due date field must be a date after invoice date."
        ]
    }
}
```

---

## Rate Limiting
Unlimited requests per minute.


## Pagination

Only the GET /invoice endpoint returns paginated results. The page size is 15.

---

## Notes

1. **Total Calculation**: The `total` field is automatically calculated from invoice items
2. **Item Totals**: Each item's `total` is calculated as `quantity × unit_price`
3. **Cascade Delete**: Deleting an invoice will automatically delete all associated items
4. **Timestamps**: All timestamps are in UTC and formatted as ISO 8601
5. **Decimal Precision**: Monetary values are stored with 2 decimal places
6. **Date Format**: All dates use the `YYYY-MM-DD` format (e.g., 2025-10-31)

---

## Testing the API

### Using cURL

All examples in this documentation use cURL. Make sure to include the proper headers and format JSON correctly.

### Using Postman
A Postman collection is available in the `postman` folder.


## Deployment
The application is deployed on Railway. You can find the link to the app [here](https://invoice-app.railway.app/).
