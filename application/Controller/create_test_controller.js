$(document).ready(function() {
    $("#create-questions").click(function() {
        const questionCount = $("#question-count").val();
        let questionsHtml = "";

        for (let i = 0; i < questionCount; i++) {
            questionsHtml += `
                <div class="question-div">
                    <label for="question-text-${i}">Питання ${i + 1}:</label>
                    <input type="text" name="questions[${i}][text]" id="question-text-${i}" required>
                    <label for="answers-count-${i}">Введіть кількість відповідей:</label>
                    <input type="number" name="questions[${i}][answers_count]" id="answers-count-${i}" class="answers-count" required>
                    <div class="answers-container" id="answers-container-${i}"></div>
                </div>
            `;
        }

        $("#questions-container").html(questionsHtml);

        $(".answers-count").change(function() {
            const questionIndex = $(this).attr("id").split("-")[2];
            const answersCount = $(this).val();
            let answersHtml = "";

            for (let i = 0; i < answersCount; i++) {
                answersHtml += `
                    <div>
                        <label for="answer-text-${questionIndex}-${i}">Відповідь ${i + 1}:</label>
                        <input type="text" name="questions[${questionIndex}][answers][${i}][text]" id="answer-text-${questionIndex}-${i}" required>
                        <label for="answer-correct-${questionIndex}-${i}">Правильна:</label>
                        <input type="checkbox" name="questions[${questionIndex}][answers][${i}][is_correct]" id="answer-correct-${questionIndex}-${i}" value="1">
                    </div>
                `;
            }

            $("#answers-container-" + questionIndex).html(answersHtml);
        });
    });

    $("#test-form").submit(function(e) {
        e.preventDefault();
        const formData = $(this).serializeArray();

        // Convert form data to the required format
        const data = {
            test_name: formData.find((item) => item.name === "test_name").value,
            test_time: formData.find((item) => item.name === "test_time").value,
            questions: []
        };

        formData.forEach((item) => {
            const match = item.name.match(/questions\[(\d+)\]\[(\w+)\](?:\[(\d+)\]\[(\w+)\])?/);
            if (match) {
                const [_, questionIndex, field, answerIndex, subField] = match;
                if (!data.questions[questionIndex]) data.questions[questionIndex] = { answers: [] };
                if (field === "text") data.questions[questionIndex].text = item.value;
                if (field === "answers") {
                    if (!data.questions[questionIndex].answers[answerIndex]) data.questions[questionIndex].answers[answerIndex] = {};
                    if (subField === "text") data.questions[questionIndex].answers[answerIndex].text = item.value;
                    if (subField === "is_correct") data.questions[questionIndex].answers[answerIndex].is_correct = item.value === "1";
                }
            }
        });

        // Send data to the server
        $.post('../Model/create_test.php', JSON.stringify(data), function(response) {
            alert(response); // Show success or error message
            if (response === "Тест успішно створено!") {
                window.location.href = "main_admin.html"; // Redirect to login page
            }
        });
    });

});
