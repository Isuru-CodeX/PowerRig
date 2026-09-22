<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.css">
    <title>Dropdown Test</title>
</head>

<body onload="loadChart();">
    <div style="width: 600px;">
        <h1 class="text-center">Monthly Sales Data</h1>
        <canvas id="myChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function loadChart() {
            var ctx = document.getElementById("myChart");
            var request = new XMLHttpRequest();
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {
                    var response = JSON.parse(request.responseText);

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: response.months,
                            datasets: [{
                                label: 'Total Sales ($)',
                                data: response.monthly_price,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        boxWidth: 20,
                                        padding: 15
                                    }
                                }
                            },
                            responsive: true,
                            maintainAspectRatio: true
                        }
                    });
                }
            };
            request.open("POST", "loadChartProcess.php", true);
            request.send();
        }
    </script>

</body>

</html>
