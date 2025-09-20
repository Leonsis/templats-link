@extends('temas.Finazze.layouts.app')

@section('content')
<!--===== HERO AREA STARTS =======-->
<div class="inner-pages-section-area" style="background-image: url({{ asset('temas/Finazze/assets/img/all-images/bg/hero-bg1.png') }}); background-position: center; background-repeat: no-repeat; background-size: cover;">
  <div class="container">
    <div class="row">
        <div class="col-lg-12 m-auto">
            <div class="inner-header text-center">
            <h2>Our Service</h2>
            <div class="space24"></div>
            <a href="{{ route('home') }}">Home <i class="fa-solid fa-angle-right"></i> <span>Our Service</span></a>
            </div>
        </div>
    </div>
  </div>
</div>
<!--===== HERO AREA ENDS =======-->

<!--===== SERVICE AREA STARTS =======-->
<div class="service-inner-section-area sp1">
    <div class="container">  
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
  
        <div class="col-lg-4 col-md-6">
          <div class="service3-single-boxarea">
            <div class="icons">
              <img src="{{ asset('temas/Finazze/assets/img/icons/service22.svg') }}"" alt="">
            </div>
            <div class="space24"></div>
            <div class="content">
              <a href="{{ route('tema.Finazze.service-single') }}">Risk Management & Mitigation</a>
              <div class="space16"></div>
              <p>Managing is key preserving business’s financial stability Risk Management  & Mitigation service help you identify.</p>
              <div class="space24"></div>
              <a href="{{ route('tema.Finazze.service-single') }}" class="readmore">Read More <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
  
        <div class="col-lg-4 col-md-6">
          <div class="service3-single-boxarea">
            <div class="icons">
              <img src="{{ asset('temas/Finazze/assets/img/icons/service23.svg') }}"" alt="">
            </div>
            <div class="space24"></div>
            <div class="content">
              <a href="{{ route('tema.Finazze.service-single') }}">Business Growth Planning</a>
              <div class="space16"></div>
              <p>Business Growth Planning service is tailored companies sustainable, scalable growth analyze current.</p>
              <div class="space24"></div>
              <a href="{{ route('tema.Finazze.service-single') }}" class="readmore">Read More <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
  
        <div class="col-lg-4 col-md-6">
          <div class="service3-single-boxarea">
            <div class="icons">
              <img src="{{ asset('temas/Finazze/assets/img/icons/service24.svg') }}"" alt="">
            </div>
            <div class="space24"></div>
            <div class="content">
              <a href="{{ route('tema.Finazze.service-single') }}">Cash Flow Optimization</a>
              <div class="space16"></div>
              <p>Maintaining a healthy cash is critical  business success. Cash Optimization service provides in-depth analysis.</p>
              <div class="space24"></div>
              <a href="{{ route('tema.Finazze.service-single') }}" class="readmore">Read More <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="service3-single-boxarea">
              <div class="icons">
                <img src="{{ asset('temas/Finazze/assets/img/icons/service25.svg') }}"" alt="">
              </div>
              <div class="space24"></div>
              <div class="content">
                <a href="{{ route('tema.Finazze.service-single') }}">Cash Flow Optimization</a>
                <div class="space16"></div>
                <p>With our guidance, you can navigate the complexities of the financial landscape with confidence, allowing you to focus.</p>
                <div class="space24"></div>
                <a href="{{ route('tema.Finazze.service-single') }}" class="readmore">Read More <i class="fa-solid fa-arrow-right"></i></a>
              </div>
            </div>
          </div>
    
          <div class="col-lg-4 col-md-6">
            <div class="service3-single-boxarea">
              <div class="icons">
                <img src="{{ asset('temas/Finazze/assets/img/icons/service26.svg') }}"" alt="">
              </div>
              <div class="space24"></div>
              <div class="content">
                <a href="{{ route('tema.Finazze.service-single') }}">Corporate Finance Advisory</a>
                <div class="space16"></div>
                <p>From optimizing cash flow to strategic budgeting & forecasting, we take a holistic approach to ensure that every financial.</p>
                <div class="space24"></div>
                <a href="{{ route('tema.Finazze.service-single') }}" class="readmore">Read More <i class="fa-solid fa-arrow-right"></i></a>
              </div>
            </div>
          </div>
    
          <div class="col-lg-4 col-md-6">
            <div class="service3-single-boxarea">
              <div class="icons">
                <img src="{{ asset('temas/Finazze/assets/img/icons/service27.svg') }}"" alt="">
              </div>
              <div class="space24"></div>
              <div class="content">
                <a href="{{ route('tema.Finazze.service-single') }}">Investment Management</a>
                <div class="space16"></div>
                <p>Our team of experts will work closely with you to analyze your current financial Best situation, identify opportunities for growth</p>
                <div class="space24"></div>
                <a href="{{ route('tema.Finazze.service-single') }}" class="readmore">Read More <i class="fa-solid fa-arrow-right"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-12">
            <div class="space18"></div>
            <div class="pagination-area">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                      <li class="page-item">
                        <a class="page-link" href="service.html#" aria-label="Previous">
                            <i class="fa-solid fa-angle-left"></i>
                        </a>
                      </li>
                      <li class="page-item"><a class="page-link" href="service.html#">1</a></li>
                      <li class="page-item"><a class="page-link" href="service.html#">2</a></li>
                      <li class="page-item"><a class="page-link" href="service.html#">...</a></li>
                      <li class="page-item"><a class="page-link" href="service.html#">12</a></li>
                      <li class="page-item">
                        <a class="page-link" href="service.html#" aria-label="Next">
                            <i class="fa-solid fa-angle-right"></i>
                        </a>
                      </li>
                    </ul>
                  </nav>
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
