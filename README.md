# PowerRig
PowerRig :- This is a Fully Working Computer parts and Accessories buying site with fully backend and Admin Parts included 
# E-Commerce Web Application

A robust, PHP-driven web application designed for e-commerce management. Features include product administration, wishlist management, user account verification, and automated PDF invoice generation.

---

## 🚀 Features

- **Product Management**: Add, update, and manage products with status toggling (`toggleStatusProcess.php`, `updateProduct.php`).
- **User Account & Profiles**: User registration, profile updating, and status verification workflows (`userProfile.php`, `verificationProcess.php`).
- **Wishlist Support**: Users can save and manage their favorite products (`wishlist.php`).
- **Invoice Generation**: PDF invoice creation using FPDF fonts for structured transaction records (`invoices/`).
- **Admin & Process Logic**: Backend PHP logic handling asynchronous and process-driven updates safely.

---

## 🛠️ Built With

* **Backend**: PHP
* **Frontend**: HTML5, CSS3, JavaScript
* **Database**: MySQL
* **Tools & Libraries**: FPDF / Custom PDF Generator, Live Server (VS Code)

---

## 📁 Directory Structure

```text
├── font/                      # Font definitions for PDF invoice generation
├── invoices/                  # Generated PDF invoices
├── resources/                 # Product images and asset media
├── test.php                   # Testing and development scripts
├── toggleStatusProcess.php    # Process file to toggle product/user status
├── updateProduct.php          # Interface to update product details
├── updateProfileProcess.php   # User profile update handler
├── userProfile.php            # User profile dashboard
├── verificationProcess.php    # Email or account verification process
└── wishlist.php               # Wishlist display and logic