<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DM Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{'css/inicio.css'}}">
</head>
<body>
<div class="container">
    <!-- Barra lateral -->
    <aside class="sidebar">
        <h2 class="sidebar-title">Bienvenido</h2>
        <nav>
            <ul class="menu">
                <li class="menu-item">
                    <a href="{{route('home')}}">
                        <i class="fas fa-home menu-icon"></i>
                        <span class="menu-text">Home</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{route('browse')}}">
                        <i class="fas fa-search menu-icon"></i>
                        <span class="menu-text">Browse</span>
                    </a>
                </li>
            </ul>
            <h3 class="sidebar-subtitle">Brochure</h3>
            <ul class="submenu">
                <li class="menu-item">
                    <a href="{{route('quote')}}">
                        <i class="fas fa-file-alt menu-icon"></i>
                        <span class="menu-text">Quotes</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="products.html">
                        <i class="fas fa-tshirt menu-icon"></i>
                        <span class="menu-text">Products</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="reminders.html">
                        <i class="fas fa-smile menu-icon"></i>
                        <span class="menu-text">Reminders</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="logout-section">
            <button class="logout-button">
                <i class="fas fa-sign-out-alt logout-icon"></i>
                <span class="logout-text">Log Out</span>
            </button>
        </div>
    </aside>
    <!-- Contenido principal -->
    <main class="main-content">
        <div class="logo">
            <h1>DM</h1>
            <p>SOLUTION SAS</p>
        </div>
    </main>
</div>
</body>
</html>
