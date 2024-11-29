# Personal Expense Tracker

## Overview
A simple yet functional web application for tracking personal expenses. This application allows users to register/login, categorize expenses, and view a summary of their spending over a specified period. It is built using Laravel for the backend and Vue.js for the frontend.

## Table of Contents
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Project Structure](#project-structure)
- [API Documentation](#api-documentation)
- [Api and Frontend Screenshot](#api-and-frontend-screenshot)
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
│   ├── Auth
│   │   ├── AuthenticatedSessionController.php
│   │   ├── RegisteredUserController.php
│   │   ├── PasswordController.php
│   │   ├── ConfirmablePasswordController.php
│   │   ├── PasswordResetLinkController.php
│   │   ├── NewPasswordController.php
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

## Api and Frontend Screenshot
Below is a sample screenshot of the api and frontend, illustrating the API endpoints and request/response structures. Note that only a few representative screenshots are attached.
### Frontend Screenshots
<div>
<img width="150" alt="Screenshot 2024-11-29 at 10 46 05 AM" src="https://github.com/user-attachments/assets/f7f4f84d-60f6-45ad-9ff7-9cfe6f62317f">
<img width="150" alt="Screenshot 2024-11-29 at 10 23 39 AM" src="https://github.com/user-attachments/assets/dfac7328-4a75-4e93-b58b-a32e1b326823">
<img width="150" alt="Screenshot 2024-11-29 at 10 23 04 AM" src="https://github.com/user-attachments/assets/da7aa63a-6ef8-4e7b-8122-c9e45e7e8ae0">
<img width="150" alt="Screenshot 2024-11-29 at 11 00 43 AM" src="https://github.com/user-attachments/assets/be6a7344-0565-4d0a-9798-aac15dc51c94">
<img width="150" alt="Screenshot 2024-11-29 at 11 00 35 AM" src="https://github.com/user-attachments/assets/e345824c-5747-4332-9634-ca26200600eb">
<img width="150" alt="Screenshot 2024-11-29 at 11 00 19 AM" src="https://github.com/user-attachments/assets/b9659a66-b532-4109-ad72-09fef5287613">
<img width="150" alt="Screenshot 2024-11-29 at 11 00 13 AM" src="https://github.com/user-attachments/assets/2a711c71-f528-4874-99fb-e955ed62d477">
<img width="150" alt="Screenshot 2024-11-29 at 10 56 18 AM" src="https://github.com/user-attachments/assets/2225a10a-ea3a-4cfb-83de-c2915813a853">
<img width="150" alt="Screenshot 2024-11-29 at 10 57 17 AM" src="https://github.com/user-attachments/assets/570e8d72-6ef1-4db7-98b4-aaf53a090365">
<img width="150" alt="Screenshot 2024-11-29 at 10 56 34 AM" src="https://github.com/user-attachments/assets/273628b6-530a-4b5c-b061-511233ed61e2">
<img width="150" alt="Screenshot 2024-11-29 at 10 56 46 AM" src="https://github.com/user-attachments/assets/5ef2f11a-0dca-49e9-9887-c68bdb525e1e">
<img width="150" alt="Screenshot 2024-11-29 at 10 57 10 AM" src="https://github.com/user-attachments/assets/c03fe4f1-3c02-4daf-b007-d4ba95678f69">   
<img width="150" alt="Screenshot 2024-11-29 at 10 58 08 AM" src="https://github.com/user-attachments/assets/501c9232-8f03-46af-8774-a8b31451004c">
<img width="150" alt="Screenshot 2024-11-29 at 10 57 56 AM" src="https://github.com/user-attachments/assets/9c1f1577-cdca-4476-9f4a-4d4a68c4496f">
<img width="150" alt="Screenshot 2024-11-29 at 10 57 40 AM" src="https://github.com/user-attachments/assets/5bb884e9-4acc-4f0e-ac9e-934a1e5cdb3b">
<img width="150" alt="Screenshot 2024-11-29 at 10 57 33 AM" src="https://github.com/user-attachments/assets/f261326f-c6d1-4fbb-92c6-3a0259931944">
<img width="150" alt="Screenshot 2024-11-29 at 10 57 27 AM" src="https://github.com/user-attachments/assets/6a05654d-cc62-4c83-bf32-3a970fb8fe40">
<img width="150" alt="Screenshot 2024-11-29 at 11 00 57 AM" src="https://github.com/user-attachments/assets/721df8c3-b14b-4872-8ab5-634a9f4564bb">

</div>

### API Screenshots
<div>
<img width="150" alt="Screenshot 2024-11-29 at 8 56 57 AM" src="https://github.com/user-attachments/assets/0677821f-98a1-417f-b450-a52175090741">
<img width="150" alt="Screenshot 2024-11-29 at 8 59 31 AM" src="https://github.com/user-attachments/assets/005bd16b-2a84-4bca-86ac-9284774be19f">
<img width="150" alt="Screenshot 2024-11-29 at 8 59 12 AM" src="https://github.com/user-attachments/assets/87fcba59-9095-4607-8e87-bcb4963cd489">
<img width="150" alt="Screenshot 2024-11-29 at 8 58 30 AM" src="https://github.com/user-attachments/assets/8e7bb60e-de44-4494-8ca9-87a964fe6a9a">
<img width="150" alt="Screenshot 2024-11-29 at 9 03 46 AM" src="https://github.com/user-attachments/assets/f0fbdf4d-6208-4af8-81b9-634b21d5e120">
<img width="150" alt="Screenshot 2024-11-29 at 9 03 16 AM" src="https://github.com/user-attachments/assets/37d279bf-4597-4e33-b787-a03267367723">
<img width="150" alt="Screenshot 2024-11-29 at 9 00 48 AM" src="https://github.com/user-attachments/assets/913bee9e-bacd-4da5-8649-490b79838501">
<img width="150" alt="Screenshot 2024-11-29 at 9 00 35 AM" src="https://github.com/user-attachments/assets/098b5c1f-7e7c-4ff3-921d-306b24c49308">
<img width="150" alt="Screenshot 2024-11-29 at 8 59 50 AM" src="https://github.com/user-attachments/assets/e36c2015-9727-4624-b28e-cfbc6ebebef5">
<img width="150" alt="Screenshot 2024-11-29 at 9 05 35 AM" src="https://github.com/user-attachments/assets/16aca60e-9df5-4ff6-8c8f-71b87f13c842">
<img width="150" alt="Screenshot 2024-11-29 at 9 05 34 AM" src="https://github.com/user-attachments/assets/1595cff3-1041-4b96-9cce-530ff870abe1">
<img width="150" alt="Screenshot 2024-11-29 at 9 04 55 AM" src="https://github.com/user-attachments/assets/1052c1a8-f21e-4b2f-8c6c-5080d43df07e">
<img width="150" alt="Screenshot 2024-11-29 at 9 04 10 AM" src="https://github.com/user-attachments/assets/03076806-032b-426d-8f07-2472db602089">

</div>
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






