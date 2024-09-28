const form = document.querySelector('form');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = {
        usuario: {
            email: form.elements.userEmail.value,
            senha: form.elements.userEmail.value + Date(),
            id_nivel_acesso: form.elements.userAccessLevel.value,
            id_dados_pessoais: form.elements.personalDataId.value,
            nome: form.elements.userName.value,
            sobrenome: form.elements.userSurname.value,
            data_nascimento: form.elements.userBirthday.value,
            biografia: form.elements.userBio.value,
            nome_foto: "",
        }
    };

    try {
        const token = document.cookie.split(';')[0].split('=')[1];
        const response = await fetch('http://localhost/equiztech/api/usuarios/?id=' + form.elements.userId.value, {
            method: 'PUT',
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
            const toastElement = document.querySelector('#form-success');
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastElement);
            toastBootstrap.show();
        }
    } catch (error) {
        console.error("Error:", error);
    }
});