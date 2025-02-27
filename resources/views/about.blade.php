@extends('layouts.layout')

@section('title','Sobre Nosotros')

@section('content')
    <!-- Hero Start -->
    <div class="container-fluid bg-primary p-5 bg-hero mb-5">
        <div class="row py-5">
            <div class="col-12 text-center">
                <h1 class="display-2 text-uppercase text-white mb-md-4">Sobre Nosotros</h1>
                <a href="/" class="btn btn-primary py-md-3 px-md-5 me-3">Inicio</a>
                <a href="/about" class="btn btn-light py-md-3 px-md-5">Sobre Nosotros</a>
            </div>
        </div>
    </div>
    <!-- Hero End -->


    <!-- About Start -->
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

@endsection