<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\Setting::get('store_name', 'DigitalXpress') . ' - Tu tienda de tecnología')</title>
    <!-- Script para aplicar tema antes de renderizar (evita flash) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- Overlay de carga/descarga de batería -->
    <div id="batteryOverlay" class="battery-overlay" style="display: none;">
        <div class="battery-container">
            <div class="battery-message" id="batteryMessage">Cargando...</div>
            <div class="battery-wrapper">
                <div class="battery">
                    <div class="battery-body">
                        <div class="battery-level" id="batteryLevel"></div>
                    </div>
                    <div class="battery-tip"></div>
                </div>
            </div>
            <div class="battery-percentage" id="batteryPercentage">0%</div>
        </div>
    </div>

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
                        <a class="nav-link position-relative d-flex align-items-center justify-content-center {{ request()->routeIs('cart.*') ? 'active' : '' }}" href="{{ route('cart.index') }}" title="Carrito">
                            <i class="fas fa-shopping-cart"></i>
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link position-relative d-flex align-items-center justify-content-center {{ request()->routeIs('favorites.*') ? 'active' : '' }}" href="{{ route('favorites.index') }}" title="Favoritos">
                                <i class="fas fa-heart"></i>
                                @if($favoritesCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
                                        {{ $favoritesCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative d-flex align-items-center justify-content-center {{ request()->routeIs('messages.*') ? 'active' : '' }}" href="{{ route('messages.index') }}" title="Bandeja de Entrada">
                                <i class="fas fa-inbox"></i>
                                @if($unreadMessagesCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
                                        {{ $unreadMessagesCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    @endauth
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
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('messages.index') }}">
                                        <i class="fas fa-inbox me-2 text-info"></i>
                                        <div>
                                            <div class="fw-bold">Bandeja de Entrada</div>
                                            <small class="text-muted">Mensajes con administradores</small>
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
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('favorites.index') }}">
                                        <i class="fas fa-heart me-2 text-danger"></i>
                                        <div>
                                            <div class="fw-bold">Mis Favoritos</div>
                                            <small class="text-muted">{{ $favoritesCount }} productos</small>
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
                        <li class="nav-item d-flex align-items-center ms-2 theme-toggle-wrapper">
                            <button class="theme-toggle" id="themeToggle" aria-label="Cambiar tema">
                                <i class="fas fa-sun"></i>
                                <i class="fas fa-moon"></i>
                            </button>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-user me-1"></i> Ingresar
                            </a>
                        </li>
                        <li class="nav-item d-flex align-items-center ms-2 theme-toggle-wrapper">
                            <button class="theme-toggle" id="themeToggle" aria-label="Cambiar tema">
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
            @php
                $isAuthMessage = str_contains(session('success'), 'Bienvenido') || 
                                 str_contains(session('success'), 'bienvenido') ||
                                 str_contains(session('success'), 'Cuenta creada') ||
                                 str_contains(session('success'), 'cuenta creada') ||
                                 str_contains(session('success'), 'Sesión iniciada') ||
                                 str_contains(session('success'), 'sesión iniciada');
            @endphp
            <div class="alert alert-success alert-dismissible fade show auto-dismiss" 
                 role="alert" 
                 data-auto-dismiss="5000"
                 data-auth-message="{{ $isAuthMessage ? 'true' : 'false' }}">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show auto-dismiss" role="alert" data-auto-dismiss="5000">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show auto-dismiss" role="alert" data-auto-dismiss="5000">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                        <h5 class="mb-0">{{ \App\Models\Setting::get('store_name', 'DigitalXpress') }}</h5>
                    </div>
                    <p class="text-light">{{ \App\Models\Setting::get('store_description', 'Tu tienda de tecnología de confianza. Los mejores productos, precios competitivos y servicio excepcional.') }}</p>
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
                <p class="mb-0">&copy; {{ date('Y') }} {{ \App\Models\Setting::get('store_name', 'DigitalXpress') }}. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Help Button -->
    <button class="help-button" title="Ayuda" data-bs-toggle="modal" data-bs-target="#helpModal">
        <i class="fas fa-question"></i>
    </button>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="helpModalLabel">
                        <i class="fas fa-question-circle me-2"></i>
                        Centro de Ayuda - {{ \App\Models\Setting::get('store_name', 'DigitalXpress') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="accordion" id="helpAccordion">
                        <!-- Compras -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#helpShopping">
                                    <i class="fas fa-shopping-cart me-2"></i> Realizar Compras
                                </button>
                            </h2>
                            <div id="helpShopping" class="accordion-collapse collapse show" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <p>Guía para comprar en nuestra tienda:</p>
                                    <ul>
                                        <li><strong>Navegar Productos:</strong> Explora nuestras categorías o usa la búsqueda para encontrar lo que necesitas</li>
                                        <li><strong>Agregar al Carrito:</strong> Haz clic en "Agregar al Carrito" en cualquier producto</li>
                                        <li><strong>Ver Carrito:</strong> Accede al carrito desde el icono en la barra de navegación</li>
                                        <li><strong>Checkout:</strong> Completa tus datos y selecciona método de pago</li>
                                        <li><strong>Seguimiento:</strong> Recibirás confirmación por email con el número de pedido</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Favoritos -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#helpFavorites">
                                    <i class="fas fa-heart me-2"></i> Lista de Favoritos
                                </button>
                            </h2>
                            <div id="helpFavorites" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <p>Guarda tus productos favoritos:</p>
                                    <ul>
                                        <li><strong>Agregar:</strong> Haz clic en el corazón de cualquier producto</li>
                                        <li><strong>Ver Favoritos:</strong> Accede desde el icono de corazón en la barra de navegación</li>
                                        <li><strong>Eliminar:</strong> Haz clic nuevamente en el corazón o usa el botón de eliminar</li>
                                        <li><strong>Eliminar Múltiples:</strong> Selecciona varios productos y elimínalos en lote</li>
                                    </ul>
                                    <p class="mt-2"><strong>Nota:</strong> Debes iniciar sesión para usar la lista de favoritos.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Reparaciones -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#helpRepairs">
                                    <i class="fas fa-wrench me-2"></i> Servicio de Reparaciones
                                </button>
                            </h2>
                            <div id="helpRepairs" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <p>Servicio técnico profesional:</p>
                                    <ul>
                                        <li><strong>Solicitar Reparación:</strong> Completa el formulario con los datos de tu dispositivo</li>
                                        <li><strong>Subir Imagen:</strong> Adjunta una foto del problema si es posible</li>
                                        <li><strong>Seguimiento:</strong> Consulta el estado de tu reparación en tu panel</li>
                                        <li><strong>Contacto:</strong> Te contactaremos para coordinar la reparación</li>
                                    </ul>
                                    <p class="mt-2"><strong>Contacto:</strong> {{ \App\Models\Setting::get('store_phone', '+51 936068781') }} | {{ \App\Models\Setting::get('store_email', 'soporte@digitalxpress.com') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Métodos de Pago -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#helpPayment">
                                    <i class="fas fa-credit-card me-2"></i> Métodos de Pago
                                </button>
                            </h2>
                            <div id="helpPayment" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <p>Formas de pago disponibles:</p>
                                    <ul>
                                        @if(\App\Models\Setting::get('payment_credit_card', '1') == '1')
                                        <li><strong>Tarjeta de Crédito:</strong> Aceptamos todas las tarjetas principales</li>
                                        @endif
                                        @if(\App\Models\Setting::get('payment_debit_card', '1') == '1')
                                        <li><strong>Tarjeta de Débito:</strong> Pago seguro con tarjeta de débito</li>
                                        @endif
                                        @if(\App\Models\Setting::get('payment_yape', '1') == '1')
                                        <li><strong>Yape:</strong> Pago rápido y seguro con Yape</li>
                                        @endif
                                        @if(\App\Models\Setting::get('payment_cash', '1') == '1')
                                        <li><strong>Efectivo:</strong> Pago en efectivo al recibir el producto</li>
                                        @endif
                                    </ul>
                                    <p class="mt-2"><strong>Seguridad:</strong> Todos los pagos son procesados de forma segura.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Envíos -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#helpShipping">
                                    <i class="fas fa-shipping-fast me-2"></i> Envíos y Entrega
                                </button>
                            </h2>
                            <div id="helpShipping" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <p>Información sobre envíos:</p>
                                    <ul>
                                        @if(\App\Models\Setting::get('shipping_enabled', '1') == '1')
                                        <li><strong>Costo de Envío:</strong> S/ {{ number_format(\App\Models\Setting::get('shipping_cost', 10), 2) }}</li>
                                        @if(\App\Models\Setting::get('free_shipping_threshold', 100) > 0)
                                        <li><strong>Envío Gratis:</strong> En compras mayores a S/ {{ number_format(\App\Models\Setting::get('free_shipping_threshold', 100), 2) }}</li>
                                        @endif
                                        <li><strong>Tiempo de Entrega:</strong> 3-5 días hábiles</li>
                                        <li><strong>Seguimiento:</strong> Recibirás el código de seguimiento por email</li>
                                        @else
                                        <li><strong>Estado:</strong> Los envíos están temporalmente deshabilitados</li>
                                        <li><strong>Alternativa:</strong> Puedes recoger tu pedido en nuestra tienda</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Garantías y Devoluciones -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#helpWarranty">
                                    <i class="fas fa-shield-alt me-2"></i> Garantías y Devoluciones
                                </button>
                            </h2>
                            <div id="helpWarranty" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <p>Políticas de garantía y devolución:</p>
                                    <ul>
                                        <li><strong>Garantía:</strong> Todos nuestros productos tienen garantía del fabricante</li>
                                        <li><strong>Devoluciones:</strong> 30 días para devoluciones sin uso</li>
                                        <li><strong>Reembolsos:</strong> Procesados en 5-7 días hábiles</li>
                                        <li><strong>Contacto:</strong> Para consultas sobre garantías, contáctanos</li>
                                    </ul>
                                    <p class="mt-2">
                                        <a href="{{ route('pages.warranties') }}" class="btn btn-sm btn-outline-primary me-2">Ver Garantías</a>
                                        <a href="{{ route('pages.returns') }}" class="btn btn-sm btn-outline-primary">Ver Devoluciones</a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Contacto -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#helpContact">
                                    <i class="fas fa-envelope me-2"></i> Contacto y Soporte
                                </button>
                            </h2>
                            <div id="helpContact" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <p>¿Necesitas ayuda? Contáctanos:</p>
                                    <ul>
                                        <li><strong>Email:</strong> <a href="https://mail.google.com/mail/?view=cm&fs=1&to=soportedigitalxpress@gmail.com&su=Consulta%20DigitalXpress" target="_blank">{{ \App\Models\Setting::get('store_email', 'soportedigitalxpress@gmail.com') }}</a></li>
                                        <li><strong>Teléfono:</strong> <a href="tel:{{ \App\Models\Setting::get('store_phone', '+51 936068781') }}">{{ \App\Models\Setting::get('store_phone', '+51 936068781') }}</a></li>
                                        @if(\App\Models\Setting::get('store_address'))
                                        <li><strong>Dirección:</strong> {{ \App\Models\Setting::get('store_address') }}</li>
                                        @endif
                                        <li><strong>Horario:</strong> Lunes a Viernes 9:00 AM - 6:00 PM</li>
                                    </ul>
                                    <p class="mt-2">
                                        <a href="{{ route('pages.contact') }}" class="btn btn-sm btn-primary">Formulario de Contacto</a>
                                        <a href="{{ route('pages.help-center') }}" class="btn btn-sm btn-outline-primary ms-2">Centro de Ayuda</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="{{ route('pages.help-center') }}" class="btn btn-primary">
                        <i class="fas fa-external-link-alt me-2"></i> Ver Centro de Ayuda Completo
                    </a>
                </div>
            </div>
        </div>
    </div>

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
    @if($errors->has('email') || $errors->has('password') || $errors->has('name') || session('error') || session('register_error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Abrir el modal automáticamente si hay errores
            const loginModalElement = document.getElementById('loginModal');
            if (loginModalElement) {
                const loginModal = new bootstrap.Modal(loginModalElement, {
                    backdrop: 'static',
                    keyboard: false
                });
            loginModal.show();
            
                // Determinar qué tab mostrar basado en los errores
                @if($errors->has('name') || session('register_error'))
                // Si hay errores de registro
                setTimeout(function() {
            const registerTab = document.getElementById('register-tab');
            if (registerTab) {
                registerTab.click();
            }
                }, 150);
                @elseif($errors->has('email') || $errors->has('password'))
                // Si hay errores de login
                setTimeout(function() {
                    const loginTab = document.getElementById('login-tab');
                    if (loginTab) {
                        loginTab.click();
                    }
                }, 150);
            @endif
            }
        });
    </script>
    @endif
    
    <!-- Script para auto-cerrar alertas después de 5 segundos -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener todas las alertas con la clase auto-dismiss
            const alerts = document.querySelectorAll('.auto-dismiss');
            
            alerts.forEach(function(alert) {
                // Obtener el tiempo de auto-cierre (por defecto 5000ms = 5 segundos)
                const dismissTime = parseInt(alert.getAttribute('data-auto-dismiss')) || 5000;
                
                // Crear un timeout para cerrar la alerta automáticamente
                setTimeout(function() {
                    // Usar Bootstrap para cerrar la alerta con animación
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, dismissTime);
            });
        });
    </script>

    <!-- Función para mostrar notificaciones toast -->
    <script>
        function showNotification(message, type = 'success') {
            // Crear el contenedor de notificaciones si no existe
            let notificationContainer = document.getElementById('notification-container');
            if (!notificationContainer) {
                notificationContainer = document.createElement('div');
                notificationContainer.id = 'notification-container';
                notificationContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
                document.body.appendChild(notificationContainer);
            }

            // Crear la notificación
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show shadow-lg`;
            notification.style.cssText = 'margin-bottom: 10px; animation: slideInRight 0.3s ease-out;';
            
            // Iconos según el tipo
            const icons = {
                'success': '<i class="fas fa-check-circle me-2"></i>',
                'danger': '<i class="fas fa-times-circle me-2"></i>',
                'info': '<i class="fas fa-info-circle me-2"></i>',
                'warning': '<i class="fas fa-exclamation-circle me-2"></i>'
            };

            notification.innerHTML = `
                ${icons[type] || icons.success}
                <strong>${message}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

            notificationContainer.appendChild(notification);

            // Auto-cerrar después de 3 segundos
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 3000);
        }

        // Agregar animación CSS si no existe
        if (!document.getElementById('notification-styles')) {
            const style = document.createElement('style');
            style.id = 'notification-styles';
            style.textContent = `
                @keyframes slideInRight {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    </script>
    
    @stack('scripts')
    
    <script>
        // ============================================
        // ANIMACIONES AL REFRESCAR LA PÁGINA
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            // Animación para elementos principales cuando se carga la página
            const mainContent = document.querySelector('main');
            if (mainContent) {
                mainContent.style.opacity = '0';
                mainContent.style.animation = 'fadeInUp 0.6s ease-out forwards';
            }

            // Animación para cards y elementos del contenido
            const animatedElements = document.querySelectorAll('.card, .feature-card, .product-card, section');
            animatedElements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.animation = `fadeInUp 0.8s ease-out forwards`;
                element.style.animationDelay = `${index * 0.1}s`;
            });

            // Animación especial para mensajes de autenticación
            const authMessages = document.querySelectorAll('.alert[data-auth-message="true"]');
            authMessages.forEach(alert => {
                // Agregar efecto de confeti o celebración visual
                alert.addEventListener('animationend', function() {
                    // Crear efecto de partículas de celebración
                    createCelebrationEffect(alert);
                });
            });

            // Función para crear efecto de celebración
            function createCelebrationEffect(element) {
                const colors = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'];
                const rect = element.getBoundingClientRect();
                
                for (let i = 0; i < 20; i++) {
                    const particle = document.createElement('div');
                    particle.style.position = 'fixed';
                    particle.style.left = rect.left + rect.width / 2 + 'px';
                    particle.style.top = rect.top + rect.height / 2 + 'px';
                    particle.style.width = '8px';
                    particle.style.height = '8px';
                    particle.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    particle.style.borderRadius = '50%';
                    particle.style.pointerEvents = 'none';
                    particle.style.zIndex = '9999';
                    particle.style.animation = `particleFloat ${1 + Math.random()}s ease-out forwards`;
                    
                    const angle = (Math.PI * 2 * i) / 20;
                    const distance = 50 + Math.random() * 50;
                    const x = Math.cos(angle) * distance;
                    const y = Math.sin(angle) * distance;
                    
                    particle.style.setProperty('--x', x + 'px');
                    particle.style.setProperty('--y', y + 'px');
                    
                    document.body.appendChild(particle);
                    
                    setTimeout(() => {
                        particle.remove();
                    }, 1000);
                }
            }

            // Agregar keyframes para partículas si no existen
            if (!document.getElementById('particle-animations')) {
                const style = document.createElement('style');
                style.id = 'particle-animations';
                style.textContent = `
                    @keyframes particleFloat {
                        to {
                            transform: translate(var(--x), var(--y)) scale(0);
                            opacity: 0;
                        }
                    }
                `;
                document.head.appendChild(style);
            }
        });

        // Animación cuando la página se carga completamente
        window.addEventListener('load', function() {
            // Agregar clase de "cargado" al body
            document.body.classList.add('page-loaded');
            
            // Animar elementos que aparecen después de la carga
            const lazyElements = document.querySelectorAll('.lazy-animate');
            lazyElements.forEach((element, index) => {
                setTimeout(() => {
                    element.style.opacity = '0';
                    element.style.animation = 'fadeInUp 0.6s ease-out forwards';
                }, index * 100);
            });
        });
    </script>
</body>
</html>