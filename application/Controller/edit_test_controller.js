$(document).ready(function() {
    $('#load-test').on('click', function() {
        let selectedTestId = $('select[name="test_id"]').val();

        $.ajax({
            url: '../Model/load_test_data.php', // Змініть на адресу вашого сервера, який повертає дані тесту
            type: 'GET',
            data: { test_id: selectedTestId },
            dataType: 'json',
            success: function(response) {
                $('#test-name').val(response.Name_test);
                $('#question-count').val(response.questions.length);

                let questionsHtml = "";
                for (let i = 0; i < response.questions.length; i++) {
                    let question = response.questions[i];

                    questionsHtml += `<div class="question-div">
                        <label for="question-text-${i}">Питання ${i + 1}:</label>
                        <input type="text" name="questions[${i}][text]" id="question-text-${i}" value="${question.text}" required>
                        <div class="answers-div">`;

                    for (let j = 0; j < question.answers.length; j++) {
                        let answer = question.answers[j];
                        questionsHtml += `
                            <label for="answer-text-${i}-${j}">Відповідь ${j + 1}:</label>
                            <input type="text" name="questions[${i}][answers][${j}]" id="answer-text-${i}-${j}" value="${answer.text}" required>`;
                    }

                    questionsHtml += `</div></div>`;
                }

                $('#questions-container').html(questionsHtml);
                $('#test-time').val(response.test_time);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Обробка помилок
                console.error("Помилка завантаження даних тесту: ", textStatus, errorThrown);
            }
        });
    });
});
