<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse - DM Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
</head>
@if(session('status'))
    <script>
        alert("{{session('status')}}")
    </script>
@endif
<body>
<div class="container">
    <x-lateral-bar></x-lateral-bar>

    <!-- Contenido principal (donde estarán los gráficos) -->
    <div class="main-content">
        <header class="header">
            <h1>DASHBOARD - PROYECTOS</h1>
        </header>
        <div class="table-container">
            <table class="project-table">
                <thead>
                <tr>
                    <th>TOTAL DE PROYECTOS: </th>
                    <th>3</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <br>
        <div class="grid-container">
            <!-- Gráfico 1: Estado de Proyectos -->
            <div class="chart-container">
                <h2>Proyectos por Estado</h2>
                <br>
                <canvas id="projectStatusChart1"></canvas>
            </div>

            <!-- Gráfico 2: Proyectos por Cliente -->
            <div class="chart-container">
                <h2>Proyectos por Cliente</h2>
                <br>
                <canvas id="projectStatusChart2"></canvas>
            </div>
            <!-- Gráfico 3: Proyectos por Fecha -->
            <div class="chart-container">
                <h2>Proyectos por Fecha</h2>
                <br>
                <canvas id="projectStatusChart3"></canvas>
            </div>
            <!-- Gráfico 4: Proyectos por Anticipo -->
            <div class="chart-container">
                <h2>Proyectos por Anticipo</h2>
                <br>
                <canvas id="projectStatusChart4"></canvas>
            </div>


        </div>
    </div>
</div>

<script>
    let keys_status = []
    let value_status = []
    let keys_client = []
    let value_client = []
    // Función para crear gráfico de torta
    function createPieChart(ctx, labels, data, backgroundColor) {
            return new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: backgroundColor
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        }

        const projectData2 = {
            "Cliente A": 6,
            "Cliente B": 12,
            "Cliente C": 5,
            "Cliente D": 7
        };

        const projectData3 = {
            "Enero": 3,
            "Febrero": 5,
            "Marzo": 7,
            "Abril": 4
        };

        const projectData4 = {
            "Bajo": 5,
            "Medio": 10,
            "Alto": 7
        };
     window.onload = function() {
         //Proyectos por estado
        fetch("/dashboard/status")
            .then(response => response.json())
            .then(data => {
                console.log(data)
                keys_status = data.map(item => item.status_name);
                console.log ("labels",keys_status);
                value_status = data.map(item => item.cantidad);
                console.log ("Values",value_status);
                createPieChart(
                    document.getElementById('projectStatusChart1').getContext('2d'),
                    keys_status,
                    value_status,
                    ['rgba(36, 191, 164, 0.6)', 'rgba(153, 102, 255, 0.6)','rgba(255, 159, 64, 0.6)', 'rgba(255, 99, 132, 0.6)']
                );
            })

            //proyectos por cliente

        fetch("/dashboard/clients")
            .then(response => response.json())
            .then(data => {
                keys_client = data.map(item => item.client_name);
                console.log ("labels",keys_status);
                value_client = data.map(item => item.cantidad);
                console.log ("Values",value_status);
                createPieChart(
                    document.getElementById('projectStatusChart2').getContext('2d'),
                    keys_client,
                    value_client,
                    ['rgba(36, 191, 164, 0.6)', 'rgba(153, 102, 255, 0.6)','rgba(255, 159, 64, 0.6)', 'rgba(255, 99, 132, 0.6)']
                );
            })

            createPieChart(
                document.getElementById('projectStatusChart3').getContext('2d'),
                Object.keys(projectData3),
                Object.values(projectData3),
                ['rgba(153, 102, 255, 0.6)', 'rgba(255, 159, 64, 0.6)', 'rgba(36, 191, 164, 0.6)', 'rgba(255, 99, 132, 0.6)']
            );

            createPieChart(
                document.getElementById('projectStatusChart4').getContext('2d'),
                Object.keys(projectData4),
                Object.values(projectData4),
                ['rgba(255, 99, 132, 0.6)', 'rgba(36, 191, 164, 0.6)', 'rgba(153, 102, 255, 0.6)']
            );
        }
    </script>
</body>
</html>
