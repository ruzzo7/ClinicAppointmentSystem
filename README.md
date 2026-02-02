# Clinic Appointment Management System

A full-stack PHP application for managing doctor appointments, patient records, and schedules. Built for XAMPP.

---

## Table of Contents

- [Login Credentials](#login-credentials)
- [Setup Instructions](#setup-instructions)
- [Features Implemented](#features-implemented)
- [Requirements](#requirements)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)
- [Project Structure](#project-structure)
- [Security](#security)
- [Known Issues](#known-issues)
- [Troubleshooting](#troubleshooting)

---

## Login Credentials

### Default System Access

**Administrator/Staff Account:**
- Username: `admin/staff`
- Password: `admin123`
- Role: Administrator (Full system access - manages patients, doctors, and appointments)

**Note:** This system currently has a single user role with full administrative privileges. The admin/staff account provides complete access to all system features including patient management, doctor management, and appointment scheduling.

### Database Access

| Setting  | Value             |
|----------|-------------------|
| Host     | localhost         |
| Database | np02cs4a240118    |
| Username | np02cs4a240118    |
| Password | YWkI5Vz8XD        |

---

## Setup Instructions

### Prerequisites
1. **XAMPP**: Download and install XAMPP with PHP 8.1+ and MySQL
2. **Composer**: Install Composer for dependency management
3. **Modern Web Browser**: Chrome, Firefox, Safari, or Edge

### Step-by-Step Installation

1. **Start XAMPP Services:**
   - Open XAMPP Control Panel
   - Start Apache and MySQL services
   - Ensure both show green "Running" status

2. **Place Project Files:**
   ```
   C:\xampp\htdocs\ClinicAppointmentSystem
   ```

3. **Install Dependencies:**
   ```bash
   cd C:\xampp\htdocs\ClinicAppointmentSystem
   composer install
   ```

4. **Set Permissions:**
   - Ensure `cache/` directory is writable
   - Right-click cache folder → Properties → Security → Full Control

5. **Access Application:**
   - Open browser and navigate to:
   ```
   http://localhost/ClinicAppointmentSystem/public/
   ```

6. **Login:**
   - Use credentials: `admin/staff` / `admin123`
   - Complete initial setup by adding doctors and patients

### First-Time Configuration
- Add doctors with their specializations and schedules
- Register initial patients
- Configure system settings as needed

---

## Features Implemented

### Patient Management System
- **Patient Registration**: Complete patient onboarding with personal and medical information
  - Personal details (name, email, phone, address)
  - Medical information (date of birth, gender)
  - Emergency contact information
- **Patient Directory**: Searchable list with pagination and sorting options
- **Patient Profiles**: Comprehensive individual patient pages with appointment history
- **Data Management**: Edit, update, and delete patient records with confirmation
- **Search Functionality**: Find patients by name, email, phone, or medical ID

### Doctor Management System
- **Doctor Registration**: Professional profiles with credentials
  - Personal and contact information
  - Medical specialization and qualifications
- **Schedule Management**: Flexible availability configuration
  - Set working days (Monday through Sunday)
  - Define daily working hours (start and end times)
  - Multiple time slot management
- **Doctor Directory**: Browse doctors by specialization
- **Profile Management**: Edit doctor information and schedules
- **Availability Tracking**: Real-time schedule monitoring

### Advanced Appointment System
- **Smart Booking Engine**: Intelligent appointment scheduling
  - Real-time availability checking with AJAX
  - Automatic slot filtering based on doctor schedules
  - Conflict prevention and validation
- **Appointment Management**:
  - Book, edit, and cancel appointments
  - Multiple status options (Scheduled, Completed, Cancelled, No-Show)
  - Appointment notes and reason tracking
  - Complete appointment history
- **Search & Filter**: Advanced appointment lookup
  - Search by date, doctor, patient, or status
  - Multiple filter combinations
  - Quick access to appointment details

### Security & Authentication
- **Single Admin Role System**:
  - **Admin**: Complete system access with all management capabilities
- **Security Features**:
  - Secure login with session management
  - CSRF token protection on all forms
  - SQL injection prevention (PDO prepared statements)
  - XSS protection via input sanitization
  - Password hashing with BCRYPT algorithm
  - Math captcha for additional security

### User Interface & Experience
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Dashboard**: Comprehensive overview with real-time statistics
  - Total counts for patients, doctors, and appointments
  - Today's appointment summary
  - Quick action buttons
  - Recent activity feed
- **Navigation**: Intuitive menu system with role-based access
- **User Feedback**: Flash messages for all user actions
- **Form Validation**: Real-time client and server-side validation
- **Loading States**: AJAX indicators for smooth user experience

### Reporting & Analytics
- **Statistical Dashboard**: Key performance indicators
- **Appointment Analytics**: Track booking trends and patterns
- **Patient Statistics**: Monitor registration and visit patterns
- **Doctor Utilization**: Schedule usage and availability metrics

### Technical Implementation
- **Modern PHP Architecture**: Built with Composer and best practices
- **Template Engine**: Blade templating for clean, maintainable views
- **AJAX Integration**: Seamless user experience with asynchronous requests
- **Database Optimization**: Efficient queries with proper indexing
- **Error Handling**: Comprehensive logging and user-friendly error messages
- **Cache System**: Blade template caching for improved performance

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

## Known Issues

### Minor Compatibility Issues

**Browser Compatibility:**
- Math captcha may not display correctly in Internet Explorer 11 and below
- **Workaround**: Use modern browsers (Chrome 90+, Firefox 88+, Safari 14+, Edge 90+)

**Mobile Interface:**
- Date picker styling may vary on different mobile devices
- **Impact**: Minimal - all functionality remains intact

### Performance Considerations

**Large Dataset Handling:**
- Patient/appointment lists may load slower with 1000+ records
- **Current**: Pagination set to 20 records per page
- **Recommendation**: Adequate for typical clinic operations

**Concurrent Booking:**
- Rare possibility of appointment conflicts with simultaneous bookings
- **Mitigation**: Database constraints prevent actual double-booking
- **Impact**: User may need to refresh and select alternative time slot

### System Requirements

**Time Zone Handling:**
- Application uses server's local time zone
- **Note**: Suitable for single-location clinic operations
- **Consideration**: Multi-location clinics may need time zone configuration

**File Permissions:**
- Blade template cache requires write permissions
- **Solution**: Ensure `cache/` directory is writable by web server
- **Common on**: Windows systems with strict user permissions

### Development Notes

**Dependency Management:**
- Composer vendor directory must be present and updated
- **Solution**: Run `composer install` if vendor folder is missing
- **Auto-fix**: Most errors resolve after dependency installation

**Session Configuration:**
- Sessions may timeout on shared hosting environments
- **Adjustment**: Modify session timeout in `includes/security.php`

### Planned Improvements
- Email notification system for appointment reminders
- Advanced reporting dashboard with charts
- Export functionality for patient and appointment data
- Integration with external calendar systems
- Mobile application companion

### Support & Testing
- **Tested Environment**: Windows 10/11 with XAMPP 8.1+
- **Database Compatibility**: MySQL 5.7+, MariaDB 10.3+
- **PHP Versions**: Compatible with PHP 8.1 and 8.2

---

## Troubleshooting

### Common Issues

**Class not found errors**
```bash
composer dump-autoload
```

**Database connection failed**
- Check that MySQL service is running in XAMPP Control Panel
- Verify database credentials in `config/db.php`
- Test database connection manually

**Blank page or 500 error**
- Enable error display: Set `display_errors = On` in `php.ini`
- Check Apache error log: `C:\xampp\apache\logs\error.log`
- Verify file permissions on project directory

**Cache folder permission issues**
- Ensure `cache/` directory exists and is writable
- Windows: Right-click cache folder → Properties → Security → Full Control
- Create cache directory if missing: `mkdir cache`

**CSRF token errors**
- Clear browser cache and cookies
- Check if sessions are working properly
- Restart browser and try again

**Math captcha not displaying**
- Clear browser cache
- Disable browser extensions that might block JavaScript
- Check browser console for JavaScript errors

### Performance Issues

**Slow page loading**
- Clear Blade template cache: Delete files in `cache/` directory
- Restart Apache and MySQL services
- Check available system memory

**Database queries timing out**
- Restart MySQL service in XAMPP
- Verify database server performance
- Check for large dataset queries

### Getting Additional Help
1. **Check Error Logs**: Always start with `C:\xampp\apache\logs\error.log`
2. **Verify Services**: Ensure Apache and MySQL are running
3. **Test Database**: Confirm database connection is working
4. **Clear Cache**: Delete cache files and restart browser
5. **Check Permissions**: Verify file and directory permissions

