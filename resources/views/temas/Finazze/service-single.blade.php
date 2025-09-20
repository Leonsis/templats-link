@extends('temas.Finazze.layouts.app')

@section('content')
<!--===== HERO AREA STARTS =======-->
<div class="inner-pages-section-area" style="background-image: url({{ asset('temas/Finazze/assets/img/all-images/bg/hero-bg1.png') }}); background-position: center; background-repeat: no-repeat; background-size: cover;">
  <div class="container">
    <div class="row">
        <div class="col-lg-12 m-auto">
            <div class="inner-header text-center">
            <h2>Our Service Details</h2>
            <div class="space24"></div>
            <a href="{{ route('home') }}">Home <i class="fa-solid fa-angle-right"></i> <span>Our Service Details</span></a>
            </div>
        </div>
    </div>
  </div>
</div>
<!--===== HERO AREA ENDS =======-->

<!--===== SERVICE AREA STARTS =======-->
<div class="service-details-siderbars-area sp8">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="service-main-detailsarea rightpadding">
                    <div class="img1 image-anime reveal">
                        <img src="{{ asset('temas/Finazze/assets/img/all-images/service/service-img6.png') }}"" alt="">
                    </div>
                    <div class="space32"></div>
                    <h3>Financial Strategy & Advisory</h3>
                    <div class="space16"></div>
                    <p>Our Financial Strategy & Advisory service is designed to provide businesses with expert guidance to navigate the complexities of today’s financial landscape. We offer comprehensive support in crafting strategic financial plans that align with your long-term business goals trends and business needs.</p>
                    <div class="space16"></div>
                    <p>Whether you’re seeking to optimize your capital structure, plan for expansion, or improve profitability, our team of financial experts will work closely with you to analyze current operations, assess risks, and identify growth opportunities. With a data-driven approach, we help you make informed decisions.</p>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="service-details-box">
                                <div class="icons">
                                    <img src="{{ asset('temas/Finazze/assets/img/icons/service28.svg') }}"" alt="">
                                </div>
                                <div class="text">
                                    <a href="{{ route('tema.Finazze.service-single') }}">Estate & Succession Planning</a>
                                    <div class="space16"></div>
                                    <p>Our Estate & Succession Planning service help individuals businesses.</p>
                                </div>
                            </div>

                            <div class="service-details-box">
                                <div class="icons">
                                    <img src="{{ asset('temas/Finazze/assets/img/icons/service30.svg') }}"" alt="">
                                </div>
                                <div class="text">
                                    <a href="{{ route('tema.Finazze.service-single') }}">Audit & Assurance Services</a>
                                    <div class="space16"></div>
                                    <p>Our audit and assurance services provide businesses with financial.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="service-details-box">
                                <div class="icons">
                                    <img src="{{ asset('temas/Finazze/assets/img/icons/service29.svg') }}"" alt="">
                                </div>
                                <div class="text">
                                    <a href="{{ route('tema.Finazze.service-single') }}">Capital Raising & Fundraising</a>
                                    <div class="space16"></div>
                                    <p>We provide personalized planning strategies that align your financial</p>
                                </div>
                            </div>

                            <div class="service-details-box">
                                <div class="icons">
                                    <img src="{{ asset('temas/Finazze/assets/img/icons/service31.svg') }}"" alt="">
                                </div>
                                <div class="text">
                                    <a href="{{ route('tema.Finazze.service-single') }}">Business Process Outsourcing</a>
                                    <div class="space16"></div>
                                    <p>Whether re preparing  retirement, transferring ownership of a family</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space32"></div>
                    <h3>Our Service Benefits</h3>
                    <div class="space16"></div>
                    <p>Our Service Name offers businesses tailored solutions designed to address their unique challenges and financial objectives. We provide comprehensive guidance and expert support in specific areas related to the service, ensuring that your business is equipped with the right tools and strategies to thrive.</p>
                    <div class="space32"></div>
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-6">
                            <div class="img1">
                                <img src="{{ asset('temas/Finazze/assets/img/all-images/service/service-img7.png') }}"" alt="">
                                <div class="play">
                                    <a href="https://www.youtube.com/watch?v=Y8XpQpW5OVY" class="popup-youtube"><i class="fa-solid fa-play"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                          <div class="space30 d-md-none d-block"></div>
                            <div class="list-area">
                                <ul>
                                    <li><img src="{{ asset('temas/Finazze/assets/img/icons/check2.svg') }}"" alt=""> Built on Trust, Driven by Results</li>
                                    <li><img src="{{ asset('temas/Finazze/assets/img/icons/check2.svg') }}"" alt=""> Your Trusted Partner in Finance</li>
                                    <li><img src="{{ asset('temas/Finazze/assets/img/icons/check2.svg') }}"" alt=""> Committed to Your Success</li>
                                    <li><img src="{{ asset('temas/Finazze/assets/img/icons/check2.svg') }}"" alt=""> Audit & Assurance Services</li>
                                    <li><img src="{{ asset('temas/Finazze/assets/img/icons/check2.svg') }}"" alt=""> Revenue Optimization Services</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="space32"></div>
                    <h3>Revenue Optimization Services</h3>
                    <div class="space16"></div>
                    <p>Our experienced team works closely  to assess your current situation, identify growth opportunities, and develop actionable plans that  with your long-term vision. Whether you're looking to mention specific outcomes the service aims to achieve we offer a data-driven approach to help you.</p>
                    <div class="space32"></div>
                    <h3>Explore Our Audit & Assurance Services</h3>
                    <div class="space16"></div>
                    <p>We offer a data-driven approach to help you make informed decisions. With a focus on delivering measurable results, our service empowers you to achieve sustainable success and navigate.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="service-inner-section-area sp1">
    <div class="container"> 
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="heading1 space-margin60 text-center">
                    <h2>More Service</h2>
                </div>
            </div>
        </div> 
      <div class="row">
        <div class="col-lg-4 col-md-6">
          <div class="service3-single-boxarea">
            <div class="icons">
              <img src="{{ asset('temas/Finazze/assets/img/icons/service19.svg') }}"" alt="">
            </div>
            <div class="space24"></div>
            <div class="content">
              <a href="{{ route('tema.Finazze.service-single') }}">Debt Restructuring Services</a>
              <div class="space16"></div>
              <p>Debt Restructuring Services designed to help businesses manage their debt more effectively, providing relief.</p>
              <div class="space24"></div>
              <a href="{{ route('tema.Finazze.service-single') }}" class="readmore">Read More <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
  
        <div class="col-lg-4 col-md-6">
          <div class="service3-single-boxarea">
            <div class="icons">
              <img src="{{ asset('temas/Finazze/assets/img/icons/service20.svg') }}"" alt="">
            </div>
            <div class="space24"></div>
            <div class="content">
              <a href="{{ route('tema.Finazze.service-single') }}">Financial Strategy & Advisory</a>
              <div class="space16"></div>
              <p>Financial Strategy & Advisory service is designed help businesses  all sizes make informed, strategic decisions.</p>
              <div class="space24"></div>
              <a href="{{ route('tema.Finazze.service-single') }}" class="readmore">Read More <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
  
        <div class="col-lg-4 col-md-6">
          <div class="service3-single-boxarea">
            <div class="icons">
              <img src="{{ asset('temas/Finazze/assets/img/icons/service21.svg') }}"" alt="">
            </div>
            <div class="space24"></div>
            <div class="content">
              <a href="{{ route('tema.Finazze.service-single') }}">Tax Planning & Compliance</a>
              <div class="space16"></div>
              <p>We stay updated the latest tax laws & policies helping you navigate complex tax landscapes our personalized.</p>
              <div class="space24"></div>
              <a href="{{ route('tema.Finazze.service-single') }}" class="readmore">Read More <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--===== SERVICE AREA ENDS =======-->

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
