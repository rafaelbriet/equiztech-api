const form = document.querySelector('form');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = {
        usuario: {
            email: form.elements.userEmail.value,
        }
    };

    try {
        const response = await fetch(CONFIG.base_url + '/api/autenticacao/esqueci-minha-senha.php', {
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
            window.localStorage.setItem('reset_link', result.link);
            window.location.href = CONFIG.base_url + '/dashboard/esqueci-minha-senha/etapa-2.php';
        }
    } catch (error) {
        console.error("Error:", error);
    }
});