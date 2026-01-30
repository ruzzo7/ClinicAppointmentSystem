@extends('layout')

@section('title', 'Patient Details - ' . $patient['name'])

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user-injured"></i> Patient Details</h1>
        <div class="header-actions">
            <a href="{{ url('patients/index.php') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
            <a href="{{ url('patients/edit.php?id=' . $patient['id']) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Patient
            </a>
        </div>
    </div>

    <div class="view-grid">
        <div class="view-card main-info">
            <div class="card-header">
                <h2>Personal Information</h2>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <label>Full Name</label>
                    <p>{{ $patient['name'] }}</p>
                </div>
                <div class="info-group">
                    <label>Email Address</label>
                    <p>{{ $patient['email'] }}</p>
                </div>
                <div class="info-group">
                    <label>Phone Number</label>
                    <p>{{ $patient['phone'] }}</p>
                </div>
                <div class="info-row">
                    <div class="info-group">
                        <label>Date of Birth</label>
                        <p>{{ date('M d, Y', strtotime($patient['date_of_birth'])) }}</p>
                    </div>
                    <div class="info-group">
                        <label>Gender</label>
                        <p><span class="badge badge-{{ $patient['gender'] }}">{{ ucfirst($patient['gender']) }}</span></p>
                    </div>
                </div>
                <div class="info-group">
                    <label>Address</label>
                    <p>{{ $patient['address'] ?: 'No address provided' }}</p>
                </div>
            </div>
        </div>

        <div class="view-card appointments-history">
            <div class="card-header">
                <h2>Appointment History</h2>
            </div>
            <div class="card-body">
                @if(count($appointments) > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Doctor</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $app)
                            <tr>
                                <td>{{ date('M d, Y', strtotime($app['appointment_date'])) }}</td>
                                <td>{{ $app['doctor_name'] }}</td>
                                <td>
                                    <span class="badge badge-{{ $app['status'] }}">
                                        {{ ucfirst($app['status']) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ url('appointments/view.php?id=' . $app['id']) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state mini">
                    <p>No appointments found for this patient.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.view-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-xl);
}
.view-card {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.card-header {
    background: var(--gray-50);
    padding: var(--spacing-md) var(--spacing-lg);
    border-bottom: 1px solid var(--gray-200);
}
.card-header h2 {
    font-size: 1.1rem;
    color: var(--gray-800);
    margin: 0;
}
.card-body {
    padding: var(--spacing-lg);
}
.info-group {
    margin-bottom: var(--spacing-md);
}
.info-group label {
    display: block;
    font-size: 0.8rem;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}
.info-group p {
    font-size: 1.1rem;
    color: var(--gray-900);
    font-weight: 500;
}
.info-row {
    display: flex;
    gap: var(--spacing-xl);
}
@media (max-width: 768px) {
    .view-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
