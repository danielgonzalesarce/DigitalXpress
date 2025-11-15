<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DigitalXpress - Tu tienda de tecnología')</title>
    <!-- Script para aplicar tema antes de renderizar (evita flash) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1e3a8a;
            --secondary-color: #3b82f6;
            --accent-color: #10b981;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f9fafb;
        }

        [data-theme="dark"] {
            --primary-color: #3b82f6;
            --secondary-color: #60a5fa;
            --accent-color: #10b981;
            --text-dark: #f9fafb;
            --text-light: #d1d5db;
            --bg-light: #1f2937;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background-color: white;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        [data-theme="dark"] body {
            background-color: #111827;
            color: var(--text-dark);
        }

        [data-theme="dark"] .navbar {
            background-color: #1f2937 !important;
            border-bottom: 1px solid #374151;
        }

        [data-theme="dark"] .navbar-brand,
        [data-theme="dark"] .nav-link {
            color: #f9fafb !important;
        }

        [data-theme="dark"] .category-nav {
            background-color: #1f2937;
        }

        [data-theme="dark"] .category-nav .nav-link {
            color: #d1d5db;
        }

        [data-theme="dark"] .category-nav .nav-link:hover {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--secondary-color);
        }

        [data-theme="dark"] .category-nav .nav-link.active {
            color: var(--secondary-color);
            font-weight: 700;
            border-bottom: 3px solid var(--secondary-color);
            background-color: rgba(59, 130, 246, 0.05);
        }

        [data-theme="dark"] .feature-card {
            background-color: #1f2937;
            color: #f9fafb;
            border: 1px solid #374151;
        }

        [data-theme="dark"] .product-card {
            background-color: #1f2937;
            color: #f9fafb;
            border: 1px solid #374151;
        }

        [data-theme="dark"] .modal-content {
            background-color: #1f2937;
            color: #f9fafb;
        }

        [data-theme="dark"] .modal-header {
            border-bottom: 1px solid #374151;
        }

        [data-theme="dark"] .form-control {
            background-color: #374151;
            border-color: #4b5563;
            color: #f9fafb;
        }

        [data-theme="dark"] .form-control:focus {
            background-color: #374151;
            border-color: var(--primary-color);
            color: #f9fafb;
        }

        [data-theme="dark"] .footer {
            background-color: #0f172a;
            color: #f9fafb;
        }

        [data-theme="dark"] .alert {
            background-color: #1f2937;
            border-color: #374151;
            color: #f9fafb;
        }

        [data-theme="dark"] .dropdown-menu {
            background-color: #1f2937;
            border-color: #374151;
        }

        [data-theme="dark"] .dropdown-item {
            color: #f9fafb;
        }

        [data-theme="dark"] .dropdown-item:hover {
            background-color: #374151;
            color: #f9fafb;
        }

        [data-theme="dark"] .nav-tabs .nav-link {
            background-color: #374151;
            color: #d1d5db;
        }

        [data-theme="dark"] .nav-tabs .nav-link.active {
            background-color: #1f2937;
            color: #f9fafb;
            border-bottom-color: var(--primary-color);
        }

        [data-theme="dark"] .text-muted {
            color: #9ca3af !important;
        }

        [data-theme="dark"] .bg-white {
            background-color: #1f2937 !important;
        }

        [data-theme="dark"] .bg-light {
            background-color: #1f2937 !important;
        }

        [data-theme="dark"] .card {
            background-color: #1f2937;
            border-color: #374151;
            color: #f9fafb;
        }

        [data-theme="dark"] .table {
            color: #f9fafb;
        }

        [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) > td {
            background-color: #374151;
        }

        .theme-toggle {
            background: none;
            border: none;
            color: var(--text-dark);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        [data-theme="dark"] .theme-toggle {
            color: #f9fafb;
        }

        [data-theme="dark"] .theme-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .theme-toggle .fa-moon {
            display: none;
        }

        [data-theme="dark"] .theme-toggle .fa-moon {
            display: inline-block;
        }

        [data-theme="dark"] .theme-toggle .fa-sun {
            display: none;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            position: relative;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link.active {
            border-bottom: 3px solid var(--primary-color);
        }

        [data-theme="dark"] .navbar-nav .nav-link.active {
            border-bottom: 3px solid var(--secondary-color);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 4rem 0;
        }

        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .feature-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-3px);
        }

        .footer {
            background-color: var(--text-dark);
            color: white;
            padding: 3rem 0 1rem;
        }

        .search-bar {
            max-width: 500px;
            margin: 0 auto;
        }

        .category-nav {
            background-color: var(--bg-light);
            padding: 2rem 0;
            width: 100%;
            margin: 0;
        }

        .category-nav nav {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 3rem; /* Espaciado aumentado entre elementos */
            flex-wrap: wrap;
            padding: 0 2rem;
            max-width: 1200px; /* Ancho máximo para limitar el ancho */
            margin: 0 auto; /* Centrar el contenido */
        }

        .category-nav .nav-link {
            color: var(--text-dark);
            font-weight: 500;
            padding: 0.75rem 2rem; /* Padding aumentado */
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 1.1rem; /* Tamaño de fuente ligeramente mayor */
            white-space: nowrap; /* Evita que el texto se divida */
            position: relative;
            border-bottom: 3px solid transparent;
        }

        .category-nav .nav-link:hover {
            background-color: rgba(30, 58, 138, 0.1);
            color: var(--primary-color);
        }

        .category-nav .nav-link.active {
            color: var(--primary-color);
            font-weight: 700;
            border-bottom: 3px solid var(--primary-color);
            background-color: rgba(30, 58, 138, 0.05);
        }

        /* Responsive: ajustar espaciado en pantallas pequeñas */
        @media (max-width: 768px) {
            .category-nav nav {
                gap: 1.5rem; /* Menos espaciado en móviles */
                padding: 0 1rem;
            }

            .category-nav .nav-link {
                padding: 0.5rem 1rem;
                font-size: 1rem;
            }
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 8px 8px 0 0;
            margin-right: 0.5rem;
            background-color: #f8f9fa;
            color: var(--text-light);
        }

        .nav-tabs .nav-link.active {
            background-color: white;
            color: var(--text-dark);
            border-bottom: 2px solid var(--primary-color);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <div class="bg-primary text-white rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fas fa-bolt"></i>
                </div>
                DigitalXpress
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Search Bar -->
                <form class="d-flex mx-auto search-bar" method="GET" action="{{ route('products.index') }}">
                    <div class="input-group">
                        <input class="form-control" type="search" name="search" placeholder="Buscar productos..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- User Actions -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            <i class="fas fa-store me-1"></i> Tienda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('repairs.*') ? 'active' : '' }}" href="{{ route('repairs.index') }}">
                            <i class="fas fa-tools me-1"></i> Reparaciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative {{ request()->routeIs('cart.*') ? 'active' : '' }}" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart me-1"></i> Carrito
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    @auth
                        <li class="nav-item d-flex align-items-center me-2">
                            <button class="theme-toggle" id="themeToggle" aria-label="Cambiar tema">
                                <i class="fas fa-sun"></i>
                                <i class="fas fa-moon"></i>
                            </button>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="me-2">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ Auth::user()->name }}</span>
                                    <small class="text-muted">{{ Auth::user()->email }}</small>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="min-width: 280px;">
                                <li class="dropdown-header">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ Auth::user()->name }}</div>
                                            <small class="text-muted">{{ Auth::user()->email }}</small>
                                        </div>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-edit me-2 text-primary"></i>
                                        <div>
                                            <div class="fw-bold">Mi Perfil</div>
                                            <small class="text-muted">Editar información personal</small>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('repairs.dashboard') }}">
                                        <i class="fas fa-tools me-2 text-warning"></i>
                                        <div>
                                            <div class="fw-bold">Mis Reparaciones</div>
                                            <small class="text-muted">Gestionar servicios técnicos</small>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('cart.index') }}">
                                        <i class="fas fa-shopping-cart me-2 text-success"></i>
                                        <div>
                                            <div class="fw-bold">Mi Carrito</div>
                                            <small class="text-muted">{{ $cartCount }} productos</small>
                                        </div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                        <i class="fas fa-trash-alt me-2 text-danger"></i>
                                        <div>
                                            <div class="fw-bold text-danger">Eliminar Cuenta</div>
                                            <small class="text-muted">Eliminar permanentemente</small>
                                        </div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center">
                                            <i class="fas fa-sign-out-alt me-2 text-secondary"></i>
                                            <div>
                                                <div class="fw-bold">Cerrar Sesión</div>
                                                <small class="text-muted">Salir de tu cuenta</small>
                                            </div>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-user me-1"></i> Ingresar
                            </a>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <button class="theme-toggle ms-3" id="themeToggle" aria-label="Cambiar tema">
                                <i class="fas fa-sun"></i>
                                <i class="fas fa-moon"></i>
                            </button>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Category Navigation -->
    @if(isset($categoriesWithProducts) && $categoriesWithProducts->count() > 0)
    <div class="category-nav">
        <nav class="nav justify-content-center">
            @foreach($categoriesWithProducts as $category)
                <a class="nav-link {{ request('category') == $category->slug || (request()->is('/') && !request('category') && $loop->first) ? 'active' : '' }}" 
                   href="{{ route('products.index', ['category' => $category->slug]) }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </nav>
    </div>
    @endif

    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h5 class="mb-0">DigitalXpress</h5>
                    </div>
                    <p class="text-light">Tu tienda de tecnología de confianza. Los mejores productos, precios competitivos y servicio excepcional.</p>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Categorías</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('products.index', ['category' => 'celulares']) }}" class="text-light text-decoration-none">Celulares</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'laptops']) }}" class="text-light text-decoration-none">Laptops</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'accesorios']) }}" class="text-light text-decoration-none">Accesorios</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'camaras']) }}" class="text-light text-decoration-none">Cámaras</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Soporte</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('pages.help-center') }}" class="text-light text-decoration-none">Centro de Ayuda</a></li>
                        <li><a href="{{ route('pages.warranties') }}" class="text-light text-decoration-none">Garantías</a></li>
                        <li><a href="{{ route('pages.returns') }}" class="text-light text-decoration-none">Devoluciones</a></li>
                        <li><a href="{{ route('pages.contact') }}" class="text-light text-decoration-none">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6 class="fw-bold mb-3">Empresa</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('pages.about') }}" class="text-light text-decoration-none">Sobre Nosotros</a></li>
                        <li><a href="{{ route('pages.terms') }}" class="text-light text-decoration-none">Términos</a></li>
                        <li><a href="{{ route('pages.privacy') }}" class="text-light text-decoration-none">Privacidad</a></li>
                        <li><a href="{{ route('pages.blog') }}" class="text-light text-decoration-none">Blog</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 DigitalXpress. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    @include('auth.login-modal')

    <!-- Delete Account Modal -->
    @auth
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteAccountModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i> Eliminar Cuenta
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-times fa-4x text-danger mb-3"></i>
                        <h4 class="text-danger">¿Estás seguro?</h4>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Esta acción no se puede deshacer.</strong> Se eliminará permanentemente:
                        <ul class="mb-0 mt-2">
                            <li>Tu perfil y datos personales</li>
                            <li>Tu historial de compras</li>
                            <li>Tus reparaciones y servicios</li>
                            <li>Tu carrito de compras</li>
                        </ul>
                    </div>
                    <form method="POST" action="{{ route('profile.destroy') }}" id="deleteAccountForm">
                        @csrf
                        @method('DELETE')
                        <div class="mb-3">
                            <label for="password" class="form-label">Confirma tu contraseña para continuar:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <button type="submit" form="deleteAccountForm" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> Eliminar Cuenta
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script para modo oscuro/claro -->
    <script>
        (function() {
            // Obtener el tema guardado o usar 'light' por defecto
            const savedTheme = localStorage.getItem('theme') || 'light';
            const html = document.documentElement;
            
            // Aplicar el tema guardado
            html.setAttribute('data-theme', savedTheme);
            
            // Función para cambiar el tema
            function toggleTheme() {
                const currentTheme = html.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                html.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
            }
            
            // Agregar event listener a todos los botones de tema
            document.addEventListener('DOMContentLoaded', function() {
                const themeToggles = document.querySelectorAll('#themeToggle');
                themeToggles.forEach(toggle => {
                    toggle.addEventListener('click', toggleTheme);
                });
            });
        })();
    </script>
    
    <!-- Script para mantener el modal abierto si hay errores de validación -->
    @if($errors->has('email') || $errors->has('password') || $errors->has('name') || session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Abrir el modal automáticamente si hay errores
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
            
            // Si hay errores de registro, cambiar al tab de registro
            @if($errors->has('name') || ($errors->has('email') && !$errors->has('password')))
            const registerTab = document.getElementById('register-tab');
            if (registerTab) {
                registerTab.click();
            }
            @endif
        });
    </script>
    @endif
    
    @stack('scripts')
</body>
</html>