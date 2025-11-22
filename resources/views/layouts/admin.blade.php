<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel de Administración') - DigitalXpress Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- Overlay para móvil -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
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
                <a href="{{ route('admin.repairs') }}" class="nav-link {{ request()->routeIs('admin.repairs*') ? 'active' : '' }}">
                    <i class="fas fa-wrench"></i>
                    <span>Reparaciones</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.inventory') }}" class="nav-link {{ request()->routeIs('admin.inventory') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Inventario</span>
                    @if(isset($inventoryBadgeCount) && $inventoryBadgeCount > 0)
                    <span class="badge-notification">{{ $inventoryBadgeCount }}</span>
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
                    @if(isset($adminNotifications) && $adminNotifications > 0)
                    <span class="notification-badge">{{ $adminNotifications }}</span>
                    @endif
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
                        Centro de Ayuda - Panel de Administración
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="accordion" id="helpAccordion">
                        <!-- Dashboard -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#helpDashboard">
                                    <i class="fas fa-chart-line me-2"></i> Dashboard
                                </button>
                            </h2>
                            <div id="helpDashboard" class="accordion-collapse collapse show" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <p>El dashboard muestra un resumen general del sistema:</p>
                                    <ul>
                                        <li><strong>Total Productos:</strong> Cantidad de productos activos en el sistema</li>
                                        <li><strong>Valor Inventario:</strong> Valor total del inventario calculado</li>
                                        <li><strong>Stock Bajo/Sin Stock:</strong> Alertas de productos con poco o sin stock</li>
                                        <li><strong>Pedidos Recientes:</strong> Últimos 5 pedidos realizados</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Productos -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#helpProducts">
                                    <i class="fas fa-cube me-2"></i> Productos
                                </button>
                            </h2>
                            <div id="helpProducts" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <p>Gestiona todos los productos de tu tienda:</p>
                                    <ul>
                                        <li><strong>Crear:</strong> Agrega nuevos productos con imágenes, precios y descripciones</li>
                                        <li><strong>Editar:</strong> Modifica cualquier información del producto, incluyendo imágenes</li>
                                        <li><strong>Eliminar:</strong> Elimina productos del sistema (se eliminan también las imágenes asociadas)</li>
                                        <li><strong>Filtrar:</strong> Busca productos por nombre, SKU o categoría</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Pedidos -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#helpOrders">
                                    <i class="fas fa-shopping-cart me-2"></i> Pedidos
                                </button>
                            </h2>
                            <div id="helpOrders" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <p>Administra los pedidos de los clientes:</p>
                                    <ul>
                                        <li><strong>Ver Detalles:</strong> Consulta información completa de cada pedido</li>
                                        <li><strong>Editar:</strong> Modifica estado, método de pago y otros datos del pedido</li>
                                        <li><strong>Actualizar Estado:</strong> Cambia el estado del pedido (pendiente, procesando, enviado, entregado, cancelado)</li>
                                        <li><strong>Eliminar:</strong> Elimina pedidos del sistema</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Reparaciones -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#helpRepairs">
                                    <i class="fas fa-wrench me-2"></i> Reparaciones
                                </button>
                            </h2>
                            <div id="helpRepairs" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <p>Gestiona las reparaciones de dispositivos:</p>
                                    <ul>
                                        <li><strong>Crear:</strong> Registra nuevas solicitudes de reparación</li>
                                        <li><strong>Editar:</strong> Actualiza información, estado y costos de reparación</li>
                                        <li><strong>Filtrar:</strong> Busca por número, cliente, dispositivo o estado</li>
                                        <li><strong>Estados:</strong> Pendiente, En Progreso, Completado, Cancelado</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Configuración -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#helpSettings">
                                    <i class="fas fa-cog me-2"></i> Configuración
                                </button>
                            </h2>
                            <div id="helpSettings" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <p>Configura los aspectos generales del sistema:</p>
                                    <ul>
                                        <li><strong>Información de la Tienda:</strong> Nombre, descripción, contacto (se muestra en el footer del sitio)</li>
                                        <li><strong>Envíos:</strong> Configura costos y umbrales de envío gratis</li>
                                        <li><strong>Métodos de Pago:</strong> Habilita o deshabilita métodos de pago disponibles</li>
                                    </ul>
                                    <p class="mt-3"><strong>Nota:</strong> Los cambios en la configuración se reflejan inmediatamente en el sitio web.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Consejos Generales -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#helpTips">
                                    <i class="fas fa-lightbulb me-2"></i> Consejos Generales
                                </button>
                            </h2>
                            <div id="helpTips" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                                <div class="accordion-body">
                                    <ul>
                                        <li>Todos los cambios se guardan automáticamente en la base de datos</li>
                                        <li>Usa los filtros de búsqueda para encontrar rápidamente lo que necesitas</li>
                                        <li>Las imágenes se almacenan en <code>storage/app/public/</code></li>
                                        <li>Puedes editar múltiples campos a la vez en los formularios</li>
                                        <li>Los mensajes de éxito/error aparecen en la parte superior de cada página</li>
                                        <li>El panel es completamente responsive y funciona en móviles</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="{{ route('pages.help-center') }}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i> Ver Centro de Ayuda Completo
                    </a>
                </div>
            </div>
        </div>
    </div>

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

        // Alternar barra lateral (funciona en todas las pantallas)
        const sidebarToggle = document.getElementById('sidebarToggle');
        const adminSidebar = document.getElementById('adminSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const adminMain = document.querySelector('.admin-main');

        if (sidebarToggle && adminSidebar) {
            sidebarToggle.addEventListener('click', function() {
                // Alternar clase 'hidden' para ocultar/mostrar
                adminSidebar.classList.toggle('hidden');
                
                // En móvil, también alternar 'show' para la animación
                if (window.innerWidth <= 768) {
                    adminSidebar.classList.toggle('show');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.toggle('show');
                    }
                }
            });

            // Cerrar sidebar al hacer clic en el overlay (solo móvil)
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    adminSidebar.classList.add('hidden');
                    adminSidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                });
            }

            // Cerrar sidebar al redimensionar ventana (si está oculto y se hace más grande)
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    // En desktop, si está oculto, mantenerlo oculto pero quitar 'show'
                    if (adminSidebar.classList.contains('hidden')) {
                        adminSidebar.classList.remove('show');
                    }
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.remove('show');
                    }
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>

