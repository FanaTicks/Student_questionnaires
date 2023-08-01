$(document).ready(function() {
    $('#create-questions').click(function() {
        var questionCount = $('#question-count').val();

        for (var i = 0; i < questionCount; i++) {
            var questionField = $('<textarea>').attr('name', 'question_' + i).attr('required', true);
            var answerCountField = $('<input>').attr('type', 'number').attr('name', 'answer_count_' + i).attr('required', true);
            var createAnswerButton = $('<button>').attr('type', 'button').text('Створити відповіді');

            $('#test-form').append(questionField);
            $('#test-form').append(answerCountField);
            $('#test-form').append(createAnswerButton);

            createAnswerButton.click(function() {
                var answerCount = $(this).prev().val();

                for (var j = 0; j < answerCount; j++) {
                    var answerField = $('<input>').attr('type', 'text').attr('name', 'answer_' + j).attr('required', true);
                    $(this).after(answerField);
                }
            });
        }

        var timeField = $('<input>').attr('type', 'time').attr('name', 'time').attr('required', true);
        var submitButton = $('<button>').attr('type', 'submit').text('Зберегти');
        $('#test-form').append(timeField);
        $('#test-form').append(submitButton);
    });
});
