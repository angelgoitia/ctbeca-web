            <!-- ***** Header Start ***** -->
            <header class="navbar navbar-sticky navbar-expand-lg navbar-dark">
                <div class="container position-relative">
                    <a class="navbar-brand" href="{{route('welcome')}}">
                        <img class="navbar-brand-regular" src="{{ asset('images/logo/logoWhite.png') }}" width="180px" alt="brand-logo">
                        <img class="navbar-brand-sticky" src="{{ asset('images/logo/logoWhite.png') }}" width="180px" alt="sticky brand-logo">
                    </a>
                    <button class="navbar-toggler d-lg-none" type="button" data-toggle="navbarToggler" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="navbar-inner">
                        <!--  Mobile Menu Toggler -->
                        <button class="navbar-toggler d-lg-none" type="button" data-toggle="navbarToggler" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <nav>
                            <ul class="navbar-nav" id="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link scroll" href="#home">Inicio</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link scroll" href="#concepts">Conceptos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link scroll" href="#profits">Ganancia</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link scroll" href="#contact">Contacto</a>
                                </li>
                                @if(Auth::guard('admin')->check())
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Ingresar</a>
                                    </li>
                                @elseif(Auth::guard('web')->check())
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Ingresar</a>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Iniciar Sesión</a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </header>
            <!-- ***** Header End ***** -->