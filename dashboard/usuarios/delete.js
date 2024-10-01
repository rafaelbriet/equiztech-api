const form = document.querySelector('form');

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    try {
        const token = Cookies.get('TOKEN');
        const response = await fetch(CONFIG.base_url + '/api/usuarios/?id=' + form.elements.userId.value, {
            method: 'DELETE',
            headers: {
                "Content-Type": "application/json",
                'Authorization': token,
            },
        });
        const result = await response.json();

        if (result.erro != null) {
            document.querySelector('#categoryName-duplicate').classList.remove('d-none');
        } else {
            document.querySelector('#categoryName-duplicate').classList.add('d-none');
            const toastElement = document.querySelector('#form-success');
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastElement);
            toastBootstrap.show();
            window.location.href = '../';
        }
    } catch (error) {
        console.error("Error:", error);
    }
});