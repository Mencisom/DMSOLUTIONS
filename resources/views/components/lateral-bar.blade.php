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
