@extends('layout')

@section('title', 'Appointment Details')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-calendar-check"></i> Appointment Details</h1>
        <div class="header-actions">
            <a href="{{ url('appointments/index.php') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            @if($appointment['status'] === 'scheduled')
            <a href="{{ url('appointments/edit.php?id=' . $appointment['id']) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Appointment
            </a>
            @endif
        </div>
    </div>

    <div class="view-card centered">
        <div class="card-header">
            <h2>Appointment #{{ $appointment['id'] }} Information</h2>
            <span class="badge badge-{{ $appointment['status'] }} lg">
                {{ strtoupper($appointment['status']) }}
            </span>
        </div>
        <div class="card-body">
            <div class="view-details">
                <div class="detail-section">
                    <h3><i class="fas fa-user-injured"></i> Patient Info</h3>
                    <div class="detail-row">
                        <label>Name:</label>
                        <p><a href="{{ url('patients/view.php?id=' . $appointment['patient_id']) }}">{{ $appointment['patient_name'] }}</a></p>
                    </div>
                </div>

                <div class="detail-section">
                    <h3><i class="fas fa-user-md"></i> Doctor Info</h3>
                    <div class="detail-row">
                        <label>Doctor:</label>
                        <p><a href="{{ url('doctors/view.php?id=' . $appointment['doctor_id']) }}">{{ $appointment['doctor_name'] }}</a></p>
                    </div>
                    <div class="detail-row">
                        <label>Specialization:</label>
                        <p><span class="badge badge-primary">{{ $appointment['specialization'] }}</span></p>
                    </div>
                </div>

                <div class="detail-section">
                    <h3><i class="fas fa-clock"></i> Schedule</h3>
                    <div class="detail-row">
                        <label>Date:</label>
                        <p>{{ date('l, F d, Y', strtotime($appointment['appointment_date'])) }}</p>
                    </div>
                    <div class="detail-row">
                        <label>Time:</label>
                        <p>{{ date('g:i A', strtotime($appointment['appointment_time'])) }}</p>
                    </div>
                </div>

                <div class="detail-section full-width">
                    <h3><i class="fas fa-notes-medical"></i> Reason for Visit</h3>
                    <p class="reason-text">{{ $appointment['reason'] ?: 'No reason provided.' }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <p>Created at: {{ date('M d, Y H:i', strtotime($appointment['created_at'])) }}</p>
        </div>
    </div>
</div>

<style>
.view-card.centered {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--gray-50);
    padding: var(--spacing-lg) var(--spacing-xl);
    border-bottom: 1px solid var(--gray-200);
}
.card-header h2 {
    margin: 0;
    font-size: 1.25rem;
}
.badge.lg {
    padding: 8px 16px;
    font-size: 0.9rem;
    font-weight: 700;
}
.card-body {
    padding: var(--spacing-xl);
}
.view-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-xl);
}
.detail-section h3 {
    font-size: 1rem;
    color: var(--gray-600);
    margin-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--gray-100);
    padding-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.detail-row {
    margin-bottom: var(--spacing-sm);
    display: flex;
    gap: 8px;
}
.detail-row label {
    font-weight: 600;
    color: var(--gray-500);
    min-width: 100px;
}
.detail-row p a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
}
.detail-row p a:hover {
    text-decoration: underline;
}
.full-width {
    grid-column: span 2;
}
.reason-text {
    background: var(--gray-50);
    padding: var(--spacing-md);
    border-radius: var(--radius-md);
    line-height: 1.6;
    color: var(--gray-800);
}
.card-footer {
    background: var(--gray-50);
    padding: var(--spacing-md) var(--spacing-xl);
    border-top: 1px solid var(--gray-200);
    color: var(--gray-500);
    font-size: 0.8rem;
    text-align: right;
}
@media (max-width: 600px) {
    .view-details {
        grid-template-columns: 1fr;
    }
    .full-width {
        grid-column: span 1;
    }
}
</style>
@endsection
