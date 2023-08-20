$(document).ready(function() {
    loadTestResults(); // Виклик функції після завантаження сторінки
});

function loadTestResults() {
    $.get("../Model/get_test_results.php", function(data) {
        console.log(data);
        var results = JSON.parse(data);
        var resultsTableBody = $('#results-table tbody');
        resultsTableBody.empty();

        results.testResults.forEach(function(result) {
            var rowHtml = '<tr>';
            rowHtml += '<td>' + result.testName + '</td>';
            rowHtml += '<td>' + result.testPasses + '</td>';
            rowHtml += '<td>' + result.averageRating + '</td>';
            rowHtml += '<td><button class="review-button" data-test-id="' + result.test_id + '">Огляд</button></td>';
            rowHtml += '</tr>';
            resultsTableBody.append(rowHtml);
        });

        // Додаємо обробник події для кнопок огляду
        $(".review-button").click(function() {
            var testId = $(this).data('test-id');
            window.location.href = "individual_results.html?test_id=" + testId;
        });
    });
}
