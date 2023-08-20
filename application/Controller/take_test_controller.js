$(document).ready(function() {
    // Saving user information
    $("#save-user").click(function() {
        const firstName = $("#first-name").val();
        const lastName = $("#last-name").val();
        const email = $("#email").val();

        // Send user data to the server
        $.post('../Model/save_user.php', { first_name: firstName, last_name: lastName, email: email }, function(response) {
            alert(response); // Show success or error message
        });
    });
    function startTimer(timeInSeconds) {
        timeRemaining = timeInSeconds; // Оновити змінну timeRemaining

        var timer = $('#test-time');
        var initialTime = timeInSeconds; // Запам'ятовуємо загальний час
        var interval = setInterval(function() {
            if (timeRemaining <= 0) {
                // Час вийшов, зупиняємо таймер та зберігаємо результати
                clearInterval(interval);
                saveResultsAndRedirect(initialTime); // Передаємо загальний час
                return;
            }

            var minutes = Math.floor(timeRemaining / 60);
            var seconds = timeRemaining % 60;
            timer.text((minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds);

            timeRemaining--; // Зменшуємо час
        }, 1000);
    }


// Збереження загального часу, виділеного на тест (в секундах)
    var totalTimeForTest;

    $('#start-test').click(function() {
        var testName = $('#test-name').val();
        $.get("../Model/get_test_time.php", { Name_test: testName }, function(time) {
            var timeParts = time.split(':');
            if (timeParts.length !== 3) {
                alert("Помилка завантаження часу на тест!");
                return;
            }
            totalTimeForTest = (+timeParts[0]) * 60 * 60 + (+timeParts[1]) * 60 + (+timeParts[2]); // Зберігаємо загальний час
            startTimer(totalTimeForTest);
            getQuestionsAndDisplay(testName);

        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error in GET request:", textStatus, errorThrown);
        });
    });

    // Submitting the test
    $("#test-form").submit(function(e) {
        e.preventDefault();
        clearInterval(timer); // Зупиняємо таймер

        var timeSpentInSeconds = totalTimeForTest - timeRemaining;

        // Збираємо відповіді та інші дані тесту
        const testName = $("#test-name").val();
        const firstName = $("#first-name").val();
        const lastName = $("#last-name").val();
        const email = $("#email").val();
        const answers = [];


        $('.question').each(function(index, question) {
            var questionId = $(question).data('question-id'); // Унікальний ідентифікатор питання
            var selectedAnswerId = $(question).find('input[type="radio"]:checked').val();
            answers.push({ question_id: questionId, answer_id: selectedAnswerId });
        });

        // Відправляємо дані на сервер
        $.ajax({
            url: '../Model/take_test.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                test_name: testName,
                first_name: firstName,
                last_name: lastName,
                email: email,
                answers: answers,
                time_spent: timeSpentInSeconds
            }),
            success: function(response) {
                alert(response);
                if (response === "Результати успішно збережено!") {
                    window.location.href = "main_menu.html";
                }
            }
        });
    });
});

function saveResultsAndRedirect(totalTime) {
    // Зберігаємо результати та перенаправляємо користувача
    var testName = $('#test-name').val();
    var firstName = $("#first-name").val();
    var lastName = $("#last-name").val();
    var email = $("#email").val();
    var answers = [];

    $('.question').each(function(index, question) {
        var questionId = $(question).data('question-id'); // Унікальний ідентифікатор питання
        var selectedAnswerId = $(question).find('input[type="radio"]:checked').val();
        answers.push({ question_id: questionId, answer_id: selectedAnswerId });
    });


    var timeSpentInSeconds = totalTime - timeRemaining; // Віднімаємо час, що залишився

    $.ajax({
        url: '../Model/take_test.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            test_name: testName,
            first_name: firstName,
            last_name: lastName,
            email: email,
            answers: answers,
            time_spent: timeSpentInSeconds
        }),
        success: function(response) {
            if (response === "Результати успішно збережено!") {
                window.location.href = "main_menu.html"; // Перенаправлення на іншу сторінку
            }
        }
    });
}





function getQuestionsAndDisplay(testName) {
    // Отримуємо питання та відповіді для вибраного тесту
    $.get("../Model/get_questions.php", { test_id: testName }, function(data) {
        var questions = JSON.parse(data); // Додаємо цей рядок, щоб розібрати JSON-рядок
        var questionsContainer = $('#questions-container');
        questionsContainer.empty(); // Очищуємо контейнер від попередніх питань
// Перебираємо питання та створюємо HTML для них
        questions.forEach(function(question, index) {
            var questionHtml = $('<div class="question" data-question-id="' + question.id + '"></div>');
            questionHtml.append('<h3>Питання ' + (index + 1) + ': ' + question.text + '</h3>');

            // Перебираємо відповіді та додаємо їх до питання
            question.answers.forEach(function(answer) {
                questionHtml.append('<label><input type="radio" name="answer_' + question.id + '" value="' + answer.id + '">' + answer.text + '</label><br>');
            });
            questionsContainer.append(questionHtml);
        });
        // Показуємо форму тесту та кнопку здачі
        $('#test-form').show();
        $('#submit-test').show();
    });
}
function formatTime(seconds) {
    var hours = Math.floor(seconds / 3600);
    var minutes = Math.floor((seconds % 3600) / 60);
    var seconds = seconds % 60;
    return (hours < 10 ? "0" : "") + hours + ":" + (minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
}