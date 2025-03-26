<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse - DM Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{asset('css/browse.css')}}">
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
                    <th>Cliente</th>
                    <th>Precio</th>
                    <th>Fecha De Terminación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>

                    @isset($projects)
                        @foreach($projects as $project)
                            <tr>
                                <td>{{$project->id}}</td>
                                <td>{{$project->proj_name}}</td>
                                <td><button class="btn">{{$project->proj_deposit}}</button></td>
                                <td>{{$project->proj_end_date}}</td>
                                <td>Active</td>
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

                <!-- Más filas -->
                </tbody>
            </table>
        </div>
    </main>
</div>
<script>
    // JavaScript - el menú emergente
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
</script>
</body>
</html>

