<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotes - DM Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{asset('css/reminders.css')}}">
</head>
<body>
<div class="container">
    <x-lateral-bar></x-lateral-bar>
    <main class="main-content">
        <header class="header">
            <h1>Recordatorios</h1>
        </header>
        <section class="form-container">
            <h2>Agregar Recordatorio</h2>
            <form id="reminderForm">
                <label for="project">Proyecto / Cotización:</label>
                <select id="project">
                    <option value="Proyecto A">Proyecto A</option>
                    <option value="Cotización 123">Cotización 123</option>
                    <!-- Aquí se llenarán los proyectos y cotizaciones dinámicamente -->
                </select>

                <label for="reminderDate">Fecha del Recordatorio:</label>
                <input type="date" id="reminderDate" required>

                <label for="message">Mensaje:</label>
                <textarea id="message" rows="3" required></textarea>

                <button type="submit">Agregar</button>
            </form>
        </section>

        <!-- Lista de recordatorios -->
        <div class="table-container">
            <h2>Recordatorios Programados</h2>
            <table class="project-table">
                <thead>
                <tr>
                    <th>Proyecto / Cotización</th>
                    <th>Fecha</th>
                    <th>Mensaje</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="reminderList">
                <!-- Aquí se llenarán los recordatorios dinámicamente -->
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Script para manejar recordatorios -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Inicializar el selector de fechas con Flatpickr
        flatpickr(".datepicker", { enableTime: true, dateFormat: "Y-m-d H:i" });

        // Manejar el formulario de recordatorios
        document.getElementById("reminderForm").addEventListener("submit", function (event) {
            event.preventDefault();

            // Capturar datos del formulario
            const title = document.getElementById("title").value;
            const date = document.getElementById("date").value;
            const type = document.getElementById("type").value;
            const message = document.getElementById("message").value;

            // Crear un nuevo recordatorio en la lista
            const reminderList = document.getElementById("reminderList");
            const li = document.createElement("li");
            li.innerHTML = `<strong>${title}</strong> - ${date} (${type}) <p>${message}</p>`;
            reminderList.appendChild(li);

            // Limpiar formulario
            this.reset();
        });
    });
</script>
</body>
</html>
