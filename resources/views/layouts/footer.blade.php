<div class="container-fluid bg-dark text-secondary px-5 mt-5">
        <div class="row gx-5">
            <div class="col-lg-8 col-md-6">
                <div class="row gx-5">
                    <div class="col-lg-4 col-md-12 pt-5 mb-5">
                        <h4 class="text-uppercase text-light mb-4">Estar en contacto</h4>
                        <div class="d-flex mb-2">
                            <i class="bi bi-geo-alt text-primary me-2"></i>
                            <p class="mb-0">Av. de los Volcanes, 24, 35560 Tinajo, Las Palmas</p>
                        </div>
                        <div class="d-flex mb-2">
                            <i class="bi bi-telephone text-primary me-2"></i>
                            <p class="mb-0">928 83 81 70</p>
                        </div>
                        <div class="d-flex mt-4">
                            <a class="btn btn-primary btn-square rounded-circle me-2" href="#"><i class="fab fa-whatsapp fa-lg"></i></a>
                            <a class="btn btn-primary btn-square rounded-circle me-2" href="https://es-es.facebook.com/GimnasioTinajo"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-primary btn-square rounded-circle" href="https://www.instagram.com/gymtinajo/"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                        <h4 class="text-uppercase text-light mb-4">Enlaces rápidos</h4>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-secondary mb-2" href="/"><i class="bi bi-arrow-right text-primary me-2"></i>Inicio</a>
                            <a class="text-secondary mb-2" href="/about"><i class="bi bi-arrow-right text-primary me-2"></i>Sobre Nosotros</a>
                            <a class="text-secondary mb-2" href="/classes"><i class="bi bi-arrow-right text-primary me-2"></i>Horarios</a>
                            <a class="text-secondary mb-2" href="/trainers"><i class="bi bi-arrow-right text-primary me-2"></i>Personal</a>
                            <a class="text-secondary mb-2" href="/blog"><i class="bi bi-arrow-right text-primary me-2"></i>Eventos</a>
                            <a class="text-secondary" href="/contact"><i class="bi bi-arrow-right text-primary me-2"></i>Contacto</a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                        <h4 class="text-uppercase text-light mb-4">Más visitados</h4>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-secondary mb-2" href="/"><i class="bi bi-arrow-right text-primary me-2"></i>Inicio</a>
                            <a class="text-secondary mb-2" href="/about"><i class="bi bi-arrow-right text-primary me-2"></i>Sobre Nosotros</a>
                            <a class="text-secondary mb-2" href="/classes"><i class="bi bi-arrow-right text-primary me-2"></i>Horarios</a>
                            <a class="text-secondary mb-2" href="/trainers"><i class="bi bi-arrow-right text-primary me-2"></i>Personal</a>
                            <a class="text-secondary mb-2" href="/events"><i class="bi bi-arrow-right text-primary me-2"></i>Eventos</a>
                            <a class="text-secondary" href="/contact"><i class="bi bi-arrow-right text-primary me-2"></i>Contacto</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="d-flex flex-column align-items-center justify-content-center text-center h-100 bg-primary p-5">
                    <h4 class="text-uppercase text-white mb-4">Newsletter</h4>
                    <h6 class="text-uppercase text-white">Suscríbete a nuestro Newsletter</h6>
                    <p class="text-light">Para estar al tanto de todos los eventos de nuestro gimnasio</p>
                    <form action="{{ route('inscribirse') }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="email" class="form-control border-white p-3" name="email" placeholder="Tu Email" required>
                            <button class="btn btn-dark" type="POST">Inscribirse</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4 py-lg-0 px-5" style="background: #111111;">
        <div class="row gx-5">
            <div class="col-lg-8">
                <div class="py-lg-4 text-center">
                    <p class="text-secondary mb-0">&copy; <a class="text-light fw-bold" href="#">Gym Tinajo</a>. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </div>