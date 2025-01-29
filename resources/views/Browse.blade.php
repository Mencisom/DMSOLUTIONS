<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse - DM Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{asset('css/browse.css')}}">
</head>
<body>
<div class="container">
    <!-- Barra lateral -->
    <x-lateral-bar></x-lateral-bar>
    <!-- Contenido principal -->
    <main class="main-content">
        <header class="header">
            <h1>Projects</h1>
            <div class="search-bar">
                <input type="text" placeholder="Search tickets...">
                <button class="filter-button">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </header>
        <div class="table-container">
            <table class="project-table">
                <thead>
                <tr>
                    <th>Task</th>
                    <th>CLIENTE</th>
                    <th>Price</th>
                    <th>Finish</th>
                    <th>Owner</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>FIG-123</td>
                    <td>Task 1</td>
                    <td><button class="btn">Project 1</button></td>
                    <td>Dec 5</td>
                    <td><img src="owner.jpg" alt="Owner" class="avatar"></td>
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

