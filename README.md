# my-php-html-project/my-php-html-project/README.md

# PHP and HTML Project

This project is a web application that includes three modules: Admin, Worker,
and User. Each module has its own sign-in and sign-up functionality, along with
individual dashboards tailored to their specific roles.

## Project Structure

```
my-php-html-project
├── admin
│   ├── approve_service.php # Admin functionality to approve services
│   ├── dashboard.php       # Admin dashboard to approve services
│   ├── sign-in.php         # Admin sign-in functionality
│   ├── sign-out.php        # Admin sign-out functionality
│   ├── sign-up.php         # Admin sign-up functionality
│   └── unapprove_service.php # Admin functionality to unapprove services
├── worker
│   ├── approve_booking.php # Worker functionality to approve bookings
│   ├── dashboard.php       # Worker dashboard to create and manage services
│   ├── edit_service.php    # Worker functionality to edit their services
│   ├── my-services.php     # Worker page to view and edit their services
│   ├── sign-in.php         # Worker sign-in functionality
│   ├── sign-out.php        # Worker sign-out functionality
│   └── sign-up.php         # Worker sign-up functionality
├── user
│   ├── avail_service.php   # User functionality to avail services
│   ├── complete_booking.php# User functionality to mark bookings as completed
│   ├── dashboard.php       # User dashboard to view and manage services
│   ├── sign-in.php         # User sign-in functionality
│   ├── sign-out.php        # User sign-out functionality
│   └── sign-up.php         # User sign-up functionality
├── css
│   ├── adminDash.css       # Styles for admin dashboard
│   ├── adminSignIn.css     # Styles for admin sign-in
│   ├── adminSignUp.css     # Styles for admin sign-up
│   ├── index.css           # Styles for the index page
│   ├── styles.css          # General styles for the project
│   ├── userDash.css        # Styles for user dashboard
│   ├── userSignIn.css      # Styles for user sign-in
│   ├── userSignUp.css      # Styles for user sign-up
│   ├── workerDash.css      # Styles for worker dashboard
│   ├── workerSignIn.css    # Styles for worker sign-in
│   ├── workerSignUp.css    # Styles for worker sign-up
├── js
│   └── script.js           # JavaScript functionality
├── index.php               # Entry point for the application
├── config.php              # Database configuration and table creation
└── README.md               # Project documentation
```

## Functionality Overview

- **Admin Module**:
  - Admins can sign in and access a dashboard to approve services created by
    workers.
  - New admins can sign up through a registration form.
  - Admins can sign out.

- **Worker Module**:
  - Workers can sign in to manage their services, including creating, editing,
    and accepting user requests.
  - New workers can register via a sign-up form.
  - Workers can sign out.
  - Workers can approve bookings made by users.
  - Workers can view pending, active, and completed bookings.

- **User Module**:
  - Users can sign in to view and avail themselves of approved services.
  - Users can mark bookings as completed and provide ratings and reviews.
  - New users can sign up through a registration form.
  - Users can sign out.
  - Users can mark bookings as completed.

## Setup Instructions

1. Clone the repository to your local machine.
2. Ensure you have a PHP server running (e.g., XAMPP, WAMP).
3. Place the project folder in the server's root directory (e.g., `htdocs` for
   XAMPP).
4. Modify the `config.php` file to match your MySQL database configuration:
   ```php
   <?php
   $servername = "";  // Change this to your database server name if different
   $username = "";    // Change this to your database username
   $password = "";    // Change this to your database password
   $dbname = "";      // Change this to your database name
   $port = ;          // Change this to your database port if different
   ?>
   ```
5. Access the application via your web browser at
   `http://localhost/my-php-html-project/index.php`.

## Technologies Used

- PHP
- HTML
- CSS

This project serves as a foundational structure for a role-based web
application, allowing for easy expansion and customization.
