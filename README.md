# PowerRig - E-Commerce Web Application

Welcome to PowerRig, a robust, full-stack, PHP-driven web application tailored for the ultimate PC parts, gaming gear, and computer accessories store. PowerRig delivers a comprehensive, end-to-end e-commerce solution that covers the entire customer journey. Shoppers can seamlessly browse an extensive catalog of computer hardware, save their favorite builds or items to a personalized wishlist, add products to their shopping cart, and complete their purchases through a secure and streamlined checkout process powered by Stripe.

Designed as a powerful single-vendor e-commerce platform, PowerRig is the perfect out-of-the-box solution for computer hardware retailers and independent PC parts store owners. It comes equipped with a dedicated and intuitive administrator dashboard that empowers store owners to effortlessly manage product catalogs, monitor inventory levels, track customer orders, and oversee registered users, all from one centralized hub.

Whether you are looking to launch a boutique gaming gear store or a comprehensive PC components marketplace, PowerRig provides the essential features and performance to kickstart your online business.

## Features

### For Users
- **Secure Account Management**: Fully-featured user registration, login, and profile management with secure email verification for new accounts and password resets (`userProfile.php`, `verificationProcess.php`).
- **Advanced Product Discovery**: Effortlessly browse and search for PC components and gaming gear using advanced search criteria, detailed filtering options, and dynamic category navigation.
- **Shopping Cart & Personalized Wishlist**: Easily save favorite products for later in a personalized wishlist, or add items directly to a responsive shopping cart for immediate purchase (`wishlist.php`, `cart.php`).
- **Secure & Seamless Checkout**: Experience a frictionless checkout process with an integrated Stripe payment gateway, ensuring secure and reliable transaction processing (`paymentProcess.php`, `checkout.php`).
- **Order History & Automated PDF Invoices**: Customers can track their purchasing history in a dedicated dashboard and download professionally formatted, automatically generated PDF invoices for their records (`purchasingHistory.php`, `invoice.php`).
- **Responsive Design**: A fully responsive frontend layout built with Bootstrap, providing an optimized shopping experience across desktop, tablet, and mobile devices.

### For Administrators
- **Comprehensive Dashboard & Analytics**: Gain immediate insights into store performance with a centralized admin dashboard featuring key metrics, recent orders, and overall sales overview (`adminDashboard.php`).
- **Robust Product Management**: Efficiently build and maintain the product catalog. Administrators can add new items with multiple images, update existing product details, manage stock inventory, configure dynamic pricing, and instantly toggle product visibility/status (`manageProducts.php`, `addProduct.php`, `updateProduct.php`).
- **Streamlined Order & Delivery Management**: Monitor incoming orders, process shipments, update delivery fees dynamically based on regions, and manage overall invoice and fulfillment statuses (`manageDelivery.php`, `changeInvoiceStatusProcess.php`).
- **Complete User Administration**: Maintain full control over the customer base. View all registered users, manage user statuses, and enforce platform security by handling user blocking or unblocking directly from the admin panel (`manageUsers.php`).
- **Brand & Category Management**: Dynamically add and link new brands, categories, and models to keep the product catalog structured and up-to-date (`manageCategories.php`, `manageBrands.php`).

## Technologies Used

- **Frontend:** HTML5, CSS3, Vanilla JavaScript, Bootstrap 5
- **Backend:** PHP (Core PHP/Procedural & Object-Oriented without heavy MVC frameworks)
- **Database:** MySQL (Custom Database connection class wrapper utilizing mysqli - no ORM)
- **Tools:** Git, Composer (Dependency Management)
- **Integrations:** Stripe PHP SDK (Payments), PHPMailer (Email Services), FPDF (PDF Generation)

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
