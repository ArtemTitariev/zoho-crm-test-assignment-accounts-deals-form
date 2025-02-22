# Zoho CRM Deals and Accounts Form

A web form built with **Vue.js** and **Laravel** that allows users to create deals and accounts in Zoho CRM using the Zoho CRM API. The form contains all the necessary fields to create these records.

Deals and accounts are automatically linked. An automatic token refresh mechanism is implemented to allow records to be created from the form at any time.

### Features:

- Validation of all form fields to ensure that valid values are entered
- Display of error messages if an invalid value is entered
- Display of a success message if the records are successfully created
- Display of an error message if the records fail to be created
- Use of Vue.js to create a dynamic and responsive user interface
- A submit button to create the deal and account records in Zoho CRM

### The backend includes functions:

- Integration with the Zoho CRM API for creating deals and accounts
- Implementation of the automatic token refresh mechanism
- Creation of the necessary routes and controllers to handle form submissions
- Validation of incoming requests

## Installation

#### 1. Clone the repository

```sh
git clone https://github.com/ArtemTitariev/zoho-crm-test-assignment-accounts-deals-form.git
```

### Backend setup:

#### 2. Install dependencies, setup project:

```sh
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

#### 3. Register for a [Zoho CRM Account](https://www.zoho.com/crm/signup.html).

#### 4. [Register new application](https://www.zoho.com/crm/developer/docs/api/register-client.html) with Zoho CRM.

#### 5. Configure your Zoho CRM credentials in **.env**:

```
 ZOHO_CLIENT_ID=[YOUR_ZOHO_CLIENT_ID]
 ZOHO_CLIENT_SECRET=[YOUR_ZOHO_CLIENT_SECRET]
 ZOHO_CODE=[YOUR_ZOHO_CODE]
```

#### 6. Start development server:

```sh
 php artisan serve
```

### Frontend setup:

#### 7. Install dependencies, compile project:

```sh
cd frontend
npm install
npm run build
```

## Tests

The backend includes automated tests to ensure the stability and correctness of the application.

To execute the tests, navigate to the **backend** folder and run:

```sh
php artisan test
```

### License

The MIT License (MIT). See [LICENSE](LICENSE) for more details.
