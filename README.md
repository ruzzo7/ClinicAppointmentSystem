# Clinic Appointment Management System

A professional, clinical-grade full-stack PHP application for managing doctor appointments, patient records, and schedules.

## Features
- **Modern Hospital UI**: Clean, responsive medical-grade aesthetic.
- **Advanced Appointment Search**: Filter by date, doctor, patient, and status.
- **Dynamic Slot Selection**: AJAX-powered real-time slot loading based on doctor availability.
- **Security First**: 
    - CSRF Protection
    - SQL Injection prevention (PDO)
    - XSS Protection
    - Math Challenge Verification on login
    - Secure Password Hashing (BCRYPT)

## Academic Audit
Verified for:
- CRUD Operations
- Database Schema Integrity
- Framework-style modularity (Blade Templates)

## Structure
- `/public`: Web gateway and controllers.
- `/includes`: Core logic and security.
- `/views`: Blade presentation layer.
- `/assets`: Theme assets (CSS/JS).
