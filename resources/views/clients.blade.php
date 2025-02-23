<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients - DM Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{asset('css/clients.css')}}">
</head>
<body>
<div class="container">
    <!-- Barra lateral -->
    <x-lateral-bar></x-lateral-bar>
    <!-- Contenido principal -->
    <main class="main-content">
        <header class="header">
            <h1>CLIENTS</h1>
            <div class="search-bar">
                <input type="text" placeholder="Search clients...">
                <button class="filter-button"><i class="fas fa-filter"></i> Filter</button>
                <button class="new-button" id="openModalButton"><i class="fas fa-plus"></i> New</button>
            </div>
        </header>
        <div class="table-container">
            <table class="project-table">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Identification</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @isset($clients)
                    @foreach($clients as $client)
                        <tr>
                            <td>{{$client->id}}</td>
                            <td>{{$client->client_name}}</td>
                            <td>{{$client->client_ph}}</td>
                            <td>{{$client->client_email}}</td>
                            <td>{{$client->client_identification}}</td>
                            <td>{{$client->client_address}}</td>
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

<!-- Modal para agregar cliente -->
<div class="modal hidden" id="clientModal">
    <div class="modal-content">
        <h2>Agregar Cliente</h2>
        <form id="clientForm">

            <label for="clientId">ID:</label>
            <input type="text" id="clientId" required>

            <label for="clientName">Nombre:</label>
            <input type="text" id="clientName" required>

            <label for="clientPhone">Teléfono:</label>
            <input type="text" id="clientPhone" required>

            <label for="clientEmail">Email:</label>
            <input type="email" id="clientEmail" required>

            <label for="clientIdentification">Identificación:</label>
            <input type="text" id="clientIdentification" required>

            <label for="clientAddress">Dirección:</label>
            <input type="text" id="clientAddress" required>

            <button type="submit">Guardar Cliente</button>
            <button type="button" id="closeFormButton" class="close-form-button">Cerrar</button>

        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("clientModal");
        const openModalButton = document.getElementById("openModalButton");
        const closeFormButton = document.getElementById("closeFormButton");

        // Abre el modal al hacer clic en el botón "New"
        openModalButton.addEventListener("click", () => {
            modal.classList.remove("hidden");
        });

        // Cierra el modal al hacer clic en "Cerrar"
        closeFormButton.addEventListener("click", () => {
            modal.classList.add("hidden");
        });

        // Cierra el modal si el usuario hace clic fuera del contenido del modal
        window.addEventListener("click", (event) => {
            if (event.target === modal) {
                modal.classList.add("hidden");
            }
        });
    });

    const actionMenus = document.querySelectorAll('.action-menu');

    actionMenus.forEach(menu => {
        const dots = menu.querySelector('.action-dots');
        const dropdown = menu.querySelector('.action-dropdown');

        dots.addEventListener('click', () => {
            document.querySelectorAll('.action-dropdown').forEach(drop => {
                if (drop !== dropdown) {
                    drop.classList.add('hidden');
                }
            });

            dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (event) => {
            if (!menu.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });

    document.getElementById('clientForm').addEventListener('submit', (event) => {
        event.preventDefault();

        const name = document.getElementById('clientName').value;
        const id = document.getElementById('clientId').value;
        const phone = document.getElementById('clientPhone').value;
        const email = document.getElementById('clientEmail').value;
        const identification = document.getElementById('clientIdentification').value;
        const address = document.getElementById('clientAddress').value;

        if (name && id && phone && email && identification && address) {
            alert('Cliente guardado con éxito');
            document.getElementById('clientModal').classList.add('hidden');
            document.getElementById('clientForm').reset();
        } else {
            alert('Por favor completa todos los campos.');
        }
    });
</script>
</body>
</html>
