@extends('layout')

@section('title', 'Home - Clinic Appointment System')

@section('content')
<div class="hero">
    <div class="container">
        <h1 class="hero-title">Modern Healthcare Management</h1>
        <p class="hero-subtitle">Experience a clean, professional, and efficient scheduling platform designed for your clinic.</p>
        <div class="hero-actions">
            <a href="{{ url('appointments/create.php') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-calendar-plus"></i> Book Appointment
            </a>
            <a href="{{ url('appointments/index.php') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-list-alt"></i> View Appointments
            </a>
        </div>
    </div>
</div>

<div class="container">
    <section class="features">
        <h2 class="section-title">Our Features</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-user-injured"></i>
                </div>
                <h3>Patient Management</h3>
                <p>Complete patient records with secure data storage and easy access</p>
                <a href="{{ url('patients/index.php') }}" class="btn btn-outline">Manage Patients</a>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <h3>Doctor Directory</h3>
                <p>Browse our qualified doctors and their specializations</p>
                <a href="{{ url('doctors/index.php') }}" class="btn btn-outline">View Doctors</a>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3>Smart Scheduling</h3>
                <p>Real-time availability check and conflict prevention</p>
                <a href="{{ url('appointments/create.php') }}" class="btn btn-outline">Book Now</a>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Advanced Search</h3>
                <p>Find appointments by date, doctor, patient, or status</p>
                <a href="{{ url('appointments/search.php') }}" class="btn btn-outline">Search</a>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Secure & Safe</h3>
                <p>Protected against SQL injection, XSS, and CSRF attacks</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Real-time Updates</h3>
                <p>Live availability check using Ajax technology</p>
            </div>
        </div>
    </section>

    <section class="stats">
        <h2 class="section-title">System Statistics</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['patients'] ?? 0 }}</div>
                <div class="stat-label">Total Patients</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['doctors'] ?? 0 }}</div>
                <div class="stat-label">Active Doctors</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['appointments'] ?? 0 }}</div>
                <div class="stat-label">Total Appointments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $stats['today'] ?? 0 }}</div>
                <div class="stat-label">Today's Appointments</div>
            </div>
        </div>
    </section>
</div>
@endsection
