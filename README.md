# PowerRig - E-Commerce Web Application

A robust, PHP-driven web application designed for an ultimate PC parts, gaming gear, and accessories store. It provides a complete end-to-end shopping experience, from browsing products and managing wishlists to secure checkout via Stripe and automated invoice generation. PowerRig features a comprehensive single-vendor e-commerce platform with product administration, making it an ideal solution for PC parts store owners.

## Features

### For Users
- **Account Management**: User registration, profile updating, and secure email verification (`userProfile.php`, `verificationProcess.php`).
- **Product Discovery**: Browse PC parts and gaming gear with advanced search and filtering.
- **Cart & Wishlist**: Save favorite products to a wishlist or add them to the cart for purchase (`wishlist.php`, `cart.php`).
- **Secure Checkout**: Integrated Stripe payment gateway for seamless and secure transactions (`paymentProcess.php`, `checkout.php`).
- **Order History & Invoices**: View purchasing history and download automatically generated PDF invoices.

### For Administrators
- **Dashboard & Analytics**: Comprehensive admin dashboard for store overview (`adminDashboard.php`).
- **Product Management**: Add, update, manage inventory, and toggle product statuses (`manageProducts.php`, `addProduct.php`, `updateProduct.php`).
- **Order Management**: Track orders, manage delivery fees, and update invoice statuses (`manageDelivery.php`, `changeInvoiceStatusProcess.php`).
- **User Management**: Manage registered users and handle user blocking/status changes (`manageUsers.php`).

## Technologies Used

- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap
- **Backend:** PHP (Core PHP without frameworks)
- **Database:** MySQL (Custom Database class wrapper - no ORM)
- **Tools:** Git, Composer
- **Integrations:** Stripe PHP SDK, PHPMailer, FPDF (PDF Generation)

## Screenshots

Add screenshots of the application's main pages here.

![Application Screenshot](docs/screenshots/homepage.png)

## Prerequisites

Before running the project, install the required software:

- A local web server stack (e.g., XAMPP, WAMP, or LAMP) with **PHP** and **MySQL**.
- **Composer** (globally installed to manage PHP dependencies).
- **Git**
- A **Stripe Account** for setting up test API keys.

## Installation

1. Clone the repository:

   ```bash
   git clone <repository-url>
   ```

2. Open the project directory:

   ```bash
   cd PowerRig
   ```

3. Install or resolve the project dependencies:

   Run the following command to install Stripe and PHPMailer:
   ```bash
   composer install
   ```

4. Configure the database and environment variables as described below.

## Database Setup

1. Start your database server (e.g., via XAMPP or WAMP).
2. Open your MySQL client (e.g., phpMyAdmin) and create a new database named `powerRig`.
3. Import the SQL structure file provided in the repository, located at `database/powerRig.sql`.
4. Configure the database connection using your local environment settings by updating `connection.php`:
   ```php
   Database::$connection = new mysqli("localhost", "root", "your_password", "powerRig", "3306");
   ```

## Configuration

You will need to configure your API keys directly within the process files, as `.env` files are not currently used in this project setup.

Update the following files with your credentials:

- **Stripe**: Update `paymentProcess.php` (and any other relevant files) with your Stripe Test Secret Key.
- **Email/PHPMailer**: Update the respective process files (like `verificationProcess.php`, `forgotPasswordProcess.php`) with your SMTP credentials for email delivery to work.

Do not commit real passwords, API keys, or other secrets.

## Running the Project

1. Ensure the `PowerRig` folder is placed within your local server's web root directory (e.g., `htdocs` for XAMPP, `www` for WAMP).
2. Start the Apache and MySQL services on your web server stack.
3. Access the application in your browser using the local URL:
   ```text
   http://localhost/PowerRig/index.php
   ```

## Project Structure

```text
PowerRig/
├── database/                  # SQL dump (powerRig.sql) for database structure
├── font/                      # Font definitions for PDF invoice generation (FPDF)
├── invoices/                  # Automatically generated PDF invoices
├── resources/                 # Product images and site media assets
├── vendor/                    # Composer dependencies (Stripe, PHPMailer)
├── composer.json              # Project dependencies configuration
├── connection.php             # Custom MySQL database connection wrapper
├── adminDashboard.php         # Main administrator dashboard
├── index.php                  # Entry point of the application
├── paymentProcess.php         # Stripe checkout initialization logic
├── script.js                  # Main frontend JavaScript logic including AJAX calls
└── README.md
```

## Testing

There is currently no automated test framework configured in the repository. Manual testing is required for both frontend and backend functionality. Ensure you test product addition, user registration, and Stripe checkout (using test cards) to verify the flow.

## Known Issues

- **Email Deliverability**: Emails for verification and password reset require valid SMTP credentials in the corresponding process files. Ensure these are set up correctly, otherwise functionality will break.
- **Security**: The custom database wrapper requires careful handling of user inputs. Be mindful of potential SQL injection risks if raw queries are modified or new queries are poorly constructed.

## Future Improvements

- Migrate to an MVC architecture to better separate business logic and presentation.
- Implement an Object-Relational Mapper (ORM) for safer and easier database interactions.
- Add an automated testing framework such as PHPUnit.
- Implement `.env` file support to manage secrets and API keys outside of the codebase.

## Contributing

Contributions are welcome! If you find any bugs or have suggestions, please open an issue or submit a pull request with your improvements.

## License

This project is licensed under the MIT License.
