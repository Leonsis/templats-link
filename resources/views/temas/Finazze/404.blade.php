@extends('temas.Finazze.layouts.app')

@section('content')
<!--===== HERO AREA STARTS =======-->
<div class="inner-pages-section-area" style="background-image: url({{ asset('temas/Finazze/assets/img/all-images/bg/hero-bg1.png') }}); background-position: center; background-repeat: no-repeat; background-size: cover;">
  <div class="container">
    <div class="row">
        <div class="col-lg-12 m-auto">
            <div class="inner-header text-center">
            <h2>Error 404</h2>
            <div class="space24"></div>
            <a href="{{ route('home') }}">Home <i class="fa-solid fa-angle-right"></i> <span>Error 404</span></a>
            </div>
        </div>
    </div>
  </div>
</div>
<!--===== HERO AREA ENDS =======-->

<!--===== OTHERS AREA STARTS =======-->
<div class="error-section-area sp1">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="imgages-content-area">
                    <div class="img1">
                        <img src="{{ asset('temas/Finazze/assets/img/all-images/others/error-img1.png') }}"" alt="">
                    </div>
                    <div class="space48"></div>
                    <div class="text heading1 text-center">
                        <h2> Sorry, Page Not Found!</h2>
                        <div class="space16"></div>
                        <p>Sorry, the page you are looking for doesn’t exist or has <br class="d-lg-block d-none"> been moved. Here are some helpful links.</p>
                        <div class="space32"></div>
                        <div class="btn-area1">
                            <a href="{{ route('home') }}" class="vl-btn1">back to home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--===== OTHERS AREA ENDS =======-->

<!--===== CTA AREA STARTS =======-->
<div class="cta1-section-area sp4">
  <div class="container">
    <div class="row">
      <div class="col-lg-5">
        <div class="cta-header">
          <h2 class="text-anime-style-3">Empowering Businesses, One Step at a Time</h2>
          <div class="space32"></div>
          <div class="btn-area1" data-aos="fade-left" data-aos-duration="1000">
            <a href="{{ route('contato') }}" class="vl-btn1">Schedule a Consultation</a>
            <a href="{{ route('contato') }}" class="vl-btn1 btn2">Start Your Journey</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="img1">
    <img src="{{ asset('temas/Finazze/assets/img/all-images/cta/cta-img1.png') }}"" alt="">
  </div>
</div>
<!--===== CTA AREA ENDS =======-->
@endsection
