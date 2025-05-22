<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Ocultar elementos no deseados
    document.querySelectorAll('aside, nav').forEach(el => {
        el.style.display = 'none';
    });
    
    // 2. Configuración inicial del quiz
    const quizContainer = document.querySelector('[data-quiz-id]');
    const questions = document.querySelectorAll('.question-container');
    const nextBtn = document.getElementById('next-btn');
    const prevBtn = document.getElementById('prev-btn');
    const submitBtn = document.getElementById('submit-btn');
    const progressBar = document.getElementById('progress-bar');
    const currentQuestionNum = document.getElementById('current-question-num');
    const totalQuestions = questions.length;
    let currentQuestionIndex = 0;

    // 3. Función para mostrar preguntas
    function showQuestion(index) {
        questions.forEach((q, i) => {
            q.classList.toggle('d-none', i !== index);
        });

        currentQuestionNum.textContent = index + 1;
        progressBar.style.width = `${((index + 1) / totalQuestions) * 100}%`;

        prevBtn.disabled = index === 0;
        nextBtn.classList.toggle('d-none', index === totalQuestions - 1);
        submitBtn.classList.toggle('d-none', index !== totalQuestions - 1);
    }

    // 4. Navegación entre preguntas
    nextBtn.addEventListener('click', () => {
        if (currentQuestionIndex < totalQuestions - 1) {
            currentQuestionIndex++;
            showQuestion(currentQuestionIndex);
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentQuestionIndex > 0) {
            currentQuestionIndex--;
            showQuestion(currentQuestionIndex);
        }
    });

    // 5. Selección de respuestas
    document.querySelectorAll('.opcion').forEach(button => {
        button.addEventListener('click', function() {
            const questionId = this.dataset.questionId;
            document.querySelectorAll(`[data-question-id="${questionId}"]`).forEach(btn => {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-outline-dark');
            });
            
            this.classList.add('active', 'btn-primary');
            this.classList.remove('btn-outline-dark');
            this.querySelector('input[type="radio"]').checked = true;
        });
    });

    // 6. Temporizador
    const timeDisplay = document.getElementById('time-remaining');
    let timeLeft = parseInt(timeDisplay.textContent);
    let quizTimeSpent = 0;
    
    const timer = setInterval(() => {
        timeLeft--;
        quizTimeSpent++;
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timeDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeLeft <= 0) {
            clearInterval(timer);
            submitQuiz();
        }
    }, 1000);

    // 7. Función para enviar el quiz
    async function submitQuiz() {
        const quizId = quizContainer.dataset.quizId;
        const answers = {};

        // Recopilar respuestas
        questions.forEach(container => {
            const questionId = container.dataset.questionId;
            const selectedOption = container.querySelector('input[type="radio"]:checked');
            const textAnswer = container.querySelector('textarea');
            
            if (selectedOption) {
                answers[questionId] = selectedOption.value;
            } else if (textAnswer) {
                answers[questionId] = textAnswer.value.trim();
            }
        });

        try {
            // Enviar datos al servidor
            const response = await fetch(`/quizzes/${quizId}/submit`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    answers: answers,
                    time_spent: quizTimeSpent
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Error al enviar el quiz');
            }

            // Mostrar resultados
            showResults(data);
            
        } catch (error) {
            console.error('Error:', error);
            alert('Error al guardar los resultados: ' + error.message);
        }
    }

    // 8. Mostrar resultados
    function showResults(data) {
        const modal = document.getElementById('results-modal');
        const modalContent = document.getElementById('results-content');
        let result = data.data;
        
        let html = `
            <div class="mb-3">
                <h5>Resultados del Quiz</h5>
                <p><strong>Puntaje:</strong> ${result.score}/${result.total_questions}</p>
                <p><strong>Correctas:</strong> ${result.correct_answers}</p>
                <p><strong>Incorrectas:</strong> ${result.incorrect_answers}</p>
            </div>
        `;

        if (data.show_details && data.details) {
            html += `<div class="mt-3"><h6>Detalle de respuestas:</h6>`;
            data.details.forEach((detail, index) => {
                html += `
                <div class="mb-2 p-2 ${detail.is_correct ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10'}">
                    <p class="mb-1"><strong>Pregunta ${index + 1}:</strong> ${detail.question}</p>
                    <p class="mb-1">Tu respuesta: ${detail.user_answer || 'No respondida'}</p>
                    ${!detail.is_correct ? `<p class="mb-0">Respuesta correcta: ${detail.correct_answer}</p>` : ''}
                </div>
                `;
            });
            html += `</div>`;
        }

        modalContent.innerHTML = html;
        const modalInstance = new bootstrap.Modal(modal);
        modalInstance.show();

        // Restaurar vista al cerrar modal
        modal.addEventListener('hidden.bs.modal', function() {
            document.querySelectorAll('aside, nav').forEach(el => {
                el.style.display = '';
            });
        });
    }

    // 9. Configurar botón de enviar
    submitBtn.addEventListener('click', submitQuiz);

    // Iniciar la primera pregunta
    showQuestion(currentQuestionIndex);
});
</script>