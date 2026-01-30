@extends('layout')

@section('title', 'Appointments - Clinic Appointment System')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-calendar-check"></i> Appointment Management</h1>
        <div class="header-actions">
            <a href="{{ url('appointments/search.php') }}" class="btn btn-secondary">
                <i class="fas fa-search"></i> Advanced Search
            </a>
            <a href="{{ url('appointments/create.php') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Book Appointment
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>All Appointments</h2>
            <div class="filter-group">
                <select id="statusFilter" class="form-control">
                    <option value="">All Status</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            @if(count($appointments) > 0)
            <div class="table-responsive">
                <table class="table" id="appointmentsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Specialization</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr data-status="{{ $appointment['status'] }}">
                            <td>{{ $appointment['id'] }}</td>
                            <td>{{ $appointment['patient_name'] }}</td>
                            <td>{{ $appointment['doctor_name'] }}</td>
                            <td><span class="badge badge-info">{{ $appointment['specialization'] }}</span></td>
                            <td>{{ date('M d, Y', strtotime($appointment['appointment_date'])) }}</td>
                            <td>{{ date('g:i A', strtotime($appointment['appointment_time'])) }}</td>
                            <td>
                                <span class="badge badge-{{ $appointment['status'] }}">
                                    {{ ucfirst($appointment['status']) }}
                                </span>
                            </td>
                            <td class="actions">
                                @if($appointment['status'] === 'scheduled')
                                <a href="{{ url('appointments/edit.php?id=' . $appointment['id']) }}" class="btn btn-sm btn-outline" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                <button onclick="deleteAppointment({{ $appointment['id'] }})" class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-calendar-check"></i>
                <p>No appointments found. Book your first appointment to get started.</p>
                <a href="{{ url('appointments/create.php') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Book Appointment
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
// Filter by status
document.getElementById('statusFilter')?.addEventListener('change', function(e) {
    const status = e.target.value;
    const rows = document.querySelectorAll('#appointmentsTable tbody tr');
    
    rows.forEach(row => {
        if (status === '' || row.dataset.status === status) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Delete appointment with confirmation
function deleteAppointment(id) {
    const url = `{{ url('appointments/delete.php') }}?id=${id}`;
    const message = `Are you sure you want to delete this appointment? This action cannot be undone.`;
    openDeleteModal(url, message);
}
</script>
@endsection
