@extends('layout')

@section('title', isset($appointment) ? 'Edit Appointment' : 'Book New Appointment')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>
            <i class="fas fa-calendar-check"></i> 
            {{ isset($appointment) ? 'Edit Appointment' : 'Book New Appointment' }}
        </h1>
        <a href="{{ url('appointments/index.php') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Appointments
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ isset($appointment) ? url('appointments/edit.php?id=' . $appointment['id']) : url('appointments/create.php') }}" class="form" id="appointmentForm">
                <input type="hidden" name="csrf_token" value="{{ $csrf_token }}">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="patient_id">Patient <span class="required">*</span></label>
                        <select id="patient_id" name="patient_id" class="form-control" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                            <option value="{{ $patient['id'] }}" 
                                    {{ (isset($appointment) && $appointment['patient_id'] == $patient['id']) ? 'selected' : '' }}>
                                {{ $patient['name'] }} ({{ $patient['email'] }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="doctor_id">Doctor <span class="required">*</span></label>
                        <select id="doctor_id" name="doctor_id" class="form-control" required>
                            <option value="">Select Doctor</option>
                            @foreach($doctors as $doctor)
                            <option value="{{ $doctor['id'] }}" 
                                    data-specialization="{{ $doctor['specialization'] }}"
                                    {{ (isset($appointment) && $appointment['doctor_id'] == $doctor['id']) ? 'selected' : '' }}>
                                {{ $doctor['name'] }} - {{ $doctor['specialization'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="appointment_date">Appointment Date <span class="required">*</span></label>
                        <input type="date" id="appointment_date" name="appointment_date" class="form-control" 
                               value="{{ $appointment['appointment_date'] ?? '' }}" 
                               min="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="appointment_time">Appointment Time <span class="required">*</span></label>
                        <select id="appointment_time" name="appointment_time" class="form-control" required disabled>
                            <option value="">Select date and doctor first</option>
                        </select>
                        <div id="time-loading" class="loading-indicator" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Checking availability...
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="reason">Reason for Visit <span class="required">*</span></label>
                    <textarea id="reason" name="reason" class="form-control" rows="3" required>{{ $appointment['reason'] ?? '' }}</textarea>
                </div>

                @if(isset($appointment))
                <div class="form-group">
                    <label for="status">Status <span class="required">*</span></label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="scheduled" {{ $appointment['status'] === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="completed" {{ $appointment['status'] === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $appointment['status'] === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                @else
                <input type="hidden" name="status" value="scheduled">
                @endif

                <div id="availability-message" class="alert" style="display: none;"></div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> {{ isset($appointment) ? 'Update Appointment' : 'Book Appointment' }}
                    </button>
                    <a href="{{ url('appointments/index.php') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
// Ajax: Live check for available time slots
let currentAppointmentId = {{ isset($appointment) ? $appointment['id'] : 'null' }};

function checkAvailability() {
    const doctorEl = document.getElementById('doctor_id');
    const dateEl = document.getElementById('appointment_date');
    const timeSelect = document.getElementById('appointment_time');
    const loading = document.getElementById('time-loading');
    
    if (!doctorEl || !dateEl || !timeSelect) return;

    const doctorId = doctorEl.value;
    const date = dateEl.value;
    
    if (!doctorId || !date) {
        timeSelect.disabled = true;
        timeSelect.innerHTML = '<option value="">Select date and doctor first</option>';
        return;
    }
    
    // Show loading
    if (loading) loading.style.display = 'block';
    timeSelect.disabled = true;
    
    // Fetch available time slots using Ajax
    fetch(`{{ url('ajax/check_availability.php') }}?doctor_id=${doctorId}&date=${date}${currentAppointmentId ? '&exclude_id=' + currentAppointmentId : ''}`)
        .then(response => response.json())
        .then(data => {
            if (loading) loading.style.display = 'none';
            
            if (data.success) {
                timeSelect.innerHTML = '<option value="">Select a time slot</option>';
                
                if (data.slots && data.slots.length > 0) {
                    data.slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot.time;
                        option.textContent = slot.formatted + (slot.available ? '' : ' (Booked)');
                        option.disabled = !slot.available;
                        
                        // Pre-select current time if editing
                        @if(isset($appointment))
                        if (slot.time === '{{ $appointment['appointment_time'] }}') {
                            option.selected = true;
                        }
                        @endif
                        
                        timeSelect.appendChild(option);
                    });
                    
                    timeSelect.disabled = false;
                } else {
                    timeSelect.innerHTML = '<option value="">No slots available for this day</option>';
                }
                
                // Show availability message
                const availableCount = data.slots ? data.slots.filter(s => s.available).length : 0;
                const message = document.getElementById('availability-message');
                if (message) {
                    message.className = availableCount > 0 ? 'alert alert-success' : 'alert alert-warning';
                    message.textContent = `${availableCount} time slot(s) available on this date`;
                    message.style.display = 'block';
                }
            } else {
                timeSelect.innerHTML = '<option value="">Error: ' + (data.message || 'Unknown error') + '</option>';
                
                const message = document.getElementById('availability-message');
                if (message) {
                    message.className = 'alert alert-error';
                    message.textContent = data.message || 'Error loading time slots';
                    message.style.display = 'block';
                }
            }
        })
        .catch(error => {
            if (loading) loading.style.display = 'none';
            console.error('Error:', error);
            if (timeSelect) {
                timeSelect.innerHTML = '<option value="">Error loading slots</option>';
            }
        });
}

// Trigger availability check when doctor or date changes
document.getElementById('doctor_id').addEventListener('change', checkAvailability);
document.getElementById('appointment_date').addEventListener('change', checkAvailability);

// Initial load if editing
@if(isset($appointment))
window.addEventListener('load', checkAvailability);
@endif

// Initialize live form validation
const appointmentValidator = new FormValidator('appointmentForm', {
    validateOnInput: true,
    validateOnBlur: true,
    showSuccessIcons: true,
    debounceDelay: 300
});

// Add custom validators
appointmentValidator.addValidator('patient_id', (value) => {
    if (!value || value === '') {
        return 'Please select a patient';
    }
    return true;
});

appointmentValidator.addValidator('doctor_id', (value) => {
    if (!value || value === '') {
        return 'Please select a doctor';
    }
    return true;
});

appointmentValidator.addValidator('appointment_date', (value) => {
    if (!value) {
        return 'Please select an appointment date';
    }
    
    const selectedDate = new Date(value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate < today) {
        return 'Appointment date cannot be in the past';
    }
    
    // Check if date is more than 6 months in future
    const sixMonthsFromNow = new Date();
    sixMonthsFromNow.setMonth(sixMonthsFromNow.getMonth() + 6);
    
    if (selectedDate > sixMonthsFromNow) {
        return 'Appointment date cannot be more than 6 months in the future';
    }
    
    return true;
});

appointmentValidator.addValidator('appointment_time', (value) => {
    if (!value || value === '') {
        return 'Please select an appointment time';
    }
    return true;
});

appointmentValidator.addValidator('reason', (value) => {
    if (value.length < 10) {
        return 'Please provide a detailed reason (at least 10 characters)';
    }
    if (value.length > 500) {
        return 'Reason is too long (maximum 500 characters)';
    }
    return true;
});

// Add error message elements if they don't exist
const formGroups = [
    { id: 'patient_id', errorId: 'patient-error' },
    { id: 'doctor_id', errorId: 'doctor-error' },
    { id: 'appointment_date', errorId: 'date-error' },
    { id: 'appointment_time', errorId: 'time-error' },
    { id: 'reason', errorId: 'reason-error' }
];

formGroups.forEach(({ id, errorId }) => {
    const field = document.getElementById(id);
    if (field) {
        const formGroup = field.closest('.form-group');
        if (formGroup && !document.getElementById(errorId)) {
            const errorSpan = document.createElement('span');
            errorSpan.className = 'error-message';
            errorSpan.id = errorId;
            formGroup.appendChild(errorSpan);
        }
    }
});

// Add validation hints
const patientEl = document.getElementById('patient_id');
const doctorEl = document.getElementById('doctor_id');
const dateEl = document.getElementById('appointment_date');
const timeEl = document.getElementById('appointment_time');
const reasonEl = document.getElementById('reason');

if (patientEl) patientEl.setAttribute('title', 'Select the patient for this appointment');
if (doctorEl) doctorEl.setAttribute('title', 'Select the doctor for this appointment');
if (dateEl) dateEl.setAttribute('title', 'Select appointment date');
if (timeEl) timeEl.setAttribute('title', 'Select available time slot');
if (reasonEl) reasonEl.setAttribute('title', 'Describe the reason for visit (10-500 characters)');
</script>
@endsection
