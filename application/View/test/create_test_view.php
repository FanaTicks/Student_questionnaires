<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Створення тесту</title>
    <link rel="stylesheet" href="../../../CSS/style_create_question.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../../Controller/test/create_test_controller.php"></script>
</head>
<body>
<div class="register-container">
    <h1>Створення тесту</h1>
    <form id="test-form" method="post">
        <label for="test-name">Назва тесту:</label>
        <input type="text" name="test_name" id="test-name" required>
        <label for="question-count">Кількість питань:</label>
        <input type="number" name="question_count" id="question-count" required>
        <button type="button" id="create-questions">Створити</button>
        <div id="questions-container"></div>
        <label for="test-time">Час на тест:</label>
        <input type="text" name="test_time" id="test-time" required>
        <button type="submit" id="save-test">Зберегти</button>
    </form>
    <form action="../main/main_admin.php" method="post">
        <button type="submit">Головне меню</button>
    </form>
</div>
</body>
</html>
<script>
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
                test_name: formData.find(item => item.name === "test_name").value,
                test_time: formData.find(item => item.name === "test_time").value,
                questions: [],
                action: 'create_test'
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
        /*    $.post('../../Model/test/create_test_model.php', JSON.stringify(data), function(response) {
                alert(response); // Show success or error message
                if (response === "Тест успішно створено!") {
                    window.location.href = "main_admin.php"; // Redirect to login page
                }
            });*/

            $.ajax({
                url: '../../Controller/test/create_test_controller.php', // URL, куди надсилається запит
                type: 'POST', // Метод запиту
                contentType: 'application/json', // Тип вмісту, що відправляється
                data: JSON.stringify(data), // Перетворення даних об'єкта в рядок JSON
                success: function(response) { // Функція зворотного виклику для обробки відповіді
                    alert(response); // Виведення відповіді сервера
                    if (response === "Тест успішно створено!") {
                        window.location.href = "main_admin.php"; // Перенаправлення на іншу сторінку
                    }
                },
                error: function(xhr, status, error) { // Функція зворотного виклику для обробки помилок
                    // Обробка помилок запиту
                    alert("Помилка: " + error);
                }
            });

        });

    });

</script>
