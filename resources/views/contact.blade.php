@extends('layouts.layout')

@section('title','Contacto')

@section('content')
    <!-- Hero Start -->
    <div class="container-fluid bg-primary p-5 bg-hero mb-5">
        <div class="row py-5">
            <div class="col-12 text-center">
                <h1 class="display-2 text-uppercase text-white mb-md-4">Contacto</h1>
                <a href="/" class="btn btn-primary py-md-3 px-md-5 me-3">Inicio</a>
                <a href="/contact" class="btn btn-light py-md-3 px-md-5">Contacto</a>
            </div>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Contact Start -->
    <div class="container-fluid p-5">
        <div class="mb-5 text-center">
            <h5 class="text-primary text-uppercase">Contáctanos</h5>
            <h1 class="display-3 text-uppercase mb-0">Estar en contacto</h1>
        </div>
        <div class="row g-5 mb-5">
            <div class="col-lg-4">
                <div class="d-flex flex-column align-items-center bg-dark rounded text-center py-5 px-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fa fa-map-marker-alt fs-4 text-white"></i>
                    </div>
                    <h5 class="text-uppercase text-primary">Visítanos</h5>
                    <p class="text-secondary mb-0">Av. de los Volcanes, 24, 35560 Tinajo, Las Palmas</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="d-flex flex-column align-items-center bg-dark rounded text-center py-5 px-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fa fa-envelope fs-4 text-white"></i>
                    </div>
                    <h5 class="text-uppercase text-primary">Nuestro email</h5>
                    <p class="text-secondary mb-0">gymtinajo@gmail.com</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="d-flex flex-column align-items-center bg-dark rounded text-center py-5 px-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fa fa-phone fs-4 text-white"></i>
                    </div>
                    <h5 class="text-uppercase text-primary">Llámanos</h5>
                    <p class="text-secondary mb-0">928 83 81 70</p>
                </div>
            </div>
        </div>
        @if(Auth::guest() || (Auth::check() && Auth::user()->tipo_usuario == 'cliente'))
            <div class="row g-0">
                <div class="col-lg-6">
                    <div class="bg-dark p-5">
                        <form id="contact-form" action="{{ route('enviar_correo') }}" method="POST">
                            @csrf <!-- Token CSRF para seguridad -->
                            
                            <div class="row g-3">
                                <div class="col-6">
                                    <input type="text" name="name" class="form-control bg-light border-0 px-4" placeholder="Tu nombre" required style="height: 55px;">
                                </div>
                                <div class="col-6">
                                    <input type="email" name="email" class="form-control bg-light border-0 px-4" placeholder="Tu correo electrónico" required style="height: 55px;">
                                </div>
                                <div class="col-12">
                                    <input type="text" name="subject" class="form-control bg-light border-0 px-4" placeholder="Asunto" required style="height: 55px;">
                                </div>
                                <div class="col-12">
                                    <textarea name="message" class="form-control bg-light border-0 px-4 py-3" rows="4" placeholder="Mensaje" required></textarea>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3" type="submit">Enviar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <iframe class="w-100"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3487.526457866717!2d-13.679721700000002!3d29.0605843!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xc46225fd32b5821%3A0x15a555d3aad5f880!2sGimnasio%20Municipal%20de%20Tinajo!5e0!3m2!1ses!2ses!4v1740393812229!5m2!1ses!2ses"
                        frameborder="0" style="height: 457px; border:0;" allowfullscreen="" aria-hidden="false"
                        tabindex="0"></iframe>
                </div>
            </div>
        @endif
    </div>
    <!-- Contact End -->
@endsection