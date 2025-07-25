const form = document.querySelector('form');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = {
        categoria: {
            nome: form.elements.categoryName.value,
        }
    };

    try {
        const token = Cookies.get('TOKEN');
        const response = await fetch(CONFIG.base_url + '/api/categorias/', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                'Authorization': token,
            },
            body: JSON.stringify(data)
        });
        const result = await response.json();

        if (result.erro != null) {
            document.querySelector('#categoryName-duplicate').classList.remove('d-none');
        } else {
            document.querySelector('#categoryName-duplicate').classList.add('d-none');
            form.reset();
            const toastElement = document.querySelector('#form-success');
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastElement);
            toastBootstrap.show();
        }
    } catch (error) {
        console.error("Error:", error);
    }
});