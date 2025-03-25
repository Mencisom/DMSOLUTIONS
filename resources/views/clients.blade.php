<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients - DM Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{asset('css/clients.css')}}">
</head>
@if(session('status'))
    <script>
        alert("{{session('status')}}")
    </script>
@endif
<body>
<div class="container">
    <!-- Barra lateral -->
    <x-lateral-bar></x-lateral-bar>
    <!-- Contenido principal -->
    <main class="main-content">
        <header class="header">
            <h1>CLIENTES</h1>
            <div class="search-bar">
                <input type="text" placeholder="Buscar clientes...">
                <button class="filter-button"><i class="fas fa-filter"></i> Filtros</button>
                <button class="new-button" id="openModalButton"><i class="fas fa-plus"></i> Nuevo</button>
            </div>
        </header>
        <div class="table-container">
            <table class="project-table">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Identificación</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
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
                                        <button class="action-btn view-update-client" id="open-update-btn">Actualizar</button>
                                        <form action="{{route('client-delete',$client)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="action-btn">Eliminar</button>
                                        </form>
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
        <h2>Actualizar Cliente</h2>
        <br>
        <form id="clientForm" method="POST" action="{{route('client-save')}}">
            @csrf
            <label for="clientName">Nombre:</label>
            <input type="text" name="clientName" id="clientName" value="{{old('clientName')}}" required>

            <label for="clientPhone">Teléfono:</label>
            <input type="text" name="clientPhone" id="clientPhone" value="{{old('clientPhone')}}" required>

            <label for="clientEmail">Email:</label>
            <input type="email" name="clientEmail" id="clientEmail" value="{{old('clientEmail')}}" required>

            <label for="clientIdentification">Identificación:</label>
            <input type="text" name="clientIdentification" id="clientIdentification" value="{{old('clientIdentification')}}" required>

            <label for="clientAddress">Dirección:</label>
            <input type="text" name="clientAddress" id="clientAddress" value="{{old('clientAddress')}}" required>

            <button type="submit">Guardar Cliente</button>

            <button type="button" id="closeFormButton" class="close-form-button">Cerrar</button>

        </form>
    </div>
</div>

<!-- Modal para actualizar cliente -->
<div class="modal hidden" id="clientUpdate">
    <div class="modal-content">
        <h2>Agregar Cliente</h2>
        <br>
        <form id="clientForm" method="POST" action="{{ route('client-update') }}">
            @csrf @method('PATCH')
            <label for="clientName">Nombre:</label>
            <input type="text" name="clientName" id="update-clientName" value="{{old('clientName')}}" required>

            <label for="clientPhone">Teléfono:</label>
            <input type="text" name="clientPhone" id="update-clientPhone" value="{{old('clientPhone')}}" required>

            <label for="clientEmail">Email:</label>
            <input type="email" name="clientEmail" id="update-clientEmail" value="{{old('clientEmail')}}" required>

            <label for="clientIdentification">Identificación:</label>
            <input type="text" name="clientIdentification" id="update-clientIdentification" value="{{old('clientIdentification')}}" required>

            <label for="clientAddress">Dirección:</label>
            <input type="text" name="clientAddress" id="update-clientAddress" value="{{old('clientAddress')}}" required>

            <button type="submit" id="updateButton">Actualizar Cliente</button>

            <button type="button" id="closeUpdateButton" class="close-form-button">Cerrar</button>

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

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById("clientUpdate");
        const openModalButton = document.getElementById("open-update-btn");
        const closeFormButton = document.getElementById("closeUpdateButton");
        const botonesDetalle = document.querySelectorAll('.view-update-client');

        botonesDetalle.forEach(function(boton) {
            boton.addEventListener('click', function(event) {
                // Evitar que el clic en el botón se propague
                event.stopPropagation();

                // Obtener la fila correspondiente al botón clickeado
                let fila = boton.closest('tr');

                // Acceder a los datos de la fila (ID, Fecha de expiración, Nombre, Teléfono, etc.)
                document.getElementById("update-clientName").setAttribute("value",fila.cells[1].textContent);
                document.getElementById("update-clientPhone").setAttribute("value",fila.cells[2].textContent);
                document.getElementById("update-clientEmail").setAttribute("value",fila.cells[3].textContent);
                document.getElementById("update-clientIdentification").setAttribute("value",fila.cells[4].textContent);
                document.getElementById("update-clientAddress").setAttribute("value",fila.cells[5].textContent);

                modal.classList.remove("hidden");
            });
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

   /* document.addEventListener('DOMContentLoaded',function (){
        document.getElementById('updateButton').addEventListener('click',function () {
            const client = document.getElementById('update-clientIdentification').getAttribute("value");
            console.log("sexo anal duro",client)
            try{
                fetch(`clients/${client}/update`)
                .then(response => response.json())
                if (response.ok) {
                    alert('Cliente actualizado exitosamente');
                } else {
                    alert('Hubo un problema con la solicitud');
                }
            }
            catch (error){
                alert('Error'+error);
            }
        })
    });*/

    /*document.getElementById('clientForm').addEventListener('submit', async (event) => {
        event.preventDefault();
        const data = {
            clientName: document.getElementById('clientName').value,
            clientPhone: document.getElementById('clientPhone').value,
            clientEmail: document.getElementById('clientEmail').value,
            clientIdentification: document.getElementById('clientIdentification').value,
            clientAddress: document.getElementById('clientAddress').value,
            token: document.getElementsByName('_token').value
        }
        try {
            const response = await fetch('clients/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    // Si necesitas incluir un token CSRF en Laravel (para proteger de CSRF), añade este encabezado:
                    'X-CSRF-TOKEN':data.token
                },
                body: JSON.stringify(data)  // Enviar los datos en el cuerpo de la solicitud
            });

            // Verificar si la respuesta es exitosa
            if (response.ok) {
                const result = await response.json();  // Obtener la respuesta como JSON
                alert('Mensaje desde el servidor:'+result.message);
            } else {
                alert('Hubo un problema con la solicitud');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });*/
</script>
</body>
</html>
