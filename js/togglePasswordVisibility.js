document.addEventListener('DOMContentLoaded', function() {
    function togglePasswordVisibility(field, icon) {
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // index.php
    const eyeLogin = document.getElementById('eyeLogin');
    const loginPassword = document.getElementById('password');
    
    if (eyeLogin && loginPassword) {
        eyeLogin.addEventListener('click', function() {
            togglePasswordVisibility(loginPassword, eyeLogin);
        });
    }

    // register.php
    const eyeRegister = document.getElementById('eyeRegister');
    const eyeRegisterConfirm = document.getElementById('eyeRegisterConfirm');
    const registerPassword = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm-password');
    
    if (eyeRegister && registerPassword) {
        eyeRegister.addEventListener('click', function() {
            togglePasswordVisibility(registerPassword, eyeRegister);
        });
    }
    
    if (eyeRegisterConfirm && confirmPassword) {
        eyeRegisterConfirm.addEventListener('click', function() {
            togglePasswordVisibility(confirmPassword, eyeRegisterConfirm);
        });
    }
});