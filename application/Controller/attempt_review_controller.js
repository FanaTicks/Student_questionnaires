function loadTestDetails() {
    var params = new URLSearchParams(window.location.search);
    var test_id = params.get('test_id');
    var ID_listeners = params.get('ID_listeners');
    var ID_Results = params.get('ID_Results');

    $.get("../Model/attempt_review.php", { test_id: test_id, ID_listeners: ID_listeners, ID_Results: ID_Results }, function(data) {
        if (data != null) {
            var results = JSON.parse(data);
            $('#test-title').text("Тест: " + results.testDetails.Name_test);
            $('#user-name').text("Користувач: " + (results.userName || "Невідомий"));
            var testContent = $('#test-content');
            testContent.empty();

            var questionNumber = 1;
            for (var questionId in results.questions) {
                var question = results.questions[questionId];
                var questionHtml = '<h3>Питання ' + questionNumber + ' - ' + question.question + '</h3>';
                question.answers.forEach(function(answer) {
                    // Виправлення помилки - правильна змінна - userAnswersIDs замість userAnswersIDs
                    var userAnsweredThis = results.userAnswersIDs.includes(answer.id);
                    var isCorrectAnswer = answer.correct == 1;

                    questionHtml += '<label><input type="radio" disabled ' + (userAnsweredThis ? 'checked="checked"' : '') + '>' + answer.text;

                    if (userAnsweredThis) {
                        if (isCorrectAnswer) {
                            questionHtml += ' <span style="background-color: green; display: inline-block; width: 15px; height: 15px;"></span>';
                        } else {
                            questionHtml += ' <span style="background-color: red; display: inline-block; width: 15px; height: 15px;"></span>';
                        }
                    }

                    questionHtml += '</label><br>';
                });
                testContent.append(questionHtml);
                questionNumber++;
            }

        } else {
            console.log("Server returned null");
        }
    });
}

$(document).ready(function() {
    // Завантаження деталей тесту при завантаженні сторінки
    loadTestDetails();

    // Перехід до головного меню
    $("#back-button").click(function() {
        window.location.href = "individual_results.html";
    });
});
