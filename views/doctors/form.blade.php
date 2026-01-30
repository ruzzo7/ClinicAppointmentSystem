@extends('layout')

@section('title', isset($doctor) ? 'Edit Doctor' : 'Add New Doctor')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>
            <i class="fas fa-user-md"></i> 
            {{ isset($doctor) ? 'Edit Doctor' : 'Add New Doctor' }}
        </h1>
        <a href="{{ url('doctors/index.php') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Doctors
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ isset($doctor) ? url('doctors/edit.php?id=' . $doctor['id']) : url('doctors/create.php') }}" class="form" id="doctorForm">
                <input type="hidden" name="csrf_token" value="{{ $csrf_token }}">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" 
                               value="{{ $doctor['name'] ?? '' }}" required>
                        <span class="error-message" id="name-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="{{ $doctor['email'] ?? '' }}" required>
                        <span class="error-message" id="email-error"></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number <span class="required">*</span></label>
                        <input type="tel" id="phone" name="phone" class="form-control" 
                               value="{{ $doctor['phone'] ?? '' }}" required>
                        <span class="error-message" id="phone-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="specialization">Specialization <span class="required">*</span></label>
                        <input type="text" id="specialization" name="specialization" class="form-control" 
                               value="{{ $doctor['specialization'] ?? '' }}" 
                               placeholder="e.g., Cardiologist, Pediatrician" required>
                        <span class="error-message" id="specialization-error"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="qualification">Qualification <span class="required">*</span></label>
                    <input type="text" id="qualification" name="qualification" class="form-control" 
                           value="{{ $doctor['qualification'] ?? '' }}" 
                           placeholder="e.g., MBBS, MD" required>
                    <span class="error-message" id="qualification-error"></span>
                </div>

                <div class="form-group">
                    <label>Available Days <span class="required">*</span></label>
                    <div class="checkbox-group">
                        @php
                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            $selectedDays = isset($doctor) ? explode(',', $doctor['available_days']) : [];
                        @endphp
                        @foreach($days as $day)
                        <label class="checkbox-label">
                            <input type="checkbox" name="available_days[]" value="{{ $day }}" 
                                   {{ in_array($day, $selectedDays) ? 'checked' : '' }}>
                            <span>{{ $day }}</span>
                        </label>
                        @endforeach
                    </div>
                    <span class="error-message" id="days-error"></span>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="start_time">Start Time <span class="required">*</span></label>
                        <input type="time" id="start_time" name="start_time" class="form-control" 
                               value="{{ $doctor['start_time'] ?? '09:00' }}" required>
                        <span class="error-message" id="start-time-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="end_time">End Time <span class="required">*</span></label>
                        <input type="time" id="end_time" name="end_time" class="form-control" 
                               value="{{ $doctor['end_time'] ?? '17:00' }}" required>
                        <span class="error-message" id="end-time-error"></span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ isset($doctor) ? 'Update Doctor' : 'Add Doctor' }}
                    </button>
                    <a href="{{ url('doctors/index.php') }}" class="btn btn-secondary">
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
// Initialize live form validation
const doctorValidator = new FormValidator('doctorForm', {
    validateOnInput: true,
    validateOnBlur: true,
    showSuccessIcons: true,
    debounceDelay: 300
});

// Add custom validators
doctorValidator.addValidator('name', (value) => {
    if (value.length < 2) {
        return 'Name must be at least 2 characters long';
    }
    if (!/^[a-zA-Z\s.]+$/.test(value)) {
        return 'Name should only contain letters, spaces, and periods';
    }
    return true;
});

doctorValidator.addValidator('email', (value) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
        return 'Please enter a valid email address';
    }
    return true;
});

doctorValidator.addValidator('phone', (value) => {
    const phoneRegex = /^[0-9\-\+\(\)\s]{10,20}$/;
    if (!phoneRegex.test(value)) {
        return 'Please enter a valid phone number (10-20 digits)';
    }
    return true;
});

doctorValidator.addValidator('specialization', (value) => {
    if (value.length < 3) {
        return 'Specialization must be at least 3 characters long';
    }
    return true;
});

doctorValidator.addValidator('qualification', (value) => {
    if (value.length < 2) {
        return 'Qualification must be at least 2 characters long';
    }
    return true;
});

// Validate available days checkboxes
const checkboxGroup = document.querySelector('.checkbox-group');
if (checkboxGroup) {
    checkboxGroup.parentElement.setAttribute('data-validate-checkbox', 'true');
    
    const checkboxes = checkboxGroup.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            const errorEl = document.getElementById('days-error');
            
            if (anyChecked) {
                if (errorEl) errorEl.textContent = '';
            } else {
                if (errorEl) errorEl.textContent = 'Please select at least one available day';
            }
        });
    });
}

// Add validation hints
const nameEl = document.getElementById('name');
const emailEl = document.getElementById('email');
const phoneEl = document.getElementById('phone');
const specializationEl = document.getElementById('specialization');
const qualificationEl = document.getElementById('qualification');
const startTimeEl = document.getElementById('start_time');
const endTimeEl = document.getElementById('end_time');

if (nameEl) nameEl.setAttribute('title', 'Enter doctor\'s full name');
if (emailEl) emailEl.setAttribute('title', 'Enter a valid email address');
if (phoneEl) phoneEl.setAttribute('title', 'Enter phone number (10-20 digits)');
if (specializationEl) specializationEl.setAttribute('title', 'e.g., Cardiologist, Pediatrician');
if (qualificationEl) qualificationEl.setAttribute('title', 'e.g., MBBS, MD, PhD');

// Validate time range
doctorValidator.addValidator('start_time', (value) => {
    if (!value) return 'Start time is required';
    return true;
});

doctorValidator.addValidator('end_time', (value) => {
    if (!value) return 'End time is required';
    
    const startTime = startTimeEl ? startTimeEl.value : '';
    if (startTime && value <= startTime) {
        return 'End time must be after start time';
    }
    
    return true;
});

// Real-time validation when either time changes
if (startTimeEl) {
    startTimeEl.addEventListener('change', () => {
        doctorValidator.validateField('end_time');
    });
}

if (endTimeEl) {
    endTimeEl.addEventListener('change', () => {
        doctorValidator.validateField('end_time');
    });
}
</script>
@endsection
