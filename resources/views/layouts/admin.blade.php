<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel de Administración') - DigitalXpress Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #3b82f6;
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
            color: white;
            padding: 0;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .admin-sidebar .logo-section {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .admin-sidebar .logo-section a {
            text-decoration: none;
            color: inherit;
            display: block;
            transition: opacity 0.3s;
        }

        .admin-sidebar .logo-section a:hover {
            opacity: 0.8;
        }

        .admin-sidebar .logo-section h4 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .admin-sidebar .logo-section p {
            margin: 0;
            font-size: 0.85rem;
            opacity: 0.8;
        }

        .admin-sidebar .nav-menu {
            padding: 1rem 0;
        }

        .admin-sidebar .nav-item {
            margin: 0.25rem 0;
        }

        .admin-sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .admin-sidebar .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: white;
        }

        .admin-sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left-color: white;
            font-weight: 600;
        }

        .admin-sidebar .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
        }

        .admin-sidebar .badge-notification {
            margin-left: auto;
            background: #ef4444;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .admin-sidebar .user-profile {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.1);
        }

        .admin-sidebar .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #10b981;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .admin-sidebar .user-info h6 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .admin-sidebar .user-info p {
            margin: 0;
            font-size: 0.75rem;
            opacity: 0.8;
        }

        .admin-sidebar .user-badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .admin-sidebar .logout-btn {
            width: 100%;
            margin-top: 1rem;
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            padding: 0.5rem;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }

        .admin-sidebar .logout-btn:hover {
            background: rgba(255,255,255,0.2);
        }

        /* Main Content */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* Header */
        .admin-header {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .admin-header .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-header .hamburger {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
        }

        .admin-header .page-title h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            color: #111827;
        }

        .admin-header .page-title p {
            margin: 0;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .admin-header .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-header .search-bar {
            position: relative;
        }

        .admin-header .search-bar input {
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            width: 250px;
            font-size: 0.875rem;
        }

        .admin-header .search-bar i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .admin-header .notification-btn {
            position: relative;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #6b7280;
            cursor: pointer;
        }

        .admin-header .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 600;
        }


        /* Content Area */
        .admin-content {
            padding: 2rem;
        }

        /* Modal de Confirmación Personalizado */
        #confirmModal .modal-dialog {
            max-width: 450px;
        }

        .confirm-modal-content {
            background: #282828;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        }

        .confirm-modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
        }

        .confirm-modal-header .modal-title {
            color: #ffffff;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .confirm-modal-body {
            padding: 2rem 1.5rem;
            color: #ffffff;
            font-size: 1rem;
            line-height: 1.6;
        }

        .confirm-modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .confirm-cancel-btn {
            background: #3a3a3a;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #ffffff;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .confirm-cancel-btn:hover {
            background: #4a4a4a;
            border-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        .confirm-accept-btn {
            background: #3366ff;
            border: none;
            color: #ffffff;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .confirm-accept-btn:hover {
            background: #2551e6;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(51, 102, 255, 0.4);
        }

        /* Overlay del Modal */
        #confirmModal .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
        }

        /* Animación de entrada */
        #confirmModal.modal.fade .modal-dialog {
            transform: scale(0.9);
            transition: transform 0.3s ease-out;
        }

        #confirmModal.modal.show .modal-dialog {
            transform: scale(1);
        }

        /* Cards */
        .metric-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .metric-card .metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .metric-card .metric-value {
            font-size: 2rem;
            font-weight: 700;
            color: #111827;
            margin: 0.5rem 0;
        }

        .metric-card .metric-label {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }

        .metric-card .metric-change {
            font-size: 0.75rem;
            color: #10b981;
            margin-top: 0.5rem;
        }

        /* Alert Banner */
        .alert-banner {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .alert-banner i {
            color: #f59e0b;
            font-size: 1.25rem;
        }

        .alert-banner a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            margin-left: auto;
        }

        /* Orders Section */
        .orders-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-top: 1.5rem;
        }

        .orders-section h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 1.5rem;
        }

        .order-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            transition: background 0.2s;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item:hover {
            background: #f9fafb;
        }

        .order-item .order-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #eff6ff;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .order-item .order-info {
            flex: 1;
        }

        .order-item .order-info h6 {
            margin: 0;
            font-size: 0.875rem;
            font-weight: 600;
            color: #111827;
        }

        .order-item .order-info p {
            margin: 0;
            font-size: 0.75rem;
            color: #6b7280;
        }

        .order-item .order-total {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
        }

        .order-status {
            padding: 0.25rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .order-status.pending {
            background: #f3f4f6;
            color: #6b7280;
        }

        .order-status.processing {
            background: #dbeafe;
            color: #2563eb;
        }

        .order-status.completed {
            background: #f3f4f6;
            color: #374151;
        }

        /* Help Button */
        .help-button {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .help-button:hover {
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="logo-section">
            <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: inherit; display: block;">
                <h4><i class="fas fa-shield-alt me-2"></i>Admin Panel</h4>
                <p>DigitalXpress Pro</p>
            </a>
        </div>

        <nav class="nav-menu">
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.products') }}" class="nav-link {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                    <i class="fas fa-cube"></i>
                    <span>Productos</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.categories') }}" class="nav-link {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>Categorías</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('repairs.index') }}" class="nav-link {{ request()->routeIs('repairs.*') ? 'active' : '' }}">
                    <i class="fas fa-wrench"></i>
                    <span>Reparaciones</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.inventory') }}" class="nav-link {{ request()->routeIs('admin.inventory') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Inventario</span>
                    @php
                        $lowStockCount = \App\Models\Product::where('is_active', true)
                            ->where('stock_quantity', '<', 10)
                            ->where('stock_quantity', '>', 0)
                            ->count();
                    @endphp
                    @if($lowStockCount > 0)
                    <span class="badge-notification">{{ $lowStockCount }}</span>
                    @endif
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Pedidos</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.revenue') }}" class="nav-link {{ request()->routeIs('admin.revenue') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Análisis</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </a>
            </div>
        </nav>

        <div class="user-profile">
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="user-info">
                <h6>{{ Auth::user()->name }}</h6>
                <p>{{ Auth::user()->email }}</p>
                <span class="user-badge">Administrador</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="admin-main">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-left">
                <button class="hamburger" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="page-title">
                    <h2>@yield('page-title', 'Dashboard')</h2>
                    <p>@yield('page-subtitle', 'Panel de administración profesional • DigitalXpress')</p>
                </div>
            </div>
            <div class="header-right">
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar...">
                </div>
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">5</span>
                </button>
            </div>
        </header>

        <!-- Content -->
        <main class="admin-content">
            @yield('content')
        </main>
    </div>

    <!-- Modal de Confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content confirm-modal-content">
                <div class="modal-header confirm-modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Confirmar Acción
                    </h5>
                </div>
                <div class="modal-body confirm-modal-body">
                    <p id="confirmModalMessage" class="mb-0"></p>
                </div>
                <div class="modal-footer confirm-modal-footer">
                    <button type="button" class="btn btn-secondary confirm-cancel-btn" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <form id="confirmModalForm" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary confirm-accept-btn">
                            <i class="fas fa-check me-2"></i>Aceptar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Button -->
    <button class="help-button" title="Ayuda">
        <i class="fas fa-question"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script para Modal de Confirmación -->
    <script>
        // Función global para mostrar modal de confirmación
        function showConfirmModal(message, formAction, method = 'POST') {
            const modalElement = document.getElementById('confirmModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
            const modalMessage = document.getElementById('confirmModalMessage');
            const modalForm = document.getElementById('confirmModalForm');
            
            modalMessage.textContent = message;
            modalForm.action = formAction;
            
            // Limpiar inputs de método anteriores
            const existingMethod = modalForm.querySelector('input[name="_method"]');
            if (existingMethod) {
                existingMethod.remove();
            }
            
            // Agregar método DELETE si es necesario
            if (method === 'DELETE') {
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                modalForm.appendChild(methodInput);
            }
            
            // Obtener token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                              document.querySelector('input[name="_token"]')?.value;
            if (csrfToken) {
                const existingToken = modalForm.querySelector('input[name="_token"]');
                if (existingToken) {
                    existingToken.value = csrfToken;
                } else {
                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = csrfToken;
                    modalForm.appendChild(tokenInput);
                }
            }
            
            modal.show();
        }

        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.admin-sidebar').classList.toggle('show');
        });
    </script>
    @stack('scripts')
</body>
</html>

