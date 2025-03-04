<div class="container-fluid bg-dark px-0">
    <div class="row gx-0">
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark p-3 p-lg-0 px-lg-5 w-100">
            <div class="container d-flex justify-content-between align-items-center w-100">

                <!-- LOGO -->
                <div class="col-lg-3 d-flex justify-content-start">
                    <a href="/" class="navbar-brand">
                        <img class="m-0 display-4 w-50 p-1" src="{{ asset('img/AAFF-LOGO-GYM-TINAJO-ELE.png') }}">
                    </a>
                </div>

                <!-- BOTÓN TOGGLER -->
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- MENÚ DE NAVEGACIÓN -->
                <div class="collapse navbar-collapse justify-content-around" id="navbarCollapse">
                    <div class="navbar-nav text-center">
                        @if (Auth::check() && Auth::user()->tipo_usuario == 'admin')
                            <!-- SOLO PARA ADMIN -->
                            <a href="/clientes" class="nav-item nav-link">Clientes</a>
                            <a href="/reservas" class="nav-item nav-link">Reservas</a>
                            <a href="/eventos" class="nav-item nav-link">Eventos</a>

                        @elseif (Auth::check() && Auth::user()->tipo_usuario == 'cliente')
                            <!-- SOLO PARA CLIENTES -->
                            <a href="/" class="nav-item nav-link">Inicio</a>
                            <a href="/about" class="nav-item nav-link">Sobre Nosotros</a>
                            <a href="/classes" class="nav-item nav-link">Clases</a>
                            <a href="/trainers" class="nav-item nav-link">Personal</a>
                            <a href="/eventos" class="nav-item nav-link">Eventos</a>
                            <a href="/contact" class="nav-item nav-link">Contacto</a>
                            <a href="{{ route('mis-reservas.index', ['userId' => Auth::user()->id]) }}" class="nav-item nav-link">Mis Reservas</a>

                        @else
                            <!-- SOLO PARA INVITADOS (NO LOGUEADOS) -->
                            <a href="/" class="nav-item nav-link">Inicio</a>
                            <a href="/about" class="nav-item nav-link">Sobre Nosotros</a>
                            <a href="/classes" class="nav-item nav-link">Clases</a>
                            <a href="/trainers" class="nav-item nav-link">Personal</a>
                            <a href="/eventos" class="nav-item nav-link">Eventos</a>
                            <a href="/contact" class="nav-item nav-link">Contacto</a>
                        @endif
                    </div>
                </div>

                <!-- ACCESO USUARIO -->
                <div class="col-lg-3 text-end">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-primary py-md-2 px-md-4">Regístrate</a>
                    @else
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Perfil</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Cerrar Sesión</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>

            </div>
        </nav>
    </div>
</div>
