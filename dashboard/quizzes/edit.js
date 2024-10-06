const form = document.querySelector('form');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = {
        pergunta: {
            texto_pergunta: form.elements.quizQuestion.value,
            id_categoria: form.elements.quizCategoria.value,
            explicacao: form.elements.quizDescription.value,
            ativo: form.elements.quizStatus.value,
            respostas: [
                {
                    id: form.elements.quizAnswerId1.value,
                    texto_alternativa: form.elements.quizAnswer1.value,
                    correta: form.elements.quizAnswerCorrect1.checked,
                },
                {
                    id: form.elements.quizAnswerId2.value,
                    texto_alternativa: form.elements.quizAnswer2.value,
                    correta: form.elements.quizAnswerCorrect2.checked,
                },
                {
                    id: form.elements.quizAnswerId3.value,
                    texto_alternativa: form.elements.quizAnswer3.value,
                    correta: form.elements.quizAnswerCorrect3.checked,
                },
                {
                    id: form.elements.quizAnswerId4.value,
                    texto_alternativa: form.elements.quizAnswer4.value,
                    correta: form.elements.quizAnswerCorrect4.checked,
                },
            ]
        }
    };

    try {
        const token = Cookies.get('TOKEN');
        const response = await fetch(CONFIG.base_url + '/api/perguntas/?id=' + form.elements.quizId.value, {
            method: 'PUT',
            headers: {
                "Content-Type": "application/json",
                'Authorization': token,
            },
            body: JSON.stringify(data)
        });
        const result = await response.json();

        if (result.erro != null) {
            console.error("Error:", result.erro);
            // document.querySelector('#userEmail-duplicate').classList.remove('d-none');
        } else {
            // document.querySelector('#userEmail-duplicate').classList.add('d-none');
            const toastElement = document.querySelector('#form-success');
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastElement);
            toastBootstrap.show();
        }
    } catch (error) {
        console.error("Error:", error);
    }
});