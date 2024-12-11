function goToQuestion(questionNumber) {
    // Esconde todas as perguntas
    const questions = document.querySelectorAll('.question');
    questions.forEach(question => question.classList.remove('active'));

    // Mostra a próxima pergunta
    const nextQuestion = document.getElementById('question' + questionNumber);
    nextQuestion.classList.add('active');
}

function goToPreviousQuestion(questionNumber) {
    // Esconde todas as perguntas
    const questions = document.querySelectorAll('.question');
    questions.forEach(question => question.classList.remove('active'));

    // Mostra a pergunta anterior
    const previousQuestion = document.getElementById('question' + questionNumber);
    previousQuestion.classList.add('active');
}

function redirectToRecommendations() {
    let type = '';
    let volume = '';
    let frizz = '';

    // Pegando as respostas para determinar o tipo de cabelo
    if (document.querySelector('input[name="q1"]:checked')) {
        const q1Value = document.querySelector('input[name="q1"]:checked').value;
        if (q1Value === 'A') type = 'Liso';
        else if (q1Value === 'B') type = 'Ondulado';
        else if (q1Value === 'C') type = 'Cacheado';
        else if (q1Value === 'D') type = 'Crespo';
    }

    // Pergunta 2
    if (document.querySelector('input[name="q2"]:checked')) {
        const q2Value = document.querySelector('input[name="q2"]:checked').value;
        if (q2Value === '1A') type += '-1A';
        else if (q2Value === '1B') type += '-1B';
        else if (q2Value === '1C') type += '-1C';
    }

    // Pergunta 3
    if (document.querySelector('input[name="q3"]:checked')) {
        const q3Value = document.querySelector('input[name="q3"]:checked').value;
        if (q3Value === '2A') type += '-2A';
        else if (q3Value === '2B') type += '-2B';
        else if (q3Value === '2C') type += '-2C';
    }

    // Pergunta 4
    if (document.querySelector('input[name="q4"]:checked')) {
        const q4Value = document.querySelector('input[name="q4"]:checked').value;
        if (q4Value === '3A') type += '-3A';
        else if (q4Value === '3B') type += '-3B';
        else if (q4Value === '3C') type += '-3C';
    }

    // Pergunta 5
    if (document.querySelector('input[name="q5"]:checked')) {
        const q5Value = document.querySelector('input[name="q5"]:checked').value;
        if (q5Value === '4A') type += '-4A';
        else if (q5Value === '4B') type += '-4B';
        else if (q5Value === '4C') type += '-4C';
    }

    // Pergunta 6 (Volume)
    if (document.querySelector('input[name="q6"]:checked')) {
        volume = document.querySelector('input[name="q6"]:checked').value;
    }

    // Pergunta 7 (Frizz)
    if (document.querySelector('input[name="q7"]:checked')) {
        frizz = document.querySelector('input[name="q7"]:checked').value;
    }

    // Criar a URL com os parâmetros
    const params = new URLSearchParams();
    params.append('tipo', type);
    params.append('volume', volume);
    params.append('frizz', frizz);

    // Redirecionar para a página de recomendações
    window.location.href = `recomendacoes.html?${params.toString()}`;
}
