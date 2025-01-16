document.addEventListener('DOMContentLoaded', function() {
    // Session timeout after 30 minutes
    const SESSION_TIMEOUT = 30 * 60 * 1000; // 30 minutes in milliseconds
    let sessionTimer;

    function startSessionTimer() {
        sessionTimer = setTimeout(logout, SESSION_TIMEOUT);
    }

    function resetSessionTimer() {
        clearTimeout(sessionTimer);
        startSessionTimer();
    }

    function checkSession() {
        fetch('auth.php?action=check_session')
            .then(response => response.json())
            .then(data => {
                if (!data.logged_in) {
                    window.location.href = 'index.html';
                } else {
                    const usernameElement = document.getElementById('username');
                    if (usernameElement) {
                        usernameElement.textContent = data.username;
                    }
                }
            });
    }

    // Handle Login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetch('auth.php?action=login', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'dashboard.html';
                } else {
                    alert(data.message || 'Login failed');
                }
            });
        });
    }

    // Handle Registration
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (this.password.value !== this.confirm_password.value) {
                alert('Passwords do not match!');
                return;
            }
            fetch('auth.php?action=register', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Directly redirect to dashboard after successful registration
                    window.location.href = 'dashboard.html';
                } else {
                    alert(data.message || 'Registration failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during registration');
            });
        });
    }

    // Handle Logout
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', logout);
    }

    function logout() {
        fetch('auth.php?action=logout')
            .then(response => response.json())
            .then(() => {
                window.location.href = 'index.html';
            });
    }

    // Reset timer on user activity
    document.addEventListener('mousemove', resetSessionTimer);
    document.addEventListener('keypress', resetSessionTimer);
    document.addEventListener('click', resetSessionTimer);

    // Check session on protected pages
    if (window.location.pathname.endsWith('dashboard.html')) {
        checkSession();
        setInterval(checkSession, 60000); // Check session every minute
        startSessionTimer();
    }
});
