const form = document.querySelector('form');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const params = new URLSearchParams(document.location.search)
    const data = {
        id: params.get('id'),
        senha: form.elements.userPassword.value,
    };   
    
    try {
        const response = await fetch(CONFIG.base_url + '/api/usuarios/redefinir-senha.php', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data)
        });
        const result = await response.text();

        // TODO: Enviar email com a senha temporária por email.
        console.log(result);
        
        if (result.erro != null) {
            document.querySelector('#form-error').classList.remove('d-none');
        } else {
            window.location.href = CONFIG.base_url + '/dashboard/esqueci-minha-senha/etapa-4.php';
        }
    } catch (error) {
        console.error("Error:", error);
    }
});