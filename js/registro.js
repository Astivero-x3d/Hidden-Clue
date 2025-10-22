// Espera a que todo el DOM esté cargado antes de ejecutar cualquier script
document.addEventListener('DOMContentLoaded', function() {
    // Obtenemos el botón del menú y los enlaces del header
    const menuToggle = document.getElementById('menu-toggle');
    const headerLinks = document.getElementById('header-links');
    
    // Verifica que existan los elementos
    if (menuToggle && headerLinks) {
        menuToggle.addEventListener('click', function() {
            headerLinks.classList.toggle('show'); // Muestra u oculta el menú
            document.body.classList.toggle('no-scroll'); // Bloquea el scroll cuando está abierto
        });
    }
    
    // Validación del formulario de registro
    const registerForm = document.getElementById('registerForm'); // Formulario principal
    if (registerForm) {
        const passwordInput = document.getElementById('password'); // Campo contraseña
        const confirmPasswordInput = document.getElementById('confirm_password'); // Campo confirmar contraseña
        const passwordError = document.getElementById('passwordError'); // Mensaje error contraseña
        const confirmPasswordError = document.getElementById('confirmPasswordError'); // Mensaje error confirmación
        
        // Validar coincidencia de contraseñas
        if (confirmPasswordInput && passwordInput) {
            confirmPasswordInput.addEventListener('input', function() {
                if (this.value !== passwordInput.value) { // Si no coinciden
                    confirmPasswordError.textContent = 'Las contraseñas no coinciden';
                    confirmPasswordError.classList.add('show'); // Muestra el mensaje de error
                } else {
                    confirmPasswordError.classList.remove('show'); // Oculta el mensaje si coinciden
                }
            });
        }
        
        // Validación al enviar el formulario
        registerForm.addEventListener('submit', function(e) {
            let isValid = true; // Variable que controla si el formulario es válido
            
            // Validar que las contraseñas coincidan
            if (passwordInput && confirmPasswordInput && 
                passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordError.textContent = 'Las contraseñas no coinciden';
                confirmPasswordError.classList.add('show');
                isValid = false;
            }
            
            // Validar fortaleza mínima de contraseña
            if (passwordInput && passwordInput.value.length < 6) {
                passwordError.textContent = 'La contraseña debe tener al menos 6 caracteres';
                passwordError.classList.add('show');
                isValid = false;
            }
            
            // Si hay algún error, prevenimos que se envíe el formulario
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});