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
                                    <a class="nav-link" href="{{route('welcome')}}">Inicio</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('player.login')}}">Iniciar Sesión</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </header>
            <!-- ***** Header End ***** -->