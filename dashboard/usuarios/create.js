const form = document.querySelector('form');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = {
                    dados_pessoais: {
                    nome: form.elements.userName.value,
                    sobrenome: form.elements.userSurname.value,
                    data_nascimento: form.elements.userBirthday.value,
                    biografia: form.elements.userBio.value,
                    nome_foto: ""
                },
                    usuario: {
                    email: form.elements.userEmail.value,
                    senha: form.elements.userEmail.value + Date(),
                    termos_condicoes: 1,
                    id_nivel_acesso: form.elements.userAccessLevel.value
                }
    };

    try {
        const token = Cookies.get('TOKEN');
        const response = await fetch(CONFIG.base_url + '/api/usuarios/', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                'Authorization': token,
            },
            body: JSON.stringify(data)
        });
        const result = await response.json();

        if (result.erro != null) {
            document.querySelector('#userEmail-duplicate').classList.remove('d-none');
        } else {
            document.querySelector('#userEmail-duplicate').classList.add('d-none');
            form.reset();
            const toastElement = document.querySelector('#form-success');
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastElement);
            toastBootstrap.show();
        }
    } catch (error) {
        console.error("Error:", error);
    }
});