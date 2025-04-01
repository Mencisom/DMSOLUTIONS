<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - DM Solutions</title>
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
            <h1>PROYECTOS</h1>
            <div class="search-bar">
                <input type="text" placeholder="Buscar proyectos...">
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
                    <th>ID</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>

                @isset($users)
                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->user_first_name}}</td>
                            <td>{{$user->user_last_name}}</td>
                            <td>{{$user->user_email}}</td>
                            <td>{{$user->role_name}}</td>
                            <td>
                                <div class="action-menu">
                                    <span class="action-dots">•••</span>
                                    <div class="action-dropdown hidden">
                                        <button class="action-btn edit-project">Actualizar</button>
                                        <form action="{{route('user-delete',$user -> id)}}" method="POST">
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
                <!-- Más filas -->
                </tbody>
            </table>
        </div>
    </main>
</div>
!-- Modal para agregar user -->
<div class="modal hidden" id="userModal">
    <div class="modal-content">
        <h2>Agregar Usuario</h2>
        <br>
        <form id="userForm" method="POST" action="{{route('user-save')}}">
            @csrf
            <label for="userName">Nombres:</label>
            <input type="text" name="userNames" id="userNames" value="{{old('userNames')}}" required>

            <label for="userlastName">Apellidos:</label>
            <input type="text" name="userLastNames" id="userLastNames" value="{{old('userLastNames')}}" required>

            <label for="userEmail">Email:</label>
            <input type="email" name="userEmail" id="userEmail" value="{{old('userEmail')}}" required>

            <label for="userPassword">Contraseña:</label>
            <input type="password" name="userPassword" id="userPassword" value="{{old('userPassword')}}" required>

            <label for="menu">Rol:</label>
            <select id="menuRol" name="menuRol">

            </select>
            <button type="submit">Guardar usuario</button>

            <button type="button" id="closeFormButton" class="close-form-button">Cerrar</button>
        </form>
    </div>
</div>
<script>
    const rolOptions = document.getElementById('menuRol');
    const openNewUser = document.getElementById('openModalButton')
    const closeNewUser = document.getElementById('closeFormButton')
    const openUserModal = document.getElementById('userModal')

    //Abrir modal nuevo cliente

    openNewUser.addEventListener("click", () => {
        openUserModal.classList.remove("hidden");
    })

    closeNewUser.addEventListener("click", () => {
        openUserModal.classList.add("hidden");
    })

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

    document.addEventListener('DOMContentLoaded', function() {

        fetch("/role")
            .then(response => response.json())
            .then(statuses => {
                rolOptions.innerHTML = "";  // Limpiar las opciones antes de añadirlas

                statuses.forEach(role => {
                    // Crear el elemento option
                    const option = document.createElement('option');
                    option.value = role.id;
                    option.textContent = role.role_name;
                    // Añadir la opción al select
                    rolOptions.appendChild(option);
                });
            });
    })
</script>
</body>
</html>
