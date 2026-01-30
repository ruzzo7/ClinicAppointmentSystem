/**
 * Live Form Validation Library
 * Provides real-time validation for form inputs with visual feedback
 */

class FormValidator {
    constructor(formId, options = {}) {
        this.form = document.getElementById(formId);
        if (!this.form) {
            console.error(`Form with id "${formId}" not found`);
            return;
        }

        this.options = {
            validateOnInput: true,
            validateOnBlur: true,
            showSuccessIcons: true,
            debounceDelay: 300,
            ...options
        };

        this.validators = {};
        this.debounceTimers = {};
        this.init();
    }

    init() {
        // Add validation icons to form groups
        this.addValidationIcons();

        // Attach event listeners
        this.attachEventListeners();
    }

    addValidationIcons() {
        const formGroups = this.form.querySelectorAll('.form-group');
        formGroups.forEach(group => {
            const input = group.querySelector('.form-control');
            if (input && !group.querySelector('.validation-icon')) {
                group.classList.add('has-validation');

                // Add success icon
                const successIcon = document.createElement('i');
                successIcon.className = 'fas fa-check-circle validation-icon icon-valid';
                group.appendChild(successIcon);

                // Add error icon
                const errorIcon = document.createElement('i');
                errorIcon.className = 'fas fa-exclamation-circle validation-icon icon-invalid';
                group.appendChild(errorIcon);
            }
        });
    }

    attachEventListeners() {
        const inputs = this.form.querySelectorAll('.form-control');

        inputs.forEach(input => {
            // Validate on input (with debounce)
            if (this.options.validateOnInput) {
                input.addEventListener('input', (e) => {
                    this.debounceValidate(e.target);
                });
            }

            // Validate on blur
            if (this.options.validateOnBlur) {
                input.addEventListener('blur', (e) => {
                    this.validateField(e.target);
                });
            }

            // Clear validation on focus
            input.addEventListener('focus', (e) => {
                this.clearFieldValidation(e.target);
            });
        });

        // Validate on form submit
        this.form.addEventListener('submit', (e) => {
            if (!this.validateForm()) {
                e.preventDefault();
                // Scroll to first error
                const firstError = this.form.querySelector('.form-control.invalid, .form-control.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        });
    }

    debounceValidate(field) {
        const fieldName = field.name || field.id;

        // Clear existing timer
        if (this.debounceTimers[fieldName]) {
            clearTimeout(this.debounceTimers[fieldName]);
        }

        // Set new timer
        this.debounceTimers[fieldName] = setTimeout(() => {
            this.validateField(field);
        }, this.options.debounceDelay);
    }

    validateField(field) {
        if (!field) return true; // Safety check

        const fieldName = field.name || field.id;
        const value = field.value.trim();
        const formGroup = field.closest('.form-group');

        if (!formGroup) return true; // No form group, skip validation

        const errorElement = formGroup.querySelector('.error-message, .validation-message');

        // Get custom validator if exists
        const customValidator = this.validators[fieldName];

        let isValid = true;
        let errorMessage = '';

        // Required field validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'This field is required';
        }
        // Email validation
        else if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid email address';
            }
        }
        // Phone validation
        else if (field.type === 'tel' && value) {
            const phoneRegex = /^[0-9\-\+\(\)\s]{10,20}$/;
            if (!phoneRegex.test(value)) {
                isValid = false;
                errorMessage = 'Please enter a valid phone number (10-20 digits)';
            }
        }
        // URL validation
        else if (field.type === 'url' && value) {
            try {
                new URL(value);
            } catch {
                isValid = false;
                errorMessage = 'Please enter a valid URL';
            }
        }
        // Number validation
        else if (field.type === 'number' && value) {
            const min = field.getAttribute('min');
            const max = field.getAttribute('max');
            const numValue = parseFloat(value);

            if (min !== null && numValue < parseFloat(min)) {
                isValid = false;
                errorMessage = `Value must be at least ${min}`;
            } else if (max !== null && numValue > parseFloat(max)) {
                isValid = false;
                errorMessage = `Value must be at most ${max}`;
            }
        }
        // Date validation
        else if (field.type === 'date' && value) {
            const dateValue = new Date(value);
            const min = field.getAttribute('min');
            const max = field.getAttribute('max');

            if (min && dateValue < new Date(min)) {
                isValid = false;
                errorMessage = 'Date is too early';
            } else if (max && dateValue > new Date(max)) {
                isValid = false;
                errorMessage = 'Date is too late';
            }
        }
        // Min/Max length validation
        else if (value) {
            const minLength = field.getAttribute('minlength');
            const maxLength = field.getAttribute('maxlength');

            if (minLength && value.length < parseInt(minLength)) {
                isValid = false;
                errorMessage = `Must be at least ${minLength} characters`;
            } else if (maxLength && value.length > parseInt(maxLength)) {
                isValid = false;
                errorMessage = `Must be at most ${maxLength} characters`;
            }
        }
        // Pattern validation
        if (isValid && field.hasAttribute('pattern') && value) {
            const pattern = new RegExp(field.getAttribute('pattern'));
            if (!pattern.test(value)) {
                isValid = false;
                errorMessage = field.getAttribute('title') || 'Invalid format';
            }
        }

        // Custom validator
        if (isValid && customValidator && value) {
            const customResult = customValidator(value, field);
            if (customResult !== true) {
                isValid = false;
                errorMessage = customResult;
            }
        }

        // Update UI
        this.updateFieldUI(field, formGroup, isValid, errorMessage, errorElement);

        return isValid;
    }

    updateFieldUI(field, formGroup, isValid, errorMessage, errorElement) {
        if (!field || !formGroup) return; // Safety check

        // Remove all validation classes
        field.classList.remove('valid', 'invalid', 'error', 'success');
        formGroup.classList.remove('valid', 'invalid', 'error', 'success');

        if (field.value.trim()) {
            if (isValid) {
                field.classList.add('valid');
                formGroup.classList.add('valid');
                if (errorElement) {
                    errorElement.textContent = '';
                }
            } else {
                field.classList.add('invalid');
                formGroup.classList.add('invalid');
                if (errorElement) {
                    errorElement.textContent = errorMessage;
                }
            }
        } else {
            // Clear validation for empty fields (unless required)
            if (errorElement) {
                errorElement.textContent = '';
            }
        }
    }

    clearFieldValidation(field) {
        if (!field) return; // Safety check

        const formGroup = field.closest('.form-group');
        if (!formGroup) return; // Safety check

        const errorElement = formGroup.querySelector('.error-message, .validation-message');

        // Only clear if not already validated
        if (!field.classList.contains('valid')) {
            field.classList.remove('invalid', 'error');
            formGroup.classList.remove('invalid', 'error');
            if (errorElement) {
                errorElement.textContent = '';
            }
        }
    }

    validateForm() {
        let isValid = true;
        const inputs = this.form.querySelectorAll('.form-control');

        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });

        // Validate checkboxes (e.g., available days)
        const checkboxGroups = this.form.querySelectorAll('[data-validate-checkbox]');
        checkboxGroups.forEach(group => {
            const checkboxes = group.querySelectorAll('input[type="checkbox"]');
            const checked = Array.from(checkboxes).some(cb => cb.checked);
            const errorElement = group.querySelector('.error-message, .validation-message');

            if (!checked) {
                isValid = false;
                if (errorElement) {
                    errorElement.textContent = 'Please select at least one option';
                }
            } else {
                if (errorElement) {
                    errorElement.textContent = '';
                }
            }
        });

        return isValid;
    }

    addValidator(fieldName, validatorFn) {
        this.validators[fieldName] = validatorFn;
    }

    removeValidator(fieldName) {
        delete this.validators[fieldName];
    }

    reset() {
        const inputs = this.form.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.classList.remove('valid', 'invalid', 'error', 'success');
            const formGroup = input.closest('.form-group');
            if (formGroup) {
                formGroup.classList.remove('valid', 'invalid', 'error', 'success');
            }
        });

        const errorElements = this.form.querySelectorAll('.error-message, .validation-message');
        errorElements.forEach(el => {
            el.textContent = '';
        });
    }
}

// Common validation functions
const ValidationRules = {
    minLength: (min) => (value) => {
        return value.length >= min || `Must be at least ${min} characters`;
    },

    maxLength: (max) => (value) => {
        return value.length <= max || `Must be at most ${max} characters`;
    },

    alphaNumeric: () => (value) => {
        return /^[a-zA-Z0-9]+$/.test(value) || 'Only letters and numbers allowed';
    },

    alpha: () => (value) => {
        return /^[a-zA-Z\s]+$/.test(value) || 'Only letters allowed';
    },

    numeric: () => (value) => {
        return /^[0-9]+$/.test(value) || 'Only numbers allowed';
    },

    email: () => (value) => {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) || 'Invalid email format';
    },

    phone: () => (value) => {
        return /^[0-9\-\+\(\)\s]{10,20}$/.test(value) || 'Invalid phone number';
    },

    futureDate: () => (value) => {
        const date = new Date(value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        return date >= today || 'Date must be today or in the future';
    },

    pastDate: () => (value) => {
        const date = new Date(value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        return date <= today || 'Date must be today or in the past';
    },

    match: (fieldId) => (value) => {
        const matchField = document.getElementById(fieldId);
        return value === matchField.value || 'Fields do not match';
    }
};
