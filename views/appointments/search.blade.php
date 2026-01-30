@extends('layout')

@section('title', 'Search Appointments - Clinic Appointment System')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-search"></i> Advanced Appointment Search</h1>
        <a href="{{ url('appointments/index.php') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Appointments
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Search Criteria</h2>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ url('appointments/search.php') }}" class="form search-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="date">Appointment Date</label>
                        <input type="date" id="date" name="date" class="form-control" 
                               value="{{ $_GET['date'] ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label for="doctor_id">Doctor</label>
                        <select id="doctor_id" name="doctor_id" class="form-control">
                            <option value="">All Doctors</option>
                            @foreach($doctors as $doctor)
                            <option value="{{ $doctor['id'] }}" 
                                    {{ (isset($_GET['doctor_id']) && $_GET['doctor_id'] == $doctor['id']) ? 'selected' : '' }}>
                                {{ $doctor['name'] }} - {{ $doctor['specialization'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="patient_id">Patient</label>
                        <select id="patient_id" name="patient_id" class="form-control">
                            <option value="">All Patients</option>
                            @foreach($patients as $patient)
                            <option value="{{ $patient['id'] }}" 
                                    {{ (isset($_GET['patient_id']) && $_GET['patient_id'] == $patient['id']) ? 'selected' : '' }}>
                                {{ $patient['name'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="scheduled" {{ (isset($_GET['status']) && $_GET['status'] === 'scheduled') ? 'selected' : '' }}>Scheduled</option>
                            <option value="completed" {{ (isset($_GET['status']) && $_GET['status'] === 'completed') ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ (isset($_GET['status']) && $_GET['status'] === 'cancelled') ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ url('appointments/search.php') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if(isset($results))
    <div class="card">
        <div class="card-header">
            <h2>Search Results ({{ count($results) }} found)</h2>
        </div>
        <div class="card-body">
            @if(count($results) > 0)
            <div class="table-responsive">
                <table class="table">
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
                        @foreach($results as $appointment)
                        <tr>
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
                                <a href="{{ url('appointments/edit.php?id=' . $appointment['id']) }}" class="btn btn-sm btn-outline">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <p>No appointments found matching your search criteria.</p>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
