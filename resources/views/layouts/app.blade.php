<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DigitalXpress - Tu tienda de tecnología')</title>
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

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
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
        }

        .category-nav .nav-link:hover,
        .category-nav .nav-link.active {
            background-color: var(--primary-color);
            color: white;
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
                        <a class="nav-link" href="{{ route('products.index') }}">
                            <i class="fas fa-store me-1"></i> Tienda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('repairs.index') }}">
                            <i class="fas fa-tools me-1"></i> Reparaciones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart me-1"></i> Carrito
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    @auth
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
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Category Navigation -->
    <div class="category-nav">
        <nav class="nav justify-content-center">
            <a class="nav-link {{ request('category') == 'accesorios' || (request()->is('/') && !request('category')) ? 'active' : '' }}" href="{{ route('products.index', ['category' => 'accesorios']) }}">Accesorios</a>
            <a class="nav-link {{ request('category') == 'camaras' ? 'active' : '' }}" href="{{ route('products.index', ['category' => 'camaras']) }}">Cámaras</a>
            <a class="nav-link {{ request('category') == 'laptops' ? 'active' : '' }}" href="{{ route('products.index', ['category' => 'laptops']) }}">Laptops</a>
            <a class="nav-link {{ request('category') == 'celulares' ? 'active' : '' }}" href="{{ route('products.index', ['category' => 'celulares']) }}">Celulares</a>
            <a class="nav-link {{ request('category') == 'relojes' ? 'active' : '' }}" href="{{ route('products.index', ['category' => 'relojes']) }}">Relojes</a>
        </nav>
    </div>

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
    @stack('scripts')
</body>
</html>