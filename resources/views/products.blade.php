<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - DM Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
</head>
<body>
<div class="container">
    <!-- Barra lateral -->
    <x-lateral-bar ></x-lateral-bar>

    <!-- Contenido principal -->
    <main class="main-content">
        <header class="header">
            <h1>PRODUCTS</h1>
            <div class="search-bar">
                <input type="text" placeholder="Search products...">
                <button class="filter-button"><i class="fas fa-filter"></i> Filter</button>
                <button class="new-button" id="openModalButton"><i class="fas fa-plus"></i> New</button>
            </div>
        </header>
        <div class="table-container">
            <table class="project-table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Id</th>
                    <th>Status</th>
                    <th>Price</th>
                    <th>Icon</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @isset($products)
                    @foreach($products as $product)
                        <tr>
                            <td>{{$product->prod_name}}</td>
                            <td>{{$product->prod_des}}</td>
                            <td>{{$product->id}}</td>
                            <td>Active</td>
                            <td>{{$product->prod_price}}</td>
                            <td><img src="Images/martillo.jpg"></td>
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

<!-- Modal para agregar producto -->
<div class="modal hidden" id="productModal">
    <div class="modal-content">
        <h2>Agregar Producto</h2>
        <form id="productForm">
            <label for="productName">Nombre:</label>
            <input type="text" id="productName" required>

            <label for="productDescription">Descripción:</label>
            <textarea id="productDescription" required></textarea>

            <label for="productId">ID:</label>
            <input type="text" id="productId" required>

            <label for="productStatus">Estado:</label>
            <select id="productStatus">
                <option value="Disponible">Disponible</option>
                <option value="Agotado">Agotado</option>
            </select>

            <label for="productRoute">Ruta:</label>
            <input type="text" id="productRoute" required>

            <label for="productPrice">Precio:</label>
            <input type="number" id="productPrice" required>

            <label for="productImage">Subir Imagen:</label>
            <input type="file" id="productImage" accept="image/*" required>

            <button type="submit">Guardar Producto</button>
            <button type="button" id="closeFormButton" class="close-form-button">Cerrar</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("productModal");
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

    document.getElementById('productForm').addEventListener('submit', (event) => {
        event.preventDefault();

        const name = document.getElementById('productName').value;
        const description = document.getElementById('productDescription').value;
        const id = document.getElementById('productId').value;
        const status = document.getElementById('productStatus').value;
        const route = document.getElementById('productRoute').value;
        const price = document.getElementById('productPrice').value;
        const image = document.getElementById('productImage').files[0];

        if (name && description && id && status && route && price && image) {
            alert('Producto guardado con éxito');
            document.getElementById('productModal').classList.add('hidden');
            document.getElementById('productForm').reset();
        } else {
            alert('Por favor completa todos los campos.');
        }
    });
</script>
</body>
</html>
