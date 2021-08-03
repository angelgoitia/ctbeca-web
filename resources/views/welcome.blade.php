<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>CTBeca</title>
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/logo/logoct.svg') }}" />
        <!-- ***** All CSS Files ***** -->

        <!-- Style css -->
        <link rel="stylesheet" type="text/css" href="{{ asset('landingPage/css/style.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/welcome.css') }}">
    
    </head>
    <body>

        <!--====== Scroll To Top Area Start ======-->
        <div id="scrollUp" title="Scroll To Top">
            <i class="fas fa-arrow-up"></i>
        </div>
        <!--====== Scroll To Top Area End ======-->

        <div class="main">
            @include('navbar.navbarMain')

            <!-- ***** Welcome Area Start ***** -->
            <section id="home" class="section welcome-area bg-overlay overflow-hidden d-flex align-items-center">
                <div class="container">
                    <div class="row align-items-center">
                        <!-- Welcome Intro Start -->
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="welcome-intro">
                                <h1 class="text-white">CTBeca</h1>
                                <p class="text-white my-4">Jugar para ganar criptomoneda.</p>
                                <!-- Store Buttons -->
                                <div class="button-group store-buttons d-flex">
                                    <a href="#">
                                        <img src="{{ asset('landingPage/img/icon/google-play.png') }}" alt="">
                                    </a>
                                    <a href="#">
                                        <img src="{{ asset('landingPage/img/icon/app-store.png') }}" alt="">
                                    </a>
                                </div>
                                <span class="d-inline-block text-white fw-3 font-italic mt-3">* Disponible en iPhone, iPad y todos los dispositivos Android</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <!-- Welcome Thumb -->
                            <div class="welcome-thumb mx-auto" data-aos="fade-left" data-aos-delay="500" data-aos-duration="1000">
                                <img class="img-mobile" src="{{ asset('images/axiesMain.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Shape Bottom -->
                <div class="shape-bottom">
                    <svg viewBox="0 0 1920 310" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="svg replaced-svg">
                        <title>sApp Shape</title>
                        <desc>Created with Sketch</desc>
                        <defs></defs>
                        <g id="sApp-Landing-Page" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="sApp-v1.0" transform="translate(0.000000, -554.000000)" fill="#FFFFFF">
                                <path d="M-3,551 C186.257589,757.321118 319.044414,856.322454 395.360475,848.004007 C509.834566,835.526337 561.525143,796.329212 637.731734,765.961549 C713.938325,735.593886 816.980646,681.910577 1035.72208,733.065469 C1254.46351,784.220361 1511.54925,678.92359 1539.40808,662.398665 C1567.2669,645.87374 1660.9143,591.478574 1773.19378,597.641868 C1848.04677,601.75073 1901.75645,588.357675 1934.32284,557.462704 L1934.32284,863.183395 L-3,863.183395" id="sApp-v1.0"></path>
                            </g>
                        </g>
                    </svg>
                </div>
            </section>
            <!-- ***** Welcome Area End ***** -->

            <!-- ***** Counter Area Start ***** -->
            <section class="section counter-area ptb_50">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-5 col-sm-3 single-counter text-center">
                            <div class="counter-inner p-3 p-md-0">
                                <!-- Counter Item -->
                                <div class="counter-item d-inline-block mb-3">
                                    <span class="counter fw-7">10</span><span class="fw-7">M</span>
                                </div>
                                <h5>Usuario</h5>
                            </div>
                        </div>
                        <div class="col-5 col-sm-3 single-counter text-center">
                            <div class="counter-inner p-3 p-md-0">
                                <!-- Counter Item -->
                                <div class="counter-item d-inline-block mb-3">
                                    <span class="counter fw-7">23</span><span class="fw-7">K</span>
                                </div>
                                <h5>Descarga</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ***** Counter Area End ***** -->

            <!-- ***** Concepts Area Start ***** -->
            <section id="concepts" class="section features-area style-two overflow-hidden ptb_100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <!-- Section Heading -->
                            <div class="section-heading text-center">
                                <h2>Que es Axies Infinity</h2>
                                <p class="d-none d-sm-block mt-4">Axie es un nuevo tipo de juego, parcialmente propiedad y operado por sus jugadores.</p>
                                <p class="d-block d-sm-none mt-4">¡Gana fichas AXS jugando y úsalas para decidir el futuro del juego!.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4 res-margin">
                            <!-- Image Box -->
                            <div class="image-box text-center icon-1 p-5 wow fadeInLeft" data-wow-delay="0.4s">
                                <!-- Featured Image -->
                                <div class="featured-img mb-3">
                                    <img class="avatar-sm" src="{{ asset('images/aventure.webp') }}" alt="">
                                </div>
                                <!-- Icon Text -->
                                <div class="icon-text">
                                    <h3 class="mb-2">Aventuras</h3>
                                    <p>¡Lucha contra Chimera y gana tesoros raros útiles para mejorar tu ejército de Axie!.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 res-margin">
                            <!-- Image Box -->
                            <div class="image-box text-center icon-1 p-5 wow fadeInUp" data-wow-delay="0.2s">
                                <!-- Featured Image -->
                                <div class="featured-img mb-3">
                                    <img class="avatar-sm" src="{{ asset('images/arena_battle.webp') }}" alt="">
                                </div>
                                <!-- Icon Text -->
                                <div class="icon-text">
                                    <h3 class="mb-2">Arena Batalla</h3>
                                    <p>¡Conviértete en una leyenda a través de intensas batallas en la arena PvP! ¡Únase a los torneos abiertos para obtener premios con dinero real!.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <!-- Image Box -->
                            <div class="image-box text-center icon-1 p-5 wow fadeInRight" data-wow-delay="0.4s">
                                <!-- Featured Image -->
                                <div class="featured-img mb-3">
                                    <img class="avatar-sm" src="{{ asset('images/breeding.webp') }}" alt="">
                                </div>
                                <!-- Icon Text -->
                                <div class="icon-text">
                                    <h3 class="mb-2">Cría</h3>
                                    <p>Mezcle y combine padres para crear la descendencia definitiva. ¡Amplíe su colección o inicie su propio negocio de cría y venda sus bebés en el mercado!.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ***** Concepts Area End ***** -->


            <!-- ***** Work Area Start ***** -->
            <section class="section work-area bg-overlay overflow-hidden ptb_100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <!-- Work Content -->
                            <div class="work-content text-center">
                                <h2 class="text-white">Cómo funciona CTBeca?</h2>
                                <p class="d-none d-sm-block text-white my-3 mt-sm-4 mb-sm-5">Contactanos y trabaja con nosotros.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <!-- Single Work -->
                            <div class="single-work text-center p-3">
                                <!-- Work Icon -->
                                <div class="work-icon">
                                    <img class="avatar-md" src="{{ asset('landingPage/img/icon/work/download.png') }}" alt="">
                                </div>
                                <h3 class="text-white py-3">Instala la aplicación</h3>
                                <p class="text-white">Disponible en iPhone, iPad y todos los dispositivos Android</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <!-- Single Work -->
                            <div class="single-work text-center p-3">
                                <!-- Work Icon -->
                                <div class="work-icon">
                                    <img class="avatar-md" src="{{ asset('landingPage/img/icon/work/settings.png') }}" alt="">
                                </div>
                                <h3 class="text-white py-3">Se Configura la cuenta</h3>
                                <p class="text-white">Se llena los datos necesario de jugador</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <!-- Single Work -->
                            <div class="single-work text-center p-3">
                                <!-- Work Icon -->
                                <div class="work-icon">
                                    <img class="avatar-md" src="{{ asset('landingPage/img/icon/work/app.png') }}" alt="">
                                </div>
                                <h3 class="text-white py-3">Disfruta teniendo el contro de los pago!</h3>
                                <p class="text-white">Permite ver control de los pagos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ***** Work Area End ***** -->

            <!-- ***** Profits Plan Area Start ***** -->
            <section id="profits" class="section price-plan-area bg-gray overflow-hidden ptb_100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <!-- Section Heading -->
                            <div class="section-heading text-center">
                                <h2>Ganacia por SLP</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-12 col-sm-10 col-lg-8">
                            <div class="row price-plan-wrapper">
                                <div class="col-12 col-md-6">
                                    <!-- Single Price Plan -->
                                    <div class="single-price-plan text-center p-5 wow fadeInLeft" data-aos-duration="2s" data-wow-delay="0.4s">
                                        <!-- Plan Thumb -->
                                        <div class="plan-thumb">
                                            <img class="avatar-lg" src="{{ asset('images/SLP.png') }}" alt="">
                                        </div>
                                        <!-- Plan Title -->
                                        <div class="plan-title my-2 my-sm-3">
                                            <h4 class="text-uppercase">Menor a 150 SLP (Diario)</h4>
                                        </div>
                                        <!-- Plan Price -->
                                        <div class="plan-price pb-2 pb-sm-3">
                                            <h2 class="fw-7">15<small class="fw-6">%</small> </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mt-4 mt-md-0">
                                    <!-- Single Price Plan -->
                                    <div class="single-price-plan text-center p-5 wow fadeInRight" data-aos-duration="2s" data-wow-delay="0.4s">
                                        <!-- Plan Thumb -->
                                        <div class="plan-thumb">
                                            <img class="avatar-lg" src="{{ asset('images/SLP.png') }}" alt="">
                                        </div>
                                        <!-- Plan Title -->
                                        <div class="plan-title my-2 my-sm-3">
                                            <h4 class="text-uppercase">Mayor a 150 SLP (Diario)</h4>
                                        </div>
                                        <!-- Plan Price -->
                                        <div class="plan-price pb-2 pb-sm-3">
                                            <h2 class="fw-7">20<small class="fw-6">%</small> </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ***** Price Plan Area End ***** -->

            <!-- ***** FAQ Area Start ***** -->
            <section id="faq" class="section faq-area style-two ptb_100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <!-- Section Heading -->
                            <div class="section-heading text-center">
                                <h2 class="text-capitalize">Preguntas frecuentes</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <!-- FAQ Content -->
                            <div class="faq-content">
                                <!-- sApp Accordion -->
                                <div class="accordion" id="sApp-accordion">
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-md-6">
                                            <!-- Single Card -->
                                            <div class="card border-0">
                                                <!-- Card Header -->
                                                <div class="card-header bg-inherit border-0 p-0">
                                                    <h2 class="mb-0">
                                                        <button class="btn px-0 py-3" type="button">
                                                            Cómo instalar CTBeca?
                                                        </button>
                                                    </h2>
                                                </div>
                                                <!-- Card Body -->
                                                <div class="card-body px-0 py-3">
                                                 Abre Play Store o App Store. y busca WhatsApp. Toca INSTALAR.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <!-- Single Card -->
                                            <div class="card border-0">
                                                <!-- Card Header -->
                                                <div class="card-header bg-inherit border-0 p-0">
                                                    <h2 class="mb-0">
                                                        <button class="btn px-0 py-3" type="button">
                                                         ¿Cómo puedo editar mi información personal?
                                                        </button>
                                                    </h2>
                                                </div>
                                                <!-- Card Body -->
                                                <div class="card-body px-0 py-3">
                                                    Debe contactar con el administrador.
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <p class="text-body text-center pt-4 px-3 fw-5">No he encontrado una respuesta adecuada? <a href="#contact">Contactar via WhatsApp.</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ***** FAQ Area End ***** -->

            <!-- ***** Download Area Start ***** -->
            <section class="section download-area overlay-dark ptb_100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-9">
                            <!-- Download Text -->
                            <div class="download-text text-center">
                                <h2 class="text-white">Ctpaga está disponible para todos los dispositivos</h2>
                                
                                <!-- Store Buttons -->
                                <div class="button-group store-buttons d-flex justify-content-center">
                                    <a href="#">
                                        <img src="{{ asset('landingPage/img/icon/google-play.png') }}" alt="">
                                    </a>
                                    <a href="#">
                                        <img src="{{ asset('landingPage/img/icon/app-store.png') }}" alt="">
                                    </a>
                                </div>
                                <span class="d-inline-block text-white fw-3 font-italic mt-3">* Disponible en iPhone, iPad y todos los dispositivos Android</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- ***** Download Area End ***** -->


            <!--====== Contact Area Start ======-->
            <section id="contact" class="contact-area bg-gray ptb_100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <!-- Section Heading -->
                            <div class="section-heading text-center">
                                <h2 class="text-capitalize">Contacto</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-12 col-md-5">
                            <!-- Contact Us -->
                            <div class="contact-us">
                                <p class="mb-3">Con el objeto de brindar atención a los usuarios en tiempo real, ponemos a su disposición la línea:</p>
                                <ul>
                                    <li class="py-2">
                                        <a class="media" href="#">
                                            <div class="social-icon mr-3">
                                                <i class="fas fa-home"></i>
                                            </div>
                                            <span class="media-body align-self-center">Vestibulum nulla libero, convallis, tincidunt suscipit diam, DC 2002</span>
                                        </a>
                                    </li>
                                    <li class="py-2">
                                        <a class="media" href="#">
                                            <div class="social-icon mr-3">
                                                <i class="fas fa-phone-alt"></i>
                                            </div>
                                            <span class="media-body align-self-center">+1 230 456 789-012 345 6789</span>
                                        </a>
                                    </li>
                                    <li class="py-2">
                                        <a class="media" href="#">
                                            <div class="social-icon mr-3">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <span class="media-body align-self-center">exampledomain@domain.com</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 pt-4 pt-md-0">
                            <!-- Contact Box -->
                            <div class="contact-box text-center">
                                <!--Google map-->
                                <div class="mapouter">
                                    <div class="gmap_canvas">
                                        <iframe width="100%" height="400" id="gmap_canvas" src="https://maps.google.com/maps?q=caracas&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--====== Contact Area End ======-->

            <!--====== Height Emulator Area Start ======-->
            <div class="height-emulator d-none d-lg-block"></div>
            <!--====== Height Emulator Area End ======-->

            <!--====== Footer Area Start ======-->
            <footer class="footer-area footer-fixed">
                <!-- Footer Top -->
                <div class="footer-top ptb_100">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-lg-3">
                                <!-- Footer Items -->
                                <div class="footer-items">
                                    <!-- Logo -->
                                    <a class="navbar-brand" href="#">
                                        <img class="logo" src="{{ asset('images/logo/logo.png') }}" alt="">
                                    </a>
                                    <p class="mt-2 mb-3">Comparte tus aplicaciones móviles favoritas con tus amigos.</p>
                                    <!-- Social Icons -->
                                    <div class="social-icons d-flex">
                                        <a class="facebook" href="#">
                                            <i class="fab fa-facebook-f"></i>
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                        <a class="twitter" href="#">
                                            <i class="fab fa-twitter"></i>
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                        <a class="google-plus" href="#">
                                            <i class="fab fa-google-plus-g"></i>
                                            <i class="fab fa-google-plus-g"></i>
                                        </a>
                                        <a class="vine" href="#">
                                            <i class="fab fa-vine"></i>
                                            <i class="fab fa-vine"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <!-- Footer Items -->
                                <div class="footer-items">
                                    <!-- Footer Title -->
                                    <h3 class="footer-title mb-2">Enlaces útiles</h3>
                                    <ul>
                                        <li class="py-2"><a href="#home">Inicio</a></li>
                                        <li class="py-2"><a href="#concepts">Conceptos</a></li>
                                        <li class="py-2"><a href="#profits">Ganancia</a></li>
                                        <li class="py-2"><a href="#faq">Preguntas frecuentes</a></li>
                                        <li class="py-2"><a href="#contact">Contacto</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <!-- Footer Items -->
                                <div class="footer-items">
                                    
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <!-- Footer Items -->
                                <div class="footer-items">
                                    <!-- Footer Title -->
                                    <h3 class="footer-title mb-2">Descarga</h3>
                                    <!-- Store Buttons -->
                                    <div class="button-group store-buttons store-black d-flex flex-wrap">
                                        <a href="https://play.google.com/store/apps/details?id=compralotodo.ctpaga">
                                            <img src="{{ asset('landingPage/img/icon/google-play-black.png') }}" alt="">
                                        </a>
                                        <a href="#">
                                            <img src="{{ asset('landingPage/img/icon/app-store-black.png') }}" alt="">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer Bottom -->
                <div class="footer-bottom">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <!-- Copyright Area -->
                                <div class="copyright-area d-flex flex-wrap justify-content-center justify-content-sm-between text-center py-4">
                                    <!-- Copyright Left -->
                                    <div class="copyright-left">&copy; Copyrights 2020 Ctpaga Todos los derechos reservados.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!--====== Footer Area End ======-->
        </div>
        <!-- ***** All jQuery Plugins ***** -->
        <!-- jQuery(necessary for all JavaScript plugins) -->
        <script src="{{ asset('landingPage/js/jquery/jquery.min.js') }}"></script>

        <!-- Bootstrap js -->
        <script src="{{ asset('landingPage/js/bootstrap/popper.min.js') }}"></script>
        <script src="{{ asset('landingPage/js/bootstrap/bootstrap.min.js') }}"></script>
        <!-- Bootstrap js -->
        <script src="{{ asset('landingPage/js/bootstrap/popper.min.js') }}"></script>

        <!-- Plugins js -->
        <script src="{{ asset('landingPage/js/plugins/plugins.min.js') }}"></script>

        <!-- Active js -->
        <script src="{{ asset('landingPage/js/active.js') }}"></script>
           <!-- laravel app.js  -->
        <script src="{{ asset('js/app.js') }}"></script>

    </body>

</html>
