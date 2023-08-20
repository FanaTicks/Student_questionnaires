function loadIndividualResults() {
    // Отримання test_id та ID_listeners з URL
    var params = new URLSearchParams(window.location.search);
    var test_id = params.get('test_id');
    var ID_listeners = params.get('ID_listeners');

    $.get("../Model/get_individual_test_results.php", { test_id: test_id, ID_listeners: ID_listeners }, function(data) {
        var results = JSON.parse(data);
        $('#test-name').text(results.testName);
        $('#listener-name').text(results.listenerName);
        var resultsTableBody = $('#results-table tbody');
        resultsTableBody.empty();

        results.data.forEach(function(dataItem, index) {
            var rowHtml = '<tr>';
            rowHtml += '<td>' + results.attempts[index].listenerName + '</td>';
            rowHtml += '<td>' + results.attempts[index].time + '</td>';
            rowHtml += '<td>' + results.attempts[index].score + '</td>';
            rowHtml += '<td><button class="review-button" data-ID_Results="' + dataItem.ID_Results + '" data-test_id="' + dataItem.test_id + '" data-ID_listeners="' + dataItem.ID_listeners + '">Огляд</button></td>';
            rowHtml += '</tr>';
            resultsTableBody.append(rowHtml);
        });

        $(".review-button").click(function() {
            var resultId = $(this).attr('data-ID_Results');
            var testId = $(this).attr('data-test_id');
            var listenerId = $(this).attr('data-ID_listeners');
            window.location.href = "attempt_review.html?ID_Results=" + resultId + "&test_id=" + testId + "&ID_listeners=" + listenerId;
        });
    });
}

$(document).ready(function() {
    // Завантаження результатів тестів при завантаженні сторінки
    loadIndividualResults();

    // Перехід до головного меню
    $("#main-menu-button").click(function() {
        window.location.href = "main_menu.html";
    });
});
