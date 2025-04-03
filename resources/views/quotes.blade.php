<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotes - DM Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{asset('css/quotes.css')}}">
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
                    <th>ID</th>
                    <th>Fecha de expiración</th>
                    <th>Nombre del cliente</th>
                    <th>Teléfono del cliente</th>
                    <th>Horas estimadas</th>
                    <th>Precio total</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @isset($quotes)
                    @foreach($quotes as $quote)
                        <tr>
                            <td id="quote_id">{{$quote->id}}</td>
                            <td>{{$quote->quote_expiration_date}}</td>
                            <td>{{$quote->client_name}}</td>
                            <td>{{$quote->client_ph}}</td>
                            <td>{{$quote->quote_estimated_time}}</td>
                            <td>{{number_format($quote->quote_total)}}</td>
                            <td>
                                <div class="action-menu">
                                    <span class="action-dots">•••</span>
                                    <div class="action-dropdown hidden">
                                        <button class="btn btn-primary btn-sm" onclick="loadQuoteData({{ $quote->id }})">
                                            Actualizar
                                        </button>
                                        <button class="action-btn view-quote-detail" id="detail-quote">Ver Detalle</button>
                                        <button class="action-btn become-project">pasar a proyecto</button>
                                        <a href="{{route('quote-export',$quote->id)}}">
                                            <button class="action-btn">Exportar</button>
                                        </a>
                                        <form action="{{route('quote-delete',$quote -> id)}}" method="POST">
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
<!-- Update Quote Modal -->
<div id="updateQuoteModal" class="modal hidden">
    <div class="modal-content">
        <span class="close" onclick="closeModal('updateQuoteModal')">&times;</span>
        <h2>Actualizar Cotización</h2>
        <form id="updateQuoteForm" method="POST" action="{{ route('quote-update') }}">
            @csrf @method('PATCH')
            <input type="hidden" id="updateQuoteId" name="quoteId">

            <!-- Reusable Form Fields -->
            <label>Cliente:</label>
            <input type="text" id="updateClientName" name="clientName" required>


            <label>Horas estimadas:</label>
            <input type="number" id="updateEstimatedHours" name="estimatedHours" required>

            <label>Número de asistentes:</label>
            <input type="number" id="updateNumAssistants" name="numAssistants" required>

            <label>Salario Asistentes:</label>
            <input type="number" id="updateAssistantSalary" name="assistantSalary" required>

            <label>Pago Supervisor:</label>
            <input type="number" id="updateSupervisorFee" name="supervisorFee" required>

            <label>Otros Costos:</label>
            <input type="number" id="updateOtherCosts" name="otherCosts" required>

            <!-- Products Table (Non-Editable) -->
            <h3>Productos en la Cotización</h3>
            <table id="updateProductsTable">
                <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Precio Total</th>
                    <th>Acción</th>
                </tr>
                </thead>
                <tbody>
                <!-- Product rows will be dynamically inserted here -->
                </tbody>
            </table>

            <!-- Add New Product Button -->
            <button class="addProductButton">Agregar Producto</button>
            <div class="productList"></div>
            <input type="hidden" name="products" id="hiddenProducts">
            <button type="submit" class="modal-button">Guardar Cotización</button>
            <button type="button" id="closeupdateQuoteModal" class="modal-button">Cerrar</button>
        </form>
    </div>
</div>

<!--Detalle de Cotización -->
<div class="modal hidden" id="quoteDetailModal">
    <div class="modal-content">
        <h2>Detalle de Cotización</h2>
        <table class="quote-detail-table">
            <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Proveedor</th>
                <th>Subtotal</th>

            </tr>
            </thead>
            <tbody id="quoteDetailBody">
            <tr>
                <td>Cámara Sony A7</td>
                <td>2</td>
                <td>$120.00</td>
            </tr>
            <tr>
                <td>Lente 50mm</td>
                <td>1</td>
                <td>$300.000</td>
                <td>$300.000</td>
            </tr>
            <tr>
                <td>Trípode Profesional</td>
                <td>1</td>
                <td>$150.000</td>
                <td>$150.000</td>
            </tr>
            </tbody>
        </table>

        <table class="quote-summary-table">
            <tfoot>
            <tr>
                <td colspan="3"><strong>Total Materiales:</strong></td>
                <td id="totalMaterialsPrice"></td>
            </tr>
            <tr>
                <td colspan="3"><strong>Ayudantes (2 personas x 2 días):</strong></td>
                <td id="helperCost">$400.000</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Supervisor (1 persona x 2 días):</strong></td>
                <td id="supervisorCost">$300.000</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Mano de obra:</strong></td>
                <td id="laborCost">$500.000</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Otros costos:</strong></td>
                <td id="otherCosts">$250.000</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Precio Total:</strong></td>
                <td id="totalPrice">$4,300.000</td>
            </tr>
            </tfoot>
        </table>

        <button id="closeQuoteDetailModal" class="modal-button">Cerrar</button>
    </div>
</div>

<!-- Formulario cotizacion -->
<div class="modal hidden" id="newCotizationModal">
    <div class="modal-content">
        <h2>Nueva Cotización</h2>

        <form  id="quoteForm" action="{{ route('quote-save') }}" method="POST" >
            @csrf
            <label for="clientName">Nombre o Razón Social</label>
            <input type="text" name="clientName" id="clientName" placeholder="Nombre o Razón Social" required>
            <input type="hidden" id="quote_id" name="quote_id">
            <div class="document-type">
                <input type="radio" id="cc" name="document" value="C.C">
                <label for="cc">C.C</label>
                <input type="radio" id="nit" name="document" value="Nit">
                <label for="nit">Nit</label>
            </div>

            <input type="text" name="clientId" id="clientId" placeholder="Identificación" required>

            <label for="phone">Teléfono</label>
            <input type="text" name="phone" id="phone" placeholder="Teléfono" required>

            <label for="address">Dirección</label>
            <input type="text" name="address" id="address" placeholder="Dirección" required>

            <label for="email">Correo</label>
            <input type="email" name="email" id="email" placeholder="Correo Electrónico"required>
            <label for="requirement">Requerimiento</label>
            <textarea id="requirement" name="requirement" placeholder="Describe tu requerimiento"></textarea>

            <h3>Mano de Obra</h3>

            <label for="estimatedHours">Horas Estimadas de Trabajo</label>
            <input type="number" id="estimatedHours" name="estimatedHours" placeholder="Horas" min="0">

            <label for="numAssistants">Número de Auxiliares</label>
            <input type="number" id="numAssistants" name="numAssistants" placeholder="Número de auxiliares" min="0">

            <label for="assistantSalary">Valor Salario Diario del Auxiliar</label>
            <input type="number" id="assistantSalary" name="assistantSalary" placeholder="Salario auxiliar" min="0">

            <label for="supervisorFee">Valor Día Supervisor de la Obra</label>
            <input type="number" id="supervisorFee" name="supervisorFee" placeholder="Valor Supervisor" min="0">

            <label for="otherCosts">Otros Costos</label>
            <input type="number" id="otherCosts" name="otherCosts" placeholder="Otros costos" min="0">

            <h3>Productos</h3>
            <button class="addProductButton">Agregar Producto</button>
            <div class="productList"></div>
            @if(session('error'))
                <script>
                    alert("{{ session('error') }}");
                </script>
            @endif
            @if(session('success'))
                <script>
                    alert("{{ session('success') }}");
                </script>
            @endif
            <input type="hidden" name="products" id="hiddenProducts1">
            <button type="submit" class="modal-button">Guardar Cotización</button>
            <button type="button" id="closeNewQuoteModal" class="modal-button">Cerrar</button>
        </form>
    </div>
</div>

<!--Convertir a proyecto-->
<div class="modal hidden" id="becomeProjectModal">
    <div class="modal-content">
        <h1>PROYECTO NUEVO: </h1> <br>
        <form id="ProjectForm" method="POST" action="{{route('project-save')}}">
            @csrf
            <input type="hidden" id="hiddenQuoteId" name="hiddenQuoteId">
            <label>Visita Técnica:</label>
            <div class="radio-group">
                <input type="radio" id="yes" name="visit" value="Si">
                <label for="yes">Si</label>
                <input type="radio" id="no" name="visit" value="No" checked>
                <label for="no">No</label>
            </div>
            <label for="calendarInput">Fecha y Hora</label>
            <input name="calendar" type="text" id="calendarInput" placeholder="Selecciona fecha y hora">

            <label>Nombre del proyecto:</label>
            <input name="projName" type="text" id="proj-name" required>

            <label>Fecha de inicio:</label>
            <input name="projStartDate" type="date" id="proj-start" required>

            <label>Fecha de finalización:</label>
            <input name="projEndDate" type="date" id="proj-end" required>

            <label>Anticipo:</label>
            <input name="projDeposit" type="number" id="proj-deposit" required>

            <label>Garantía:</label>
            <input name="projWarranty" type="date" id="proj-warranty" required>

            <button type="submit" class="modal-button">Guardar Cotización</button>
            <button type="button" id="closeBecomeProjectModal" class="modal-button">Cerrar</button>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    let productList = [];
    const openModalButton = document.getElementById('openModalButton');
    const closeModalButton = document.getElementById('closeNewQuoteModal');
    const modal = document.getElementById('newCotizationModal');
    const modalupdate= document.getElementById('updateQuoteModal');
    const visitaSi = document.getElementById("yes");
    const visitaNo = document.getElementById("no");
    const calendarContainer = document.getElementById("calendarContainer");
    const calendarInput = document.getElementById("calendarInput");
    const hourSelectionModal = document.getElementById('hourSelectionModal');
    const hourForm = document.getElementById('hourForm');
    const confirmationModal = document.getElementById('confirmationModal');
    const closeConfirmationModal = document.getElementById('closeConfirmationModal');
    const actionMenus = document.querySelectorAll('.action-menu');


    document.addEventListener("DOMContentLoaded", function () {
        // Buscar TODOS los botones con la clase 'addProductButton'
        document.querySelectorAll(".addProductButton").forEach(button => {
            button.addEventListener("click", function () {
                agregarProducto(button);
            });
        });
    });

    function agregarProducto(button) {
        const modal = button.closest(".modal"); // Detecta en qué modal se hizo clic
        const productList = modal.querySelector(".productList"); // Busca la lista dentro del modal correspondiente

        if (!productList) {
            console.error("No se encontró la lista de productos en el modal.");
            return;
        }

        const productDiv = document.createElement("div");
        productDiv.classList.add("product-entry");

        productDiv.innerHTML = `
        <select class="product-list">
            <option value="" required>Cargando productos...</option>
        </select>
        <input type="number" placeholder="Cantidad" class="product-quantity" min="1" required>
        <input type="number" placeholder="Precio" class="product-price" min="0" required value="0">
        <button type="button" class="remove-product">❌</button>
    `;

        productList.appendChild(productDiv);
        const productPriceInput = productDiv.querySelector(".product-price");
        let quantityInput = productDiv.querySelector(".product-quantity");
        const productSelect = productDiv.querySelector(".product-list");
        const removeButton = productDiv.querySelector(".remove-product");
        cargarProductos(productSelect);


        productSelect.addEventListener("change", function () {
            console.log("LA MONDA SE LLAMA ",productSelect.options[productSelect.selectedIndex].text)
            productPriceInput.value = productSelect.value;
        })
// Evento para detectar si el producto contiene "cable"
        productSelect.addEventListener("change", function () {
            const nameselect = productSelect.options[productSelect.selectedIndex].text
            if (nameselect.toLowerCase().includes("cable", "CABLE", "Cable")) {
                // Reemplazar el input de cantidad por un select con opciones de metros
                const select = document.createElement("select");
                select.classList.add("product-quantity");
                // select.name = "quantity";
                select.innerHTML = `
                <option value="0">Elija</option>
                <option value="10">10 metros</option>
                <option value="20">20 metros</option>
                <option value="30">30 metros</option>
                <option value="50">50 metros</option>
                <option value="100">100 metros</option>
            `;
                quantityInput.replaceWith(select);
                quantityInput = select; // Actualizar la referencia
            } else {
                // Si se borra "cable", vuelve a ser un input de cantidad normal
                if (quantityInput.tagName === "SELECT") {
                    const input = document.createElement("input");
                    input.type = "number";
                    input.classList.add("product-quantity");
                    input.placeholder = "Cantidad";
                    input.min = "1";
                    quantityInput.replaceWith(input);
                    quantityInput = input;
                }
            }

            quantityInput.addEventListener("input", function () {

               addProductToList(productSelect.options[productSelect.selectedIndex].text, quantityInput.value, productPriceInput.value);

            });


        });

        removeButton.addEventListener("click", function() {
            let index = Array.from(productList.children).indexOf(productDiv);
            removeProductFromList(index);
            productDiv.remove();

        });

    }

    /////////
    function addProductToList(productId, quantity, price, hidden) {
        if (window.getComputedStyle(modal).display === 'none') {
            console.log('El modal está oculto');
        } else {
            console.log('El modal está visible');
        }
        productList.push({ id: productId, quantity: quantity, price: price });

        if (window.getComputedStyle(modal).display === 'none') {
            document.getElementById("hiddenProducts").value = JSON.stringify(productList);
        } else {
            document.getElementById("hiddenProducts1").value = JSON.stringify(productList);
        }


    }
    ///////////////////////////
    function removeProductFromList(index,hidden) {
        productList.splice(index, 1);
        console.log('LISTA REMOVIDA', productList);
        if (window.getComputedStyle(modal).display === 'none') {
            document.getElementById("hiddenProducts").value = JSON.stringify(productList);
        } else {
            document.getElementById("hiddenProducts1").value = JSON.stringify(productList);
        }


    }

    ////////////

    // Abrir modal de cotización
    openModalButton.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    // Cerrar modal de cotización
    closeModalButton.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    // Cerrar modal al hacer clic fuera de él
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });
    //


    // DETALLE COTIZACION
    const quoteDetailModal = document.getElementById("quoteDetailModal");
    const closeQuoteDetailModal = document.getElementById("closeQuoteDetailModal");

    document.addEventListener('DOMContentLoaded', function() {
        // Obtener todos los botones "Ver Detalle"
        const botonesDetalle = document.querySelectorAll('.view-quote-detail');

        botonesDetalle.forEach(function(boton) {
            boton.addEventListener('click', function(event) {
                // Evitar que el clic en el botón se propague
                event.stopPropagation();

                // Obtener la fila correspondiente al botón clickeado
                let fila = boton.closest('tr');

                // Acceder a los datos de la fila (ID, Fecha de expiración, Nombre, Teléfono, etc.)
                let id = fila.cells[0].textContent;

                const tbody = document.getElementById("quoteDetailBody");
                tbody.innerHTML = ""
                console.log(id)
                fetch(`quote/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        let total = 0;

                        data.forEach(detail => {
                            total += detail.quantity * detail.unit_price;
                            const row = `
                        <tr>
                            <td>${detail.prod_name}</td>
                            <td>${detail.quantity}</td>
                            <td>$${detail.unit_price.toFixed(2)}</td>
                            <td>${detail.provider_name}</td>
                            <td>${total}</td>
                        </tr>
                        `;
                            tbody.innerHTML += row;
                        });
                        document.getElementById("totalMaterialsPrice").textContent = `$${total}`;
                        document.getElementById("totalPrice").textContent = `$${fila.cells[5].textContent}`;
                    })
                    .catch(error => console.error('Error al obtener los productos:', error));

                fetch(`quote/detailed/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(r => {
                            console.log(r)
                            document.getElementById("helperCost").textContent = `$${r.quote_helper_payday}`;
                            document.getElementById("supervisorCost").textContent = `$${r.quote_supervisor_payday}`;
                            document.getElementById("laborCost").textContent = `$${r.quote_work_total}`;
                            document.getElementById("otherCosts").textContent = `$${r.quote_other_costs}`
                        })
                    })
                quoteDetailModal.classList.remove("hidden");
            });
        });
    });

    closeQuoteDetailModal.addEventListener("click", () => {
        quoteDetailModal.classList.add("hidden");
    });

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

    // Abrir modal de cotización
    openModalButton.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    // Cerrar modal de cotización
    closeModalButton.addEventListener('click', () => {
        modal.classList.add('hidden');
    });

    // CALENDARIO

    let calendarInstance;

    visitaNo.addEventListener("click", () => {
        calendarInput.value = "";
        if (calendarInstance) {
            calendarInstance.destroy();
            calendarInstance = null;
        }
        calendarInput.disabled = true;
        calendarInput.classList.add("hidden");
    });

    visitaSi.addEventListener("click", () => {
        calendarInput.disabled = false;
        calendarInput.classList.remove("hidden");

        if (calendarInstance) {
            calendarInstance.destroy();
        }

        calendarInstance = flatpickr(calendarInput, {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true
        });
    });
    //Consultar si ya es Proyecto
    const openModalProject = document.getElementById("becomeProjectModal"); // Selecciona el modal
    const closeProjectModalButton = document.getElementById('closeBecomeProjectModal'); // Botón de cerrar el modal
    document.addEventListener('DOMContentLoaded', function() {
        const botonesProyecto = document.querySelectorAll('.become-project');

        botonesProyecto.forEach(function(boton) {
            boton.addEventListener('click', function(event) {
                // Evitar que el clic en el botón se propague
                event.stopPropagation();
                let fila = boton.closest('tr');
                let project = fila.cells[0].textContent;

                openModalProject.classList.remove("hidden");
                console.log(project);
                fetch(`projects/${project}`)
                    .then(response => {
                        if (!response.ok){
                            document.getElementById("proj-deposit").max = fila.cells[5].textContent.replace(/,/g, '');
                            document.getElementById("hiddenQuoteId").value = project;
                            openModalProject.classList.remove("hidden");
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log(data);
                        alert(`Cotización ya creada bajo id: ${data.data.id} y nombre: ${data.data.proj_name}`);
                    })
                    .catch(error => {
                        console.error('Error:', error.message);
                    })
            });
        });
    });

    //Cerrar modal proyecto
    closeProjectModalButton.addEventListener('click', () => {

        openModalProject.classList.add('hidden');
    });

    closeProjectModalButton.addEventListener('click', () => {
        openModalProject.classList.add('hidden');
    });


    // // Cerrar mensaje de confirmación
    // closeConfirmationModal.addEventListener('click', () => {
    //     confirmationModal.classList.add('hidden');
    // });


    function cargarProductos(selectElement) {
        fetch("{{route('quoteproducts')}}")
            .then(response => response.json())
            .then(data => {
                selectElement.innerHTML = '<option value="">Selecciona un producto</option>';
                if (data.success && Array.isArray(data.data)) {

                    data.data.forEach(producto => {
                        const option = document.createElement("option");
                        option.value = producto.prod_price_sales;
                        option.textContent = producto.prod_name;
                        selectElement.appendChild(option);
                    });
                } else {
                    console.error("Error en la respuesta del servidor:", data.message || "Formato incorrecto");
                }
            })
            .catch(error => console.error("Error al cargar los productos:", error));
    }
    //// EVENTOS DE ACTUALIZAR
    const closeupdateModalButton = document.getElementById("closeupdateQuoteModal");
    closeupdateModalButton.addEventListener('click', () => {
        console.log("lo opirmio")
        modalupdate.classList.add('hidden');
    });


    function loadQuoteData(quoteId) {
        $.ajax({
            url: `/quotes/${quoteId}/data`,
            type: 'GET',
            success: function(data) {
                // Llenar los campos del formulario
                $('#updateQuoteId').val(quoteId);
                $('#updateClientName').val(data.client_name.client_name);
                $('#updatePhone').val(data.client_ph);
                $('#updateEmail').val(data.client_email);
                $('#updateAddress').val(data.client_address);
                $('#updateEstimatedHours').val(data.quote_estimated_time);
                $('#updateNumAssistants').val(data.quote_helpers);
                $('#updateAssistantSalary').val(data.quote_helper_payday);
                $('#updateSupervisorFee').val(data.quote_supervisor_payday);
                $('#updateOtherCosts').val(data.quote_other_costs);

                // Limpiar y agregar productos
                $('#updateProductsTable tbody').empty();
                data.products.forEach(material => {
                    $('#updateProductsTable tbody').append(`
                <tr data-id="${material.id}">
                    <td>${material.prod_name}</td>   <!-- Cambiado -->
                    <td>${material.quantity}</td>
                    <td>${material.prod_price_sales}</td>
                    <td>${material.total_price}</td>
                    <td><button class="btn btn-danger btn-sm" onclick="removeProduct(${material.id})">Eliminar</button></td>
                </tr>
            `);
                });
                // Mostrar el modal (sin Bootstrap)
                document.getElementById('updateQuoteModal').classList.remove('hidden');
            },
            error: function(error) {
                alert('Error al cargar la cotización.');
            }
        });


    }
    function removeProduct(materialId) {
        var table = document.getElementById("updateProductsTable");
        var rows = table.getElementsByTagName("tr");

        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var button = row.querySelector("button");

            // Verifica si el botón existe y si tiene el material correcto
            if (button && button.getAttribute("onclick").includes(`removeProduct(${materialId})`)) {
                table.deleteRow(i); // Elimina la fila encontrada
                break; // Salimos del bucle una vez eliminada la fila
            }
        }
    }

    function updateProductListFromTable() {

        let table = document.getElementById("updateProductsTable");
        let rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

        for (let i = 0; i < rows.length; i++) {
            let cells = rows[i].getElementsByTagName("td");

            let productId = cells[0].innerText;
            let quantity = parseInt(cells[1].innerText);
            let price = parseFloat(cells[2].innerText);

            productList.push({ id: productId, quantity: quantity, price: price });
        }

        // Actualizar campo oculto
        document.getElementById("hiddenProducts").value = JSON.stringify(productList);
    }
    document.getElementById("updateQuoteForm").addEventListener("submit", function (event) {
        event.preventDefault(); // Evita el envío inmediato

        updateProductListFromTable(); // Recorrer la tabla y actualizar la lista de productos

        this.submit(); // Envía el formulario
    });
</script>
</body>
</html>
