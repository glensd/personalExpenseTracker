# Personal Expense Tracker

## Overview
A simple yet functional web application for tracking personal expenses. This application allows users to register/login, categorize expenses, and view a summary of their spending over a specified period. It is built using Laravel for the backend and Vue.js for the frontend.


## Table of Contents
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Project Structure](#project-structure)
- [API Documentation](#api-documentation)
- [Security](#security)
- [Credits](#Credits)
- [License](#license)

## Features
### Backend
- **User Authentication**: Implemented using Laravel Breeze. Supports registration, login, logout, and password reset.
- **Database Design**: Includes migrations for users, categories, and expenses tables.
  - **Expenses Table**:
    - user_id (Foreign key to users table)
    - category_id (Foreign key to categories table)
    - amount, description, expense_date
- **CRUD Operations**:
  - Full CRUD operations for managing expenses and categories.
- **Expense Summary**:
    - Summarize expenses by category over a specified period.
- **RESTful APIs**:
    - Endpoints for interacting with the application programmatically.
    - Validation and proper error handling included.

### Frontend
- **UI Framework**: 
  - Built with Vue.js using Laravel Mix for asset compilation.
  - Responsive design for mobile and desktop.
  - Clean and user-friendly interface using Vue.js.
- **Expenses Dashboard**: 
  - Add and view expenses with a form and table.
  - Paginated table view with adjustable rows and scrollbars.
  - Filter expenses by category and date range.
- **Analytics**:
    - Interactive Pie Chart and Bar Graph for expenses by category and month using Chart.js.
    - Summary boxes for:
      - Total Expenses
      - Total Categories
      - Total Amount
      - Average Expense.
- **Integration with Backend**:
    - Axios and Fetch API used for connecting the frontend with Laravel backend.

### Additional Features

- **Validation**:
    - Client-side and server-side validation of user input.
- **Responsive Design**:
    - Fully responsive and mobile-friendly UI.

## Requirements
- PHP 8.0 or higher
- Composer
- Laravel 8 or higher
- Node.js and NPM
- MySQL or any compatible database
- Vue Js
- Modern browser for frontend testing

## Installation
1. Clone the repository:
   ```bash
   git clone <repository-url>
   ```

2. Navigate into the project directory:
    ```bash
   cd <project-directory>
   ```

3. Install dependencies:
    ```bash
    composer install
    ```

4. Set up your .env file:
    ```bash
    cp .env.example .env
    ```

5. Generate an application key:
    ```bash
    php artisan key:generate
    ```

6. Run migrations to set up the database:
    ``` bash 
    php artisan migrate
    ```
7. Seed the database :
    ```bash
    php artisan db:seed
    ```

8. Start Development Server:
    ```bash
    php artisan serve
    ```
9. Build Frontend Assets:
    ```bash
    npm run dev
    ```

## Project Structure
### Directory Layout

```bash
├── app/Http/Controllers
│   ├── API
│   │   ├── CategoryController.php
│   │   ├── ExpenseController.php
│   ├── CategoryController.php
│   ├── ExpenseController.php
├── resources/js/Pages
│   ├── Dashboard.vue
│   ├── Expenses.vue
│   ├── Auth
│   │   ├── Login.vue
│   │   ├── Register.vue
│   │   ├── ForgotPassword.vue
│   │   ├── ResetPassword.vue
│   │   ├── ConfirmPassword.vue
├── routes
│   ├── web.php
│   ├── api.php

```


### Database Credentials
Use the following credentials to connect to the MySQL database:

-   DB_CONNECTION=mysql
-    DB_HOST=127.0.0.1
-    DB_PORT=3306
-    DB_DATABASE=expense_tracker
-    DB_USERNAME=expenseTracker
-    DB_PASSWORD=expenseTracker@123

## API Documentation

### Endpoints

#### Authentication
| Method | Endpoint              | Description               |
|--------|-----------------------|---------------------------|
| POST   | `/api/login`          | User login               |
| POST   | `/api/register`       | User registration        |

#### Categories (CRUD Operations)
| Method | Endpoint               | Description                |
|--------|------------------------|----------------------------|
| GET    | `/api/categories`      | Fetch all categories       |
| POST   | `/api/categories`      | Create a new category      |
| PUT    | `/api/categories/{id}` | Update a specific category |
| DELETE | `/api/categories/{id}` | Delete a specific category |

#### Expenses (CRUD Operations)
| Method | Endpoint               | Description                |
|--------|------------------------|----------------------------|
| GET    | `/api/expenses`        | Fetch all expenses         |
| POST   | `/api/expenses`        | Create a new expense       |
| PUT    | `/api/expenses/{id}`   | Update a specific expense  |
| DELETE | `/api/expenses/{id}`   | Delete a specific expense  |

#### Summary
| Method | Endpoint                   | Description                      |
|--------|----------------------------|----------------------------------|
| POST   | `/api/expenses/summary`    | Summary by category and date    |

## Example Request: Adding Expense
``` bash
POST /api/expenses
Content-Type: application/json
Authorization: Bearer <auth_token>

{
  "category_id": 1,
  "amount": 100.50,
  "description": "Grocery shopping",
  "expense_date": "2024-11-29"
}
```

## Example Response
``` bash
{
  "status": true,
  "message": "Expense added successfully.",
  "data": {
    "id": 10,
    "category_id": 1,
    "amount": "100.50",
    "description": "Grocery shopping",
    "expense_date": "2024-11-29"
  }
}
   
```
## Security
- CSRF protection implemented using Laravel middleware.
- JWT tokens or Sanctum for API authentication.
- Validation implemented at both client-side and server-side.
## Credits
- Laravel: Backend framework.
- Vue.js: Frontend framework.
- Chart.js: For creating charts.
## License
This project is licensed under the MIT License.






