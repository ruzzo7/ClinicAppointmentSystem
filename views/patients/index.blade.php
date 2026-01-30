@extends('layout')

@section('title', 'Patients - Clinic Appointment System')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-user-injured"></i> Patient Management</h1>
        <a href="{{ url('patients/create.php') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Patient
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>All Patients</h2>
            <div class="search-box">
                <input type="text" id="patientSearch" placeholder="Search patients..." class="form-control">
                <i class="fas fa-search"></i>
            </div>
        </div>
        <div class="card-body">
            @if(count($patients) > 0)
            <div class="table-responsive">
                <table class="table" id="patientsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Date of Birth</th>
                            <th>Gender</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                        <tr>
                            <td>{{ $patient['id'] }}</td>
                            <td>{{ $patient['name'] }}</td>
                            <td>{{ $patient['email'] }}</td>
                            <td>{{ $patient['phone'] }}</td>
                            <td>{{ date('M d, Y', strtotime($patient['date_of_birth'])) }}</td>
                            <td><span class="badge badge-{{ $patient['gender'] }}">{{ ucfirst($patient['gender']) }}</span></td>
                            <td class="actions">
                                <a href="{{ url('patients/edit.php?id=' . $patient['id']) }}" class="btn btn-sm btn-outline" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deletePatient({{ $patient['id'] }}, '{{ $patient['name'] }}')" class="btn btn-sm btn-danger" title="Delete">
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
                <i class="fas fa-user-injured"></i>
                <p>No patients found. Add your first patient to get started.</p>
                <a href="{{ url('patients/create.php') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Patient
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
// Live search functionality
document.getElementById('patientSearch')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#patientsTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Delete patient with confirmation
function deletePatient(id, name) {
    const url = `{{ url('patients/delete.php') }}?id=${id}`;
    const message = `Are you sure you want to delete patient "${name}"? This will also delete all their appointments and cannot be undone.`;
    openDeleteModal(url, message);
}
</script>
@endsection
