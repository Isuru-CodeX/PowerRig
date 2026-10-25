# PowerRig - E-Commerce Web Application

A robust, PHP-driven web application designed for an ultimate PC parts, gaming gear, and accessories store. PowerRig features a comprehensive single-vendor e-commerce platform with product administration, seamless user experiences, integrated payment processing, and automated PDF invoice generation.

---

## 🚀 Overview

PowerRig is a single-vendor e-commerce store meaning all products are managed directly by the store administrators. It provides a complete end-to-end shopping experience, from browsing products and managing wishlists to secure checkout via Stripe and automated invoice generation.

---

## ✨ Features

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

---

## 🛠️ Tech Stack

* **Backend**: PHP (Core PHP without frameworks)
* **Frontend**: HTML5, CSS3, JavaScript, Bootstrap
* **Database**: MySQL (Custom Database class wrapper - no ORM)
* **Payment Gateway**: [Stripe PHP SDK](https://stripe.com/)
* **Emailing**: [PHPMailer](https://github.com/PHPMailer/PHPMailer)
* **PDF Generation**: [FPDF](http://www.fpdf.org/) for automated invoices
* **Dependency Management**: Composer

---

## 🏗️ Architecture & Design

- **Single-Vendor Model**: The database and logic are structured around a single store owner. Products are not tied to specific user accounts (no `user_email` in the product table).
- **Custom Database Wrapper**: The project uses a custom `Database` class wrapper (`connection.php`) to execute raw MySQL queries.
- **Process-Driven Logic**: The backend logic is heavily separated into dedicated process files (e.g., `addToCartProcess.php`, `paymentProcess.php`) which handle asynchronous requests (AJAX) from the frontend securely.

---

## 📁 Directory Structure

```text
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
└── ...                        # Various process scripts, CSS, and views
```

---

## ⚙️ Prerequisites

To run this project locally, you will need:
- A local web server stack (e.g., XAMPP, WAMP, or LAMP) with **PHP** and **MySQL**.
- **Composer** installed globally to manage PHP dependencies.
- A **Stripe Account** for setting up test API keys.

---

## 🚦 Installation Steps

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd PowerRig
   ```

2. **Database Setup:**
   - Open your MySQL client (e.g., phpMyAdmin).
   - Create a new database named `powerrig` (or import directly if the script creates it).
   - Import the provided SQL structure file located at `database/powerRig.sql`.
   - Update `connection.php` with your local MySQL credentials:
     ```php
     Database::$connection = new mysqli("localhost", "root", "your_password", "powerRig", "3306");
     ```

3. **Install Dependencies:**
   - Navigate to the project directory where `composer.json` is located.
   - Run the following command to install Stripe and PHPMailer:
     ```bash
     composer install
     ```

4. **Configure API Keys:**
   - **Stripe**: Update `paymentProcess.php` (and any other relevant files) with your Stripe Test Secret Key.
   - **Email/PHPMailer**: Update the respective process files (like `verificationProcess.php`, `forgotPasswordProcess.php`) with your SMTP credentials for email delivery to work.

5. **Run the Application:**
   - Ensure the project folder is within your local server's web root (e.g., `htdocs` for XAMPP).
   - Start Apache and MySQL services.
   - Access the application in your browser (adjust path as necessary).
