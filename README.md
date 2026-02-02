# Clinic Appointment Management System

A full-stack PHP application for managing doctor appointments, patient records, and schedules. Built for XAMPP.

---

## Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)
- [Project Structure](#project-structure)
- [Security](#security)
- [Troubleshooting](#troubleshooting)

---

## Features

### Patient Management
- Add new patients with personal details (name, email, phone, address, DOB, gender)
- View all patients in a list with pagination
- Edit patient information
- Delete patient records
- View individual patient profiles with appointment history

### Doctor Management
- Register doctors with specialization and qualifications
- Set available days (Monday-Sunday)
- Configure working hours (start and end time)
- View, edit, and delete doctor records
- Track doctor schedules

### Appointment Scheduling
- Book appointments by selecting patient, doctor, date, and time
- Real-time slot availability check using AJAX
- Automatically shows only available time slots based on doctor's schedule
- Set appointment status (Scheduled, Completed, Cancelled)
- Add reason/notes for each appointment
- Edit and cancel existing appointments

### Search and Filter
- Search appointments by date, doctor, patient, or status
- Filter results with multiple criteria
- Quick access to appointment details

### Authentication and Security
- User login with session management
- Role-based access control (Admin, Staff, Doctor)
- CSRF protection on all forms
- Input validation and sanitization
- Secure password hashing

### User Interface
- Clean, responsive design
- Dashboard with statistics (total patients, doctors, appointments, today's appointments)
- Flash messages for user feedback
- Form validation with error messages

---

## Requirements

- XAMPP (PHP 8.1+, MySQL/MariaDB)
- Composer
- Database provided by college server

---

## Installation

1. Place the project in your htdocs folder:
   ```
   C:\xampp\htdocs\ClinicAppointmentSystem
   ```

2. Install dependencies:
   ```bash
   cd C:\xampp\htdocs\ClinicAppointmentSystem
   composer install
   ```

---

## Database

The database is already set up on the college server. No need to create or import anything.

Default credentials (from db.php):

| Setting  | Value             |
|----------|-------------------|
| Host     | localhost         |
| Database | np02cs4a240118    |
| Username | np02cs4a240118    |
| Password | YWkI5Vz8XD        |

---

## Configuration

You can optionally create a `.env` file to override the default database settings:

```env
DB_HOST=localhost
DB_NAME=np02cs4a240118
DB_USER=np02cs4a240118
DB_PASS=YWkI5Vz8XD

SESSION_NAME=clinic_session
```

If no `.env` file exists, the application uses the defaults from `config/db.php`.

---

## Running the Application

1. Make sure Apache and MySQL are running
2. Open your browser and go to:

```
http://localhost/ClinicAppointmentSystem/public/
```

Default login: `admin` / `admin123`

---

## Project Structure

```
ClinicAppointmentSystem/
├── assets/           CSS and JavaScript files
├── cache/            Blade template cache
├── config/           Database configuration
├── database/         SQL schema files
├── includes/         Core PHP functions
├── public/           Web entry point and controllers
├── vendor/           Composer dependencies
└── views/            Blade templates
```

---

## Security

- CSRF token protection
- SQL injection prevention (PDO prepared statements)
- XSS protection via input sanitization
- Password hashing with BCRYPT
- Secure session handling

---

## User Roles

| Role   | Access Level                        |
|--------|-------------------------------------|
| Admin  | Full system access                  |
| Staff  | Manage patients and appointments    |
| Doctor | View schedules and appointments     |

---

## Troubleshooting

**Class not found errors**
Run `composer dump-autoload`

**Database connection failed**
Check that MySQL is running and verify your `.env` credentials

**Blank page or 500 error**
Enable `display_errors` in php.ini and check `C:\xampp\apache\logs\error.log`

**Cache folder permission issues**
Make sure the `cache/` directory is writable

