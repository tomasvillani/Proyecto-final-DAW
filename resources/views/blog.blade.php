@extends('layouts/layout')

@section('title','Blog Grid')

@section('content')
    <!-- Hero Start -->
    <div class="container-fluid bg-primary p-5 bg-hero mb-5">
        <div class="row py-5">
            <div class="col-12 text-center">
                <h1 class="display-2 text-uppercase text-white mb-md-4">Blog Grid</h1>
                <a href="/" class="btn btn-primary py-md-3 px-md-5 me-3">Home</a>
                <a href="/blog" class="btn btn-light py-md-3 px-md-5">Blog</a>
            </div>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Blog Start -->
    <div class="container-fluid p-5">
        <div class="row g-5">
            <!-- Blog list Start -->
            <div class="col-lg-8">
                <div class="row g-5">
                    <div class="col-md-6">
                        <div class="blog-item">
                            <div class="position-relative overflow-hidden rounded-top">
                                <img class="img-fluid" src="img/blog-1.jpg" alt="">
                            </div>
                            <div class="bg-dark d-flex align-items-center rounded-bottom p-4">
                                <div class="flex-shrink-0 text-center text-secondary border-end border-secondary pe-3 me-3">
                                    <span>01</span>
                                    <h6 class="text-light text-uppercase mb-0">January</h6>
                                    <span>2045</span>
                                </div>
                                <a class="h5 text-uppercase text-light" href="">Sed amet tempor amet sit kasd sea lorem</h4></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="blog-item">
                            <div class="position-relative overflow-hidden rounded-top">
                                <img class="img-fluid" src="img/blog-2.jpg" alt="">
                            </div>
                            <div class="bg-dark d-flex align-items-center rounded-bottom p-4">
                                <div class="flex-shrink-0 text-center text-secondary border-end border-secondary pe-3 me-3">
                                    <span>01</span>
                                    <h6 class="text-light text-uppercase mb-0">January</h6>
                                    <span>2045</span>
                                </div>
                                <a class="h5 text-uppercase text-light" href="">Sed amet tempor amet sit kasd sea lorem</h4></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="blog-item">
                            <div class="position-relative overflow-hidden rounded-top">
                                <img class="img-fluid" src="img/blog-3.jpg" alt="">
                            </div>
                            <div class="bg-dark d-flex align-items-center rounded-bottom p-4">
                                <div class="flex-shrink-0 text-center text-secondary border-end border-secondary pe-3 me-3">
                                    <span>01</span>
                                    <h6 class="text-light text-uppercase mb-0">January</h6>
                                    <span>2045</span>
                                </div>
                                <a class="h5 text-uppercase text-light" href="">Sed amet tempor amet sit kasd sea lorem</h4></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="blog-item">
                            <div class="position-relative overflow-hidden rounded-top">
                                <img class="img-fluid" src="img/blog-1.jpg" alt="">
                            </div>
                            <div class="bg-dark d-flex align-items-center rounded-bottom p-4">
                                <div class="flex-shrink-0 text-center text-secondary border-end border-secondary pe-3 me-3">
                                    <span>01</span>
                                    <h6 class="text-light text-uppercase mb-0">January</h6>
                                    <span>2045</span>
                                </div>
                                <a class="h5 text-uppercase text-light" href="">Sed amet tempor amet sit kasd sea lorem</h4></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <nav aria-label="Page navigation">
                          <ul class="pagination pagination-lg justify-content-center m-0">
                            <li class="page-item disabled">
                              <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true"><i class="bi bi-arrow-left"></i></span>
                              </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                              <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true"><i class="bi bi-arrow-right"></i></span>
                              </a>
                            </li>
                          </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- Blog list End -->

        </div>
    </div>
    <!-- Blog End -->
@endsection