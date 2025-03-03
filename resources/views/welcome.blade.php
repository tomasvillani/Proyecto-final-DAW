@extends('layouts/layout')

@section('title','Gym Tinajo')

@section('content')
    <!-- Carousel Start -->
    <div class="container-fluid p-0 mb-5">
        <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="w-100" src="img/carousel-1.jpg" alt="Imagen">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3" style="max-width: 900px;">
                            <h5 class="text-white text-uppercase">El Mejor Centro de Gimnasio</h5>
                            <h1 class="display-2 text-white text-uppercase mb-md-4">Fortalece Tu Cuerpo Con Nosotros</h1>
                            <a href="/register" class="btn btn-primary py-md-3 px-md-5 me-3 star-border">Únete</a>
                            <a href="/contact" class="btn btn-light py-md-3 px-md-5">Contáctanos</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="w-100" src="img/carousel-2.jpg" alt="Imagen">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3" style="max-width: 900px;">
                            <h5 class="text-white text-uppercase">El Mejor Centro de Gimnasio</h5>
                            <h1 class="display-2 text-white text-uppercase mb-md-4">Aumenta Tu Fuerza Con Nuestros Entrenadores</h1>
                            <a href="/register" class="btn btn-primary py-md-3 px-md-5 me-3 star-border">Únete</a>
                            <a href="/contact" class="btn btn-light py-md-3 px-md-5">Contáctanos</a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#header-carousel"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    </div>
    <!-- Carousel End -->


    <div class="container-fluid p-5">
        <div class="row gx-5">
            <div class="col-lg-5 mb-5 mb-lg-0" style="min-height: 500px;">
                <div class="position-relative h-100">
                    <img class="position-absolute w-100 h-100 rounded" src="img/ubicacion_gimnasio.png" style="object-fit: cover;">
                </div>
            </div>
            <div class="col-lg-7">
                <div class="mb-4">
                    <h5 class="text-primary text-uppercase">Sobre Nosotros</h5>
                    <h1 class="display-3 text-uppercase mb-0">Bienvenido al gimnasio de Tinajo</h1>
                </div>
                <h4 class="text-body mb-4">Aquí encontrarás toda la información necesaria acerca del gimnasio.</h4>
                <p class="mb-4">Más de 20 años ofreciendo un lugar donde poder forjar una vida saludable para nuestros vecinos. Un gimnasio equipado y listo para ponerse en forma y llevar una vida sana cerca de casa.</p>
                <div class="rounded bg-dark p-5">
                    <ul class="nav nav-pills justify-content-between mb-3">
                        <li class="nav-item w-50">
                            <a class="nav-link text-uppercase text-center w-100 active" data-bs-toggle="pill" href="#pills-1">Sobre Nosotros</a>
                        </li>
                        <li class="nav-item w-50">
                                <a class="nav-link text-uppercase text-center w-100" data-bs-toggle="pill" href="#pills-2">Por qué elegirnos</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pills-1">
                            <p class="text-secondary mb-0">Con más de 20 años de experiencia, nuestro gimnasio es el lugar ideal para construir un estilo de vida saludable. Ofrecemos un espacio completamente equipado para que nuestros vecinos se mantengan en forma y mejoren su bienestar, todo en un ambiente cercano y accesible. ¡Tu salud es nuestra prioridad!</p>
                        </div>
                        <div class="tab-pane fade" id="pills-2">
                            <p class="text-secondary mb-0">Porque combinamos un ambiente cómodo y equipado con tecnología innovadora. Contamos con un chatbot con IA para asistirte en todo momento, respondiendo dudas y ayudándote a sacar el máximo provecho de tu entrenamiento. Además, ofrecemos atención personalizada y un espacio ideal para que alcances tus objetivos de forma práctica y accesible.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Programe Start -->
    <div class="container-fluid programe position-relative px-5 mt-5" style="margin-bottom: 180px;">
        <div class="row g-5 gb-5">
            <div class="col-lg-4 col-md-6">
                <div class="bg-light rounded text-center p-5">
                    <i class="flaticon-six-pack display-1 text-primary"></i>
                    <h3 class="text-uppercase my-4">Tonificación</h3>
                    <p>Tonifica tu cuerpo para tener un mejor aspecto.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="bg-light rounded text-center p-5">
                    <i class="flaticon-barbell display-1 text-primary"></i>
                    <h3 class="text-uppercase my-4">Levantamiento de pesas</h3>
                    <p>El levantamiento de pesas requiere una técnica adecuada.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="bg-light rounded text-center p-5">
                    <i class="flaticon-bodybuilding display-1 text-primary"></i>
                    <h3 class="text-uppercase my-4">Musculación</h3>
                    <p>Muscula tu cuerpo, te ayudaremos en todo lo posible.</p>
                </div>
            </div>
            <div class="col-lg-12 col-md-6 text-center">
                <h1 class="text-uppercase text-light mb-4">30% Descuento para residentes</h1>
                <a href="/register" class="btn btn-primary py-3 px-5">Inscríbete</a>
            </div>
        </div>
    </div>
    <!-- Programe Start -->

    <!-- Team Start -->
    <div class="container-fluid p-5">
        <div class="mb-5 text-center">
            <h5 class="text-primary text-uppercase">Nuestro equipo</h5>
            <h1 class="display-3 text-uppercase mb-0">Entrenadores profesionales</h1>
        </div>
        <div class="row g-5">
            <div class="col-lg-4 col-md-6">
                <div class="team-item position-relative">
                    <div class="position-relative overflow-hidden rounded">
                        <img class="img-fluid w-100" src="img/team-1.jpg" alt="">
                        <div class="team-overlay">
                            <div class="d-flex align-items-center justify-content-start">
                                <a class="btn btn-light btn-square rounded-circle mx-1" href="#"><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-light btn-square rounded-circle mx-1" href="#"><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-light btn-square rounded-circle mx-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="position-absolute start-0 bottom-0 w-100 rounded-bottom text-center p-4" style="background: rgba(34, 36, 41, .9);">
                        <h5 class="text-uppercase text-light">John Deo</h5>
                        <p class="text-uppercase text-secondary m-0">Entrenador</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="team-item position-relative">
                    <div class="position-relative overflow-hidden rounded">
                        <img class="img-fluid w-100" src="img/team-2.jpg" alt="">
                        <div class="team-overlay">
                            <div class="d-flex align-items-center justify-content-start">
                                <a class="btn btn-light btn-square rounded-circle mx-1" href="#"><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-light btn-square rounded-circle mx-1" href="#"><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-light btn-square rounded-circle mx-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="position-absolute start-0 bottom-0 w-100 rounded-bottom text-center p-4" style="background: rgba(34, 36, 41, .9);">
                        <h5 class="text-uppercase text-light">James Taylor</h5>
                        <p class="text-uppercase text-secondary m-0">Monitor</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="team-item position-relative">
                    <div class="position-relative overflow-hidden rounded">
                        <img class="img-fluid w-100" src="img/team-3.jpg" alt="">
                        <div class="team-overlay">
                            <div class="d-flex align-items-center justify-content-start">
                                <a class="btn btn-light btn-square rounded-circle mx-1" href="#"><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-light btn-square rounded-circle mx-1" href="#"><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-light btn-square rounded-circle mx-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="position-absolute start-0 bottom-0 w-100 rounded-bottom text-center p-4" style="background: rgba(34, 36, 41, .9);">
                        <h5 class="text-uppercase text-light">Adam Phillips</h5>
                        <p class="text-uppercase text-secondary m-0">Entrenador</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Team End -->
    

    <!-- Testimonial Start -->
    <div class="container-fluid p-0 my-5">
        <div class="row g-0">
            <div class="col-lg-6" style="min-height: 500px;">
                <div class="position-relative h-100">
                    <img class="position-absolute w-100 h-100" src="img/testimonial.jpg" style="object-fit: cover;">
                </div>
            </div>
            <div class="col-lg-6 bg-dark p-5">
                <div class="mb-5">
                    <h5 class="text-primary text-uppercase">Testimonios</h5>
                    <h1 class="display-3 text-uppercase text-light mb-0">Lo que dicen nuestros miembros</h1>
                </div>
                <div class="owl-carousel testimonial-carousel">
                    <div class="testimonial-item">
                        <p class="fs-4 fw-normal text-light mb-4"><i class="fa fa-quote-left text-primary me-3"></i> "Hace seis meses me uní al gimnasio y mi vida ha cambiado por completo. Los entrenadores son increíbles y siempre están motivándome. ¡He perdido 10 kilos y me siento más fuerte y saludable que nunca!"</p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid rounded-circle" src="img/testimonial-1.jpg" alt="">
                            <div class="ps-4">
                                <h5 class="text-uppercase text-light">Ana López</h5>
                                <span class="text-uppercase text-secondary">Miembro del gimnasio</span>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item">
                        <p class="fs-4 fw-normal text-light mb-4"><i class="fa fa-quote-left text-primary me-3"></i> "Nunca me gustó hacer ejercicio hasta que comencé a entrenar aquí. La variedad de clases y la atención personalizada me motivan a seguir mejorando. He ganado mucha fuerza y resistencia." </p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid rounded-circle" src="img/testimonial-2.jpg" alt="">
                            <div class="ps-4">
                                <h5 class="text-uppercase text-light">Pedro Martínez</h5>
                                <span class="text-uppercase text-secondary">Miembro del gimnasio</span>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item">
                        <p class="fs-4 fw-normal text-light mb-4"><i class="fa fa-quote-left text-primary me-3"></i> "El ambiente en el gimnasio es muy amigable y siempre encuentro la motivación que necesito. Gracias al entrenamiento personalizado, mis resultados han sido más rápidos de lo que imaginaba. ¡Totalmente recomendable!" </p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid rounded-circle" src="img/testimonial-3.jpg" alt="">
                            <div class="ps-4">
                                <h5 class="text-uppercase text-light">Luis González</h5>
                                <span class="text-uppercase text-secondary">Miembro del gimnasio</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->
     
@endsection