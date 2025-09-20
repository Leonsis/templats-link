@extends('temas.Finazze.layouts.app')

@section('content')
<!--===== HERO AREA STARTS =======-->
<div class="inner-pages-section-area" style="background-image: url({{ asset('temas/Finazze/assets/img/all-images/bg/hero-bg1.png') }}); background-position: center; background-repeat: no-repeat; background-size: cover;">
  <div class="container">
    <div class="row">
        <div class="col-lg-12 m-auto">
            <div class="inner-header text-center">
            <h2>Our Projects</h2>
            <div class="space24"></div>
            <a href="{{ route('home') }}">Home <i class="fa-solid fa-angle-right"></i> <span>Our Projects</span></a>
            </div>
        </div>
    </div>
  </div>
</div>
<!--===== HERO AREA ENDS =======-->

<!--===== PROJECT AREA STARTS =======-->
<div class="project-inner-section-area sp1">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="800">
          <div class="project-single-boxarea2">
            <div class="img1 image-anime">
              <img src="{{ asset('temas/Finazze/assets/img/all-images/project/project-img17.png') }}"" alt="">
            </div>
            <div class="space24"></div>
            <div class="content-area">
              <div class="text">
                <a href="project.html#">#Business And Finance</a>
                <div class="space16"></div>
                <a href="{{ route('tema.Finazze.project-single') }}">Future Forward Pioneering New Frontiers</a>
              </div>
              <div class="arrow">
                <a href="{{ route('tema.Finazze.project-single') }}"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                <path d="M9.33398 22.6644L22.6673 9.33105M22.6673 9.33105H10.6673M22.6673 9.33105V21.3311" stroke="#6E9419" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg></a>
              </div>
            </div>
          </div>
        </div>
  
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1000">
          <div class="project-single-boxarea2">
            <div class="img1 image-anime">
              <img src="{{ asset('temas/Finazze/assets/img/all-images/project/project-img18.png') }}"" alt="">
            </div>
            <div class="space24"></div>
            <div class="content-area">
              <div class="text">
                <a href="project.html#">#Business And Finance</a>
                <div class="space16"></div>
                <a href="{{ route('tema.Finazze.project-single') }}">Project Titan: Building Financial Resilience</a>
              </div>
              <div class="arrow">
                <a href="{{ route('tema.Finazze.project-single') }}"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                <path d="M9.33398 22.6644L22.6673 9.33105M22.6673 9.33105H10.6673M22.6673 9.33105V21.3311" stroke="#6E9419" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg></a>
              </div>
            </div>
          </div>
        </div>
  
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1200">
          <div class="project-single-boxarea2">
            <div class="img1 image-anime">
              <img src="{{ asset('temas/Finazze/assets/img/all-images/project/project-img19.png') }}"" alt="">
            </div>
            <div class="space24"></div>
            <div class="content-area">
              <div class="text">
                <a href="project.html#">#Business And Finance</a>
                <div class="space16"></div>
                <a href="{{ route('tema.Finazze.project-single') }}">Project Ascend: Elevating Business Potential</a>
              </div>
              <div class="arrow">
                <a href="{{ route('tema.Finazze.project-single') }}"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                <path d="M9.33398 22.6644L22.6673 9.33105M22.6673 9.33105H10.6673M22.6673 9.33105V21.3311" stroke="#6E9419" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg></a>
              </div>
            </div>
          </div>
        </div>
  
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1000">
          <div class="project-single-boxarea2 box2">
            <div class="img1 image-anime">
              <img src="{{ asset('temas/Finazze/assets/img/all-images/project/project-img20.png') }}"" alt="">
            </div>
            <div class="space24"></div>
            <div class="content-area">
              <div class="text">
                <a href="project.html#">#Business And Finance</a>
                <div class="space16"></div>
                <a href="{{ route('tema.Finazze.project-single') }}">The Catalyst Initiative: Driving Innovation</a>
              </div>
              <div class="arrow">
                <a href="{{ route('tema.Finazze.project-single') }}"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                <path d="M9.33398 22.6644L22.6673 9.33105M22.6673 9.33105H10.6673M22.6673 9.33105V21.3311" stroke="#6E9419" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg></a>
              </div>
            </div>
          </div>
        </div>
  
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1100">
          <div class="project-single-boxarea2">
            <div class="img1 image-anime">
              <img src="{{ asset('temas/Finazze/assets/img/all-images/project/project-img21.png') }}"" alt="">
            </div>
            <div class="space24"></div>
            <div class="content-area">
              <div class="text">
                <a href="project.html#">#Business And Finance</a>
                <div class="space16"></div>
                <a href="{{ route('tema.Finazze.project-single') }}">Empowerment Project: Financial Freedom for All</a>
              </div>
              <div class="arrow">
                <a href="{{ route('tema.Finazze.project-single') }}"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                <path d="M9.33398 22.6644L22.6673 9.33105M22.6673 9.33105H10.6673M22.6673 9.33105V21.3311" stroke="#6E9419" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg></a>
              </div>
            </div>
          </div>
        </div>
  
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1200">
          <div class="project-single-boxarea2 box3">
            <div class="img1 image-anime">
              <img src="{{ asset('temas/Finazze/assets/img/all-images/project/project-img22.png') }}"" alt="">
            </div>
            <div class="space24"></div>
            <div class="content-area">
              <div class="text">
                <a href="project.html#">#Business And Finance</a>
                <div class="space16"></div>
                <a href="{{ route('tema.Finazze.project-single') }}">360 Comprehensive Business Solutions</a>
              </div>
              <div class="arrow">
                <a href="{{ route('tema.Finazze.project-single') }}"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                <path d="M9.33398 22.6644L22.6673 9.33105M22.6673 9.33105H10.6673M22.6673 9.33105V21.3311" stroke="#6E9419" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg></a>
              </div>
            </div>
          </div>
        </div>

          
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1000">
            <div class="project-single-boxarea2 box2">
              <div class="img1 image-anime">
                <img src="{{ asset('temas/Finazze/assets/img/all-images/project/project-img25.png') }}"" alt="">
              </div>
              <div class="space24"></div>
              <div class="content-area">
                <div class="text">
                  <a href="project.html#">#Business And Finance</a>
                  <div class="space16"></div>
                  <a href="{{ route('tema.Finazze.project-single') }}">Risk Management And Mitigation Initiative</a>
                </div>
                <div class="arrow">
                  <a href="{{ route('tema.Finazze.project-single') }}"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                  <path d="M9.33398 22.6644L22.6673 9.33105M22.6673 9.33105H10.6673M22.6673 9.33105V21.3311" stroke="#6E9419" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg></a>
                </div>
              </div>
            </div>
          </div>
    
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1100">
            <div class="project-single-boxarea2">
              <div class="img1 image-anime">
                <img src="{{ asset('temas/Finazze/assets/img/all-images/project/project-img23.png') }}"" alt="">
              </div>
              <div class="space24"></div>
              <div class="content-area">
                <div class="text">
                  <a href="project.html#">#Business And Finance</a>
                  <div class="space16"></div>
                  <a href="{{ route('tema.Finazze.project-single') }}">Sustainable Business Growth Strategy</a>
                </div>
                <div class="arrow">
                  <a href="{{ route('tema.Finazze.project-single') }}"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                  <path d="M9.33398 22.6644L22.6673 9.33105M22.6673 9.33105H10.6673M22.6673 9.33105V21.3311" stroke="#6E9419" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg></a>
                </div>
              </div>
            </div>
          </div>
    
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1200">
            <div class="project-single-boxarea2 box4">
              <div class="img1 image-anime">
                <img src="{{ asset('temas/Finazze/assets/img/all-images/project/project-img24.png') }}"" alt="">
              </div>
              <div class="space24"></div>
              <div class="content-area">
                <div class="text">
                  <a href="project.html#">#Business And Finance</a>
                  <div class="space16"></div>
                  <a href="{{ route('tema.Finazze.project-single') }}">Mergers & Acquisitions Success Stories</a>
                </div>
                <div class="arrow">
                  <a href="{{ route('tema.Finazze.project-single') }}"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                  <path d="M9.33398 22.6644L22.6673 9.33105M22.6673 9.33105H10.6673M22.6673 9.33105V21.3311" stroke="#6E9419" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg></a>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-12">
            <div class="space18"></div>
            <div class="pagination-area">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                      <li class="page-item">
                        <a class="page-link" href="project.html#" aria-label="Previous">
                            <i class="fa-solid fa-angle-left"></i>
                        </a>
                      </li>
                      <li class="page-item"><a class="page-link" href="project.html#">1</a></li>
                      <li class="page-item"><a class="page-link" href="project.html#">2</a></li>
                      <li class="page-item"><a class="page-link" href="project.html#">...</a></li>
                      <li class="page-item"><a class="page-link" href="project.html#">12</a></li>
                      <li class="page-item">
                        <a class="page-link" href="project.html#" aria-label="Next">
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
  <!--===== PROJECT AREA ENDS =======-->

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

<!--===== FOOTER AREA STARTS =======-->
<div class="vl-footer1-section-area sp8">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-6">
        <div class="footer-logo1">
          <img src="{{ asset('temas/Finazze/assets/img/logo/logo1.png') }}"" alt="">
          <div class="space24"></div>
          <p>We are committed to providing  with the highest level of service expertise  business and finance if you have any.</p>
          <div class="space24"></div>
          <ul>
            <li><a href="project.html#"><i class="fa-brands fa-facebook-f"></i></a></li>
            <li><a href="project.html#"><i class="fa-brands fa-linkedin-in"></i></a></li>
            <li><a href="project.html#"><i class="fa-brands fa-instagram"></i></a></li>
            <li><a href="project.html#" class="m-0"><i class="fa-brands fa-youtube"></i></a></li>
          </ul>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="space30 d-md-none d-block"></div>
        <div class="vl-footer-widget first-padding">
          <h3>Quick Links</h3>
          <div class="space4"></div>
          <ul>
            <li><a href="{{ route('sobre') }}">About Us</a></li>
            <li><a href="{{ route('tema.Finazze.service') }}">Our Services</a></li>
            <li><a href="{{ route('tema.Finazze.project') }}">Case Studies</a></li>
             <li><a href="{{ route('tema.Finazze.pricing') }}">Pricing Plan</a></li>
            <li><a href="{{ route('contato') }}">Contact Us</a></li>
          </ul>
        </div>
      </div> 
      <div class="col-lg-3 col-md-6">
        <div class="vl-footer-widget">
          <div class="space30 d-lg-none d-block"></div>
          <h3>Contact Us</h3>
          <ul>
            <li><a href="tel:+11234567890"><img src="{{ asset('temas/Finazze/assets/img/icons/phn1.svg') }}"" alt="">+1 123 456 7890</a></li>
            <li><a href="project.html#"><img src="{{ asset('temas/Finazze/assets/img/icons/location1.svg') }}"" alt="">421 Allen, Mexico 4233</a></li>
            <li><a href="https://html.vikinglab.agency/finazze/renevagency@com"><img src="{{ asset('temas/Finazze/assets/img/icons/email1.svg') }}"" alt="">finazzeconsult@com</a></li>
            <li><a href="project.html#"><img src="{{ asset('temas/Finazze/assets/img/icons/global1.svg') }}"" alt="">finazzeconsult.com</a></li>
          </ul>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="vl-footer-widget">
          <div class="space30 d-lg-none d-block"></div>
          <h3>Instagram Post</h3>
          <div class="space8"></div>
          <div class="row">
            <div class="col-lg-4 col-4">
              <div class="footer-img">
                <img src="{{ asset('temas/Finazze/assets/img/all-images/footer/footer-img1.png') }}"" alt="">
                <div class="icons">
                  <a href="project.html#"><i class="fa-brands fa-instagram"></i></a>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-4">
              <div class="footer-img">
                <img src="{{ asset('temas/Finazze/assets/img/all-images/footer/footer-img2.png') }}"" alt="">
                <div class="icons">
                  <a href="project.html#"><i class="fa-brands fa-instagram"></i></a>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-4">
              <div class="footer-img">
                <img src="{{ asset('temas/Finazze/assets/img/all-images/footer/footer-img3.png') }}"" alt="">
                <div class="icons">
                  <a href="project.html#"><i class="fa-brands fa-instagram"></i></a>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-4">
              <div class="footer-img">
                <img src="{{ asset('temas/Finazze/assets/img/all-images/footer/footer-img4.png') }}"" alt="">
                <div class="icons">
                  <a href="project.html#"><i class="fa-brands fa-instagram"></i></a>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-4">
              <div class="footer-img">
                <img src="{{ asset('temas/Finazze/assets/img/all-images/footer/footer-img5.png') }}"" alt="">
                <div class="icons">
                  <a href="project.html#"><i class="fa-brands fa-instagram"></i></a>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-4">
              <div class="footer-img">
                <img src="{{ asset('temas/Finazze/assets/img/all-images/footer/footer-img6.png') }}"" alt="">
                <div class="icons">
                  <a href="project.html#"><i class="fa-brands fa-instagram"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="space60"></div>
    <div class="row">
      <div class="col-lg-12">
        <div class="vl-copyright-area">
          <p>© Copyright 2025 - Finazze. All Right Reserved</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!--===== FOOTER AREA ENDS =======-->

<!--===== JS SCRIPT LINK =======-->
<script src="{{ asset('temas/Finazze/assets/js/plugins/bootstrap.min.js') }}""></script>
<script src="{{ asset('temas/Finazze/assets/js/plugins/fontawesome.js') }}""></script>
<script src="{{ asset('temas/Finazze/assets/js/plugins/aos.js') }}""></script>
<script src="{{ asset('temas/Finazze/assets/js/plugins/counter.js') }}""></script>
<script src="{{ asset('temas/Finazze/assets/js/plugins/gsap.min.js') }}""></script>
<script src="{{ asset('temas/Finazze/assets/js/plugins/ScrollTrigger.min.js') }}""></script>
<script src="{{ asset('temas/Finazze/assets/js/plugins/Splitetext.js') }}""></script>
<script src="{{ asset('temas/Finazze/assets/js/plugins/SmoothScroll.js') }}""></script>
<script src="{{ asset('temas/Finazze/assets/js/plugins/sidebar.js') }}""></script>
<script src="{{ asset('temas/Finazze/assets/js/plugins/magnific-popup.js') }}""></script>
<script src="{{ asset('temas/Finazze/assets/js/plugins/mobilemenu.js') }}""></script>
<script src="{{ asset('temas/Finazze/assets/js/plugins/owlcarousel.min.js') }}""></script>
<script src="{{ asset('temas/Finazze/assets/js/plugins/nice-select.js') }}""></script>
<script src="{{ asset('temas/Finazze/assets/js/plugins/waypoints.js') }}""></script>
<script src="{{ asset('temas/Finazze/assets/js/plugins/slick-slider.js') }}""></script>
<script src="{{ asset('temas/Finazze/assets/js/plugins/circle-progress.js') }}""></script>
<script src="{{ asset('temas/Finazze/assets/js/plugins/swiper.js') }}""></script>
<script src="{{ asset('temas/Finazze/assets/js/main.js') }}""></script>

</body>
</html>
@endsection
