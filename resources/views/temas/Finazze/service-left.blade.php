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
            <div class="col-lg-4">
                <div class="service-side-widget">
                    <div class="search-area">
                        <h3>Search</h3>
                        <div class="space24"></div>
                        <form action="{{ route('contato.enviar') }}">@csrf
                            <input type="text" placeholder="Search...">
                            <button type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                <path d="M22.5757 14.2934C22.5757 18.6724 18.9564 22.2528 14.4539 22.2528C9.95128 22.2528 6.33203 18.6724 6.33203 14.2934C6.33203 9.91439 9.95128 6.33398 14.4539 6.33398C18.9564 6.33398 22.5757 9.91439 22.5757 14.2934Z" stroke="white" stroke-width="2"/>
                                <path d="M25.9545 27.3757C26.3485 27.7627 26.9873 27.7627 27.3814 27.3757C27.7754 26.9887 27.7754 26.3612 27.3814 25.9742L25.9545 27.3757ZM19.4389 20.9761L25.9545 27.3757L27.3814 25.9742L20.8658 19.5747L19.4389 20.9761Z" fill="white"/>
                              </svg></button>
                        </form>
                    </div>
                    <div class="space30"></div>
                    <div class="categories-area">
                        <h3>Our Services</h3>
                        <div class="space4"></div>
                        <ul>
                            <li><a href="service-left.html#">Supply Chain Management <span><i class="fa-solid fa-angle-right"></i></span></a></li>
                            <li><a href="service-left.html#">Mergers And Acquisitions <span><i class="fa-solid fa-angle-right"></i></span></a></li>
                            <li><a href="service-left.html#">Investment Strategies <span><i class="fa-solid fa-angle-right"></i></span></a></li>
                            <li><a href="service-left.html#">Wealth Management <span><i class="fa-solid fa-angle-right"></i></span></a></li>
                            <li><a href="service-left.html#">Transformation in Finance <span><i class="fa-solid fa-angle-right"></i></span></a></li>
                        </ul>
                    </div>
                    <div class="space30"></div>
                    <div class="help-area">
                        <h3>If You Need Any Help <br class="d-lg-block d-none"> Contact With Us</h3>
                        <div class="space24"></div>
                        <div class="btn-area1">
                            <a href="tel:+1234567890"><span><svg xmlns="http://www.w3.org/2000/svg" width="14" height="18" viewBox="0 0 14 18" fill="none">
                                <path d="M1.64362 5.57135C1.40487 3.99885 2.51362 2.58635 4.20737 2.06885C4.50794 1.97695 4.83224 2.00311 5.11419 2.142C5.39614 2.28089 5.61453 2.52205 5.72487 2.81635L6.26862 4.26635C6.35615 4.49962 6.37198 4.75372 6.31407 4.99605C6.25616 5.23837 6.12716 5.45786 5.94362 5.62635L4.32612 7.1076C4.24639 7.18077 4.18698 7.27335 4.15368 7.37631C4.12038 7.47927 4.11433 7.5891 4.13612 7.6951L4.15112 7.7601L4.18987 7.9226C4.39103 8.71216 4.69753 9.47105 5.10112 10.1789C5.54076 10.931 6.08635 11.6159 6.72112 12.2126L6.77112 12.2576C6.85187 12.3293 6.94981 12.3788 7.0554 12.4014C7.16099 12.4239 7.27062 12.4188 7.37362 12.3864L9.46487 11.7276C9.70253 11.653 9.95707 11.6511 10.1958 11.7221C10.4346 11.7932 10.6467 11.9339 10.8049 12.1264L11.7949 13.3276C12.2074 13.8276 12.1574 14.5626 11.6836 15.0039C10.3874 16.2126 8.60487 16.4601 7.36487 15.4639C5.8449 14.2378 4.56356 12.7425 3.58487 11.0526C2.59823 9.36403 1.94044 7.50439 1.64362 5.57135ZM5.44737 7.7776L6.78737 6.5476C7.15466 6.21077 7.41291 5.77185 7.52895 5.2872C7.64499 4.80254 7.61354 4.29425 7.43862 3.8276L6.89612 2.3776C6.67405 1.78541 6.23453 1.30018 5.66714 1.02078C5.09974 0.741374 4.44716 0.68883 3.84237 0.873852C1.73862 1.5176 0.0498675 3.40385 0.407368 5.7601C0.657368 7.4051 1.23362 9.4976 2.50487 11.6826C3.56076 13.5047 4.94294 15.117 6.58237 16.4389C8.44237 17.9326 10.9249 17.4226 12.5374 15.9201C12.9988 15.4905 13.2788 14.901 13.3204 14.272C13.362 13.6429 13.1619 13.0217 12.7611 12.5351L11.7711 11.3326C11.4544 10.9482 11.0301 10.6672 10.5526 10.5255C10.0751 10.3839 9.5662 10.3881 9.09112 10.5376L7.35487 11.0839C6.90658 10.6216 6.51391 10.1086 6.18487 9.5551C5.86702 8.99555 5.61932 8.39898 5.44737 7.77885V7.7776Z" fill="white"/>
                              </svg></span> +123 456 7890</a>
                        </div>
                    </div>

                    <div class="space30"></div>
                    <div class="social-area">
                        <h3>Follow Us</h3>
                        <div class="space24"></div>
                        <ul>
                            <li><a href="service-left.html#"><i class="fa-brands fa-facebook-f"></i></a></li>
                            <li><a href="service-left.html#"><i class="fa-brands fa-instagram"></i></a></li>
                            <li><a href="service-left.html#"><i class="fa-brands fa-pinterest-p"></i></a></li>
                            <li><a href="service-left.html#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="service-main-detailsarea leftpadding">
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
