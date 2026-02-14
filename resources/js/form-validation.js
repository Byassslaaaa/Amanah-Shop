/**
 * Form Validation Helper
 * Client-side validation with real-time feedback
 */

class FormValidator {
    constructor(formElement) {
        this.form = formElement;
        this.errors = {};
        this.init();
    }

    init() {
        const inputs = this.form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            // Real-time validation on blur
            input.addEventListener('blur', () => this.validateField(input));

            // Clear error on input
            input.addEventListener('input', () => this.clearError(input));
        });

        // Form submission validation
        this.form.addEventListener('submit', (e) => {
            if (!this.validateForm()) {
                e.preventDefault();
                this.focusFirstError();
            }
        });
    }

    validateField(input) {
        const value = input.value.trim();
        const name = input.name;
        let error = null;

        // Required validation
        if (input.hasAttribute('required') && !value) {
            error = this.getRequiredMessage(input);
        }

        // Email validation
        if (input.type === 'email' && value && !this.isValidEmail(value)) {
            error = 'Format email tidak valid';
        }

        // Number validation
        if (input.type === 'number' && value) {
            const min = input.getAttribute('min');
            const max = input.getAttribute('max');

            if (min && parseFloat(value) < parseFloat(min)) {
                error = `Nilai minimal adalah ${min}`;
            }
            if (max && parseFloat(value) > parseFloat(max)) {
                error = `Nilai maksimal adalah ${max}`;
            }
        }

        // Min length validation
        const minLength = input.getAttribute('minlength');
        if (minLength && value.length > 0 && value.length < parseInt(minLength)) {
            error = `Minimal ${minLength} karakter`;
        }

        // Max length validation
        const maxLength = input.getAttribute('maxlength');
        if (maxLength && value.length > parseInt(maxLength)) {
            error = `Maksimal ${maxLength} karakter`;
        }

        // Password confirmation
        if (input.name === 'password_confirmation') {
            const password = this.form.querySelector('input[name="password"]');
            if (password && value !== password.value) {
                error = 'Konfirmasi password tidak cocok';
            }
        }

        // Display error
        if (error) {
            this.showError(input, error);
            this.errors[name] = error;
            return false;
        } else {
            this.clearError(input);
            delete this.errors[name];
            return true;
        }
    }

    validateForm() {
        const inputs = this.form.querySelectorAll('input[required], textarea[required], select[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    showError(input, message) {
        // Remove existing error
        this.clearError(input);

        // Add error class
        input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
        input.classList.remove('border-gray-300');
        input.setAttribute('aria-invalid', 'true');

        // Create error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-red-500 text-sm mt-1 error-message';
        errorDiv.setAttribute('role', 'alert');
        errorDiv.textContent = message;

        input.parentNode.appendChild(errorDiv);
    }

    clearError(input) {
        // Remove error classes
        input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
        input.classList.add('border-gray-300');
        input.removeAttribute('aria-invalid');

        // Remove error message
        const errorMessage = input.parentNode.querySelector('.error-message');
        if (errorMessage) {
            errorMessage.remove();
        }

        // Remove from errors object
        delete this.errors[input.name];
    }

    focusFirstError() {
        const firstError = this.form.querySelector('[aria-invalid="true"]');
        if (firstError) {
            firstError.focus();
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    getRequiredMessage(input) {
        const label = this.form.querySelector(`label[for="${input.id}"]`);
        const fieldName = label ? label.textContent : input.name;
        return `${fieldName} harus diisi`;
    }

    isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
}

// Auto-initialize form validation on forms with data-validate attribute
document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        new FormValidator(form);
    });
});

export default FormValidator;
