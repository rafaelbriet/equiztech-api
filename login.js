const formLogin = document.querySelector('#form-login');

formLogin.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = {
        usuario: {
            email: formLogin.elements.floatingInput.value,
            senha: formLogin.elements.floatingPassword.value,
        }
    };

    try {
        const response = await fetch('./api/autenticacao/login.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data)
        });
        const result = await response.json();

        if (result.erro != null) {
            document.querySelector('#form-invalid').classList.remove('d-none');
        } else {
            document.querySelector('#form-invalid').classList.add('d-none');
            sessionStorage.setItem('session_token', result.token);
            window.location.href = 'dashboard'
        }
    } catch (error) {
        console.error("Error:", error);
    }
});