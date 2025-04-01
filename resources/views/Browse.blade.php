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
                    <th>Anticipo</th>
                    <th>Fecha De Inicio</th>
                    <th>Fecha De cierre</th>
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
                            <td><button class="btn">{{number_format($project->quote_total)}}</button></td>
                            <td>{{number_format($project->proj_deposit)}}</td>
                            <td>{{$project->proj_start_date}}</td>
                            <td>{{$project->proj_end_date}}</td>
                            <td>{{$project->status_name}}</td>
                            <td>
                                <div class="action-menu">
                                    <span class="action-dots">•••</span>
                                    <div class="action-dropdown hidden">
                                        <button class="action-btn edit-project">Actualizar</button>
                                        <form action="{{route('project-delete',$project->id)}}" method="POST">
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
<div class="modal hidden" id="editProjectModal">
    <div class="modal-content">
        <h1>EDITAR PROYECTO: </h1> <br>
        <form id="ProjectForm" method='POST' action="{{route('project-update')}}">
            @csrf @method('PATCH')
            <label for="menu">Estado:</label>
            <select id="menuStatus" name="menuStatus">

            </select>

            <input type="hidden" name="hiddenProjectId" id="hiddenProjectId">
            <label>Visita Técnica: <h style="color: red">*</h></label>
            <input name="calendar" type="date" id="visit-calendar" placeholder="Selecciona fecha y hora">

            <label>Nombre del proyecto: <h style="color: red">*</h></label>
            <input name="projName" type="text" id="proj-name" required>

            <label>Fecha de inicio: <h style="color: red">*</h></label>
            <input name="projStartDate" type="date" id="proj-start" required>

            <label>Fecha de finalización: <h style="color: red">*</h></label>
            <input name="projEndDate" type="date" id="proj-end" required>

            <label>Anticipo: <h style="color: red">*</h></label>
            <input name="projDeposit" type="number" id="proj-deposit" required>

            <label>Garantía: <h style="color: red">*</h></label>
            <input name="projWarranty" type="date" id="proj-warranty" required>

            <button type="submit" class="modal-button">Actualizar Proyecto</button>
            <button type="button" id="closeEditProjectModal" class="modal-button">Cerrar</button>
        </form>
    </div>
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

    //Editar Proyecto
    const EditModalProject = document.getElementById("editProjectModal"); // Selecciona el modal
    const closeEditProjectModal = document.getElementById('closeEditProjectModal'); // Botón de cerrar el modal
    const options = document.getElementById("menuStatus");


    document.addEventListener('DOMContentLoaded', function() {
        const botonesEditar = document.querySelectorAll('.edit-project');
        let status_id = 0;
        botonesEditar.forEach(function(boton) {
            boton.addEventListener('click', function(event) {
                // Evitar que el clic en el botón se propague
                event.stopPropagation();
                let fila = boton.closest('tr');
                let projectDetail = fila.cells[0].textContent;
                console.log(projectDetail);
                console.log(projectDetail);
                fetch(`projects/detail/${projectDetail}`)
                    .then(response => response.json())
                    .then(data => {
                        status_id = data.data.status_id;
                        console.log("Status id ",status_id);
                        console.log(data);
                        document.getElementById("hiddenProjectId").value = data.data.id;
                        document.getElementById("proj-name").value = data.data.proj_name;
                        document.getElementById("proj-start").value = parsDate(data.data.proj_start_date);
                        document.getElementById("proj-end").value = parsDate(data.data.proj_end_date);
                        document.getElementById("proj-deposit").value = data.data.proj_deposit;
                        document.getElementById("proj-warranty").value = parsDate(data.data.proj_warranty);
                        document.getElementById("proj-deposit").max = fila.cells[2].textContent.replace(/,/g, '');
                        let visita = data.data.proj_visit;

                        if (visita != null){
                            document.getElementById("visit-calendar").value = parsDate(data.data.proj_warranty);
                        }else{
                            document.getElementById("visit-calendar").value = "";
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error.message);
                    })
                fetch("/status")
                    .then(response => response.json())
                    .then(statuses => {
                        options.innerHTML = "";  // Limpiar las opciones antes de añadirlas

                        statuses.forEach(status => {
                            // Crear el elemento option
                            const option = document.createElement('option');
                            option.value = status.id;
                            option.textContent = status.status_name;

                            // Si el estado actual del proyecto coincide con el valor, seleccionar la opción
                            if (status.id == status_id) {
                                option.selected = true;
                            }

                            // Añadir la opción al select
                            options.appendChild(option);
                        });
                    });

                EditModalProject.classList.remove("hidden");
            });
        });
    });

    closeEditProjectModal.addEventListener("click", () => {
        EditModalProject.classList.add("hidden");
    })

    function parsDate(date){
        date = new Date();

        // Obtener las partes de la fecha
        const day = String(date.getDate()).padStart(2, '0');  // Día con 2 dígitos
        const month = String(date.getMonth() + 1).padStart(2, '0');  // Mes con 2 dígitos (se suma 1 porque los meses empiezan desde 0)
        const year = date.getFullYear();  // Año de 4 dígitos

        // Formato DD-MM-YYYY
        return `${year}-${month}-${day}`;
    }

</script>
</body>
</html>

