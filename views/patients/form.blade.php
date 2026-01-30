@extends('layout')

@section('title', isset($patient) ? 'Edit Patient' : 'Add New Patient')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>
            <i class="fas fa-user-injured"></i> 
            {{ isset($patient) ? 'Edit Patient' : 'Add New Patient' }}
        </h1>
        <a href="{{ url('patients/index.php') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Patients
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ isset($patient) ? url('patients/edit.php?id=' . $patient['id']) : url('patients/create.php') }}" class="form" id="patientForm">
                <input type="hidden" name="csrf_token" value="{{ $csrf_token }}">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" 
                               value="{{ $patient['name'] ?? '' }}" required>
                        <span class="error-message" id="name-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="{{ $patient['email'] ?? '' }}" required>
                        <span class="error-message" id="email-error"></span>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number <span class="required">*</span></label>
                        <input type="tel" id="phone" name="phone" class="form-control" 
                               value="{{ $patient['phone'] ?? '' }}" required>
                        <span class="error-message" id="phone-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth <span class="required">*</span></label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" 
                               value="{{ $patient['date_of_birth'] ?? '' }}" required>
                        <span class="error-message" id="dob-error"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="gender">Gender <span class="required">*</span></label>
                    <select id="gender" name="gender" class="form-control" required>
                        <option value="">Select Gender</option>
                        <option value="male" {{ (isset($patient) && $patient['gender'] === 'male') ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ (isset($patient) && $patient['gender'] === 'female') ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ (isset($patient) && $patient['gender'] === 'other') ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" class="form-control" rows="3">{{ $patient['address'] ?? '' }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ isset($patient) ? 'Update Patient' : 'Add Patient' }}
                    </button>
                    <a href="{{ url('patients/index.php') }}" class="btn btn-secondary">
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
const patientValidator = new FormValidator('patientForm', {
    validateOnInput: true,
    validateOnBlur: true,
    showSuccessIcons: true,
    debounceDelay: 300
});

// Add custom validators
patientValidator.addValidator('name', (value) => {
    if (value.length < 2) {
        return 'Name must be at least 2 characters long';
    }
    if (!/^[a-zA-Z\s]+$/.test(value)) {
        return 'Name should only contain letters and spaces';
    }
    return true;
});

patientValidator.addValidator('email', (value) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
        return 'Please enter a valid email address';
    }
    return true;
});

patientValidator.addValidator('phone', (value) => {
    const phoneRegex = /^[0-9\-\+\(\)\s]{10,20}$/;
    if (!phoneRegex.test(value)) {
        return 'Please enter a valid phone number (10-20 digits)';
    }
    return true;
});

patientValidator.addValidator('date_of_birth', (value) => {
    const dob = new Date(value);
    const today = new Date();
    const age = today.getFullYear() - dob.getFullYear();
    
    if (dob > today) {
        return 'Date of birth cannot be in the future';
    }
    if (age > 150) {
        return 'Please enter a valid date of birth';
    }
    return true;
});

// Add validation hints
document.getElementById('name').setAttribute('title', 'Enter full name (letters and spaces only)');
document.getElementById('email').setAttribute('title', 'Enter a valid email address');
document.getElementById('phone').setAttribute('title', 'Enter phone number (10-20 digits)');
document.getElementById('date_of_birth').setAttribute('title', 'Select date of birth');
</script>
@endsection
