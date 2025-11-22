<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true" 
     @if($errors->has('email') || $errors->has('password') || $errors->has('name') || session('error') || session('register_error'))
     data-bs-backdrop="static" data-bs-keyboard="false"
     @endif>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="loginModalLabel">Bienvenido a DigitalXpress</h5>
                <button type="button" class="btn-close" 
                        @if($errors->has('email') || $errors->has('password') || $errors->has('name') || session('error') || session('register_error'))
                        onclick="return false;" style="pointer-events: none; opacity: 0.5;"
                        @else
                        data-bs-dismiss="modal"
                        @endif
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">Inicia sesión en tu cuenta o regístrate para acceder a todos nuestros servicios</p>
                
                <!-- Mensajes de error -->
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Por favor, corrige los siguientes errores:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs nav-fill mb-4" id="authTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ ($errors->has('email') && $errors->has('password')) || (!$errors->has('name') && !session('register_error')) ? 'active' : '' }}" 
                                id="login-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#login" 
                                type="button" 
                                role="tab">
                            Iniciar Sesión
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $errors->has('name') || session('register_error') ? 'active' : '' }}" 
                                id="register-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#register" 
                                type="button" 
                                role="tab">
                            Registrarse
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="authTabContent">
                    <!-- Login Tab -->
                    <div class="tab-pane fade {{ ($errors->has('email') && $errors->has('password')) || (!$errors->has('name') && !session('register_error')) ? 'show active' : '' }}" id="login" role="tabpanel">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="Ingresa tu E-mail" 
                                       autocomplete="new-password" 
                                       required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" 
                                       placeholder="Ingresa tu Contraseña" 
                                       autocomplete="new-password" 
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Recordarme
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                Iniciar Sesión
                            </button>
                        </form>
                        
                        <!-- Social Login -->
                        <div class="text-center">
                            <div class="position-relative mb-3">
                                <hr>
                                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">
                                    O CONTINÚA CON
                                </span>
                            </div>
                            <a href="{{ route('pages.development') }}" class="btn btn-outline-danger w-100 text-decoration-none">
                                <i class="fab fa-google me-2"></i>
                                Continuar con Google
                            </a>
                        </div>
                    </div>

                    <!-- Register Tab -->
                    <div class="tab-pane fade {{ $errors->has('name') || session('register_error') ? 'show active' : '' }}" id="register" role="tabpanel">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="register_name" class="form-label">Nombre</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="register_name" name="name" 
                                       value="{{ old('name') }}"
                                       placeholder="Ingresa tu Nombre" 
                                       autocomplete="new-password" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="register_email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="register_email" name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="Ingresa tu E-mail (@gmail.com)" 
                                       autocomplete="new-password" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Solo se permiten direcciones de correo @gmail.com</small>
                            </div>
                            <div class="mb-3">
                                <label for="register_password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="register_password" name="password" 
                                       placeholder="Ingresa tu Contraseña" 
                                       autocomplete="new-password" 
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" 
                                       placeholder="Ingresa una vez más tu Contraseña" 
                                       autocomplete="new-password" 
                                       required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-3">
                                Registrarse
                            </button>
                        </form>
                        
                        <!-- Social Register -->
                        <div class="text-center">
                            <div class="position-relative mb-3">
                                <hr>
                                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">
                                    O CONTINÚA CON
                                </span>
                            </div>
                            <a href="{{ route('pages.development') }}" class="btn btn-outline-danger w-100 text-decoration-none">
                                <i class="fab fa-google me-2"></i>
                                Continuar con Google
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
