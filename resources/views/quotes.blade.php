<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotes - DM Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{'css/quotes.css'}}">
</head>
<body>
<div class="container">
    <!-- Barra lateral -->
    <x-lateral-bar></x-lateral-bar>

    <!-- Contenido principal -->
    <main class="main-content">
        <header class="header">
            <h1>COTIZACIONES</h1>
            <div class="search-bar">
                <input type="text" placeholder="Buscar Cotizaciones...">
                <button class="filter-button">
                    <i class="fas fa-filter"></i> Filtros
                </button>
                <button class="new-button" id="openModalButton">
                    <i class="fas fa-plus"></i> Nuevo
                </button>
            </div>
        </header>
        <div class="table-container">
            <table class="project-table">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Fecha de expiración</th>
                    <th>Nombre del Cliente</th>
                    <th>Teléfono del Cliente</th>
                    <th>Horas estimadas</th>
                    <th>Precio Materiales</th>
                    <th>Ayudantes</th>
                    {{--<th>Precio Ayudante día</th>
                    <th>Precio Supervisor Día</th>
                    <th>Precio Mano de obra</th>--}}
                    <th>Otros costos</th>
                    <th>Precio total</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @isset($quotes)
                    @foreach($quotes as $quote)
                        <tr>
                            <td>{{$quote->id}}</td>
                            <td>{{$quote->quote_expiration_date}}</td>
                            <td>{{$quote->client_name}}</td>
                            <td>{{$quote->client_ph}}</td>
                            <td>{{$quote->quote_estimated_time}}</td>
                            <td>{{$quote->quote_material_total}}</td>
                            {{--<td>{{$quote->quote_helpers}}</td>
                            <td>{{$quote->quote_helper_payday}}</td>
                            <td>{{$quote->quote_supervisor_payday}}</td>--}}
                            <td>{{$quote->quote_work_total}}</td>
                            <td>{{$quote->quote_other_costs}}</td>
                            <td><button class="btn">{{$quote->quote_total}}</button></td>
                            <td>
                                <div class="action-menu">
                                    <span class="action-dots">•••</span>
                                    <div class="action-dropdown hidden">
                                        <button class="action-btn">Eliminar</button>
                                        <button class="action-btn">Actualizar</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endisset
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Formulario cotizacion -->
<div class="modal hidden" id="newCotizationModal">
    <div class="modal-content">
        <h2>Nueva Cotización</h2>
        <form>
            <label>Nombre o Razón Social</label>
            <input type="text" placeholder="Nombre o Razón Social">

            <div class="checkbox-group">
                <label>
                    <input type="checkbox"> C.C
                </label>
                <label>
                    <input type="checkbox"> Nit
                </label>
            </div>

            <label>Teléfono</label>
            <input type="text" placeholder="Teléfono">

            <label>Dirección</label>
            <input type="text" placeholder="Dirección">

            <label>Correo</label>
            <input type="email" placeholder="Correo Electrónico">

            <label>Requerimiento</label>
            <textarea placeholder="Describe tu requerimiento"></textarea>

            <label>Visita Técnica</label>
            <div class="radio-group">
                <label>
                    <input type="radio" name="visita" id="visitaSi" value="si"> Sí
                </label>
                <label>
                    <input type="radio" name="visita" id="visitaNo" value="no"> No
                </label>
            </div>

            <!-- Calendario -->
            <div id="calendarContainer" class="hidden">
                <label>Seleccione una fecha:</label>
                <input type="text" id="calendarInput" placeholder="Seleccione una fecha" disabled readonly>
            </div>
        </form>
        <button id="closeModalButton" class="close-modal-button">Cerrar</button>
    </div>
</div>

<!-- Seleccionar la hora -->
<div class="modal hidden" id="hourSelectionModal">
    <div class="modal-content">
        <h2>Selecciona una hora</h2>
        <form id="hourForm">
            <label for="hourSelect">Horas disponibles:</label>
            <select id="hourSelect" required>
                <option value="9:00">9:00 AM</option>
                <option value="10:00">10:00 AM</option>
                <option value="11:00">11:00 AM</option>
                <option value="1:00">1:00 PM</option>
                <option value="3:00">3:00 PM</option>
            </select>
            <button type="submit" class="modal-button">Confirmar Hora</button>
        </form>
    </div>
</div>

<!-- Confirmación -->
<div class="modal hidden" id="confirmationModal">
    <div class="modal-content">
        <h2>¡Visita Agendada!</h2>
        <p>Tu visita técnica ha sido programada exitosamente.</p>
        <button id="closeConfirmationModal" class="modal-button">Cerrar</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    const openModalButton = document.getElementById('openModalButton');
    const closeModalButton = document.getElementById('closeModalButton');
    const modal = document.getElementById('newCotizationModal');
    const visitaSi = document.getElementById('visitaSi');
    const visitaNo = document.getElementById('visitaNo');
    const calendarContainer = document.getElementById('calendarContainer');
    const calendarInput = document.getElementById('calendarInput');
    const hourSelectionModal = document.getElementById('hourSelectionModal');
    const hourForm = document.getElementById('hourForm');
    const confirmationModal = document.getElementById('confirmationModal');
    const closeConfirmationModal = document.getElementById('closeConfirmationModal');

    const actionMenus = document.querySelectorAll('.action-menu');

    actionMenus.forEach(menu => {
        const dots = menu.querySelector('.action-dots');
        const dropdown = menu.querySelector('.action-dropdown');

        dots.addEventListener('click', () => {
            // Oculta otros menús abiertos
            document.querySelectorAll('.action-dropdown').forEach(drop => {
                if (drop !== dropdown) {
                    drop.classList.add('hidden');
                }
            });

            // Alterna el menú actual
            dropdown.classList.toggle('hidden');
        });

        // Cierra el menú al hacer clic fuera
        document.addEventListener('click', (event) => {
            if (!menu.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });

    // Abrir modal de cotización
    openModalButton.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    // Cerrar modal de cotización
    closeModalButton.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    // Mostrar u ocultar calendario
    visitaSi.addEventListener('click', () => {
        calendarContainer.classList.remove('hidden');
        calendarInput.disabled = false;
    });

    visitaNo.addEventListener('click', () => {
        calendarContainer.classList.add('hidden');
        calendarInput.disabled = true;
        calendarInput.value = "";
    });

    // Inicializar calendario y manejar selección de fecha
    flatpickr(calendarInput, {
        enableTime: false,
        dateFormat: "Y-m-d",
        onChange: function (selectedDates, dateStr) {
            modal.classList.add('hidden');
            hourSelectionModal.classList.remove('hidden');
        },
    });

    // Manejar la selección de hora
    hourForm.addEventListener('submit', (e) => {
        e.preventDefault();
        hourSelectionModal.classList.add('hidden');
        confirmationModal.classList.remove('hidden');
    });

    // Cerrar mensaje de confirmación
    closeConfirmationModal.addEventListener('click', () => {
        confirmationModal.classList.add('hidden');
    });

</script>
</body>
</html>
