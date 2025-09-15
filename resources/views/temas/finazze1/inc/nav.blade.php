<!--===== PRELOADER STARTS =======-->
<div class="preloader">
  <div class="loader"></div>
</div>
<!--===== PRELOADER ENDS =======-->

<!--===== PROGRESS STARTS=======-->
<div class="paginacontainer">
     <div class="progress-wrap">
       <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
         <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
       </svg>
     </div>
   </div>
 <!--===== PROGRESS ENDS=======-->

<!--=====HEADER START=======-->
<header class="homepage1-body">
  <div id="vl-header-sticky" class="vl-header-area vl-transparent-header">
      <div class="container">
          <div class="row align-items-center">
              <div class="col-lg-2 col-md-6 col-6">
                  <div class="vl-logo">
                      <a href="{{ route('home') }}">@if(\App\Helpers\HeadHelper::getLogo())
                              <img src="{{ \App\Helpers\HeadHelper::getLogo() }}" alt="{{ \App\Helpers\HeadHelper::getMetaTitle('global') }}">
                          @else
                              <img src="{{ asset('temas/finazze1/assets/img/logo/logo1.png') }}" alt="finazze1">
                          @endif</a>
                  </div>
              </div>
              <div class="col-lg-7 d-none d-lg-block">
                  <div class="vl-main-menu text-center">
                      <nav class="vl-mobile-menu-active">
                          <ul>
                              <li class="has-dropdown">
                                  <a href="index.html#">Home <span><i class="fa-solid fa-angle-down d-lg-inline d-none"></i></span></a>
                                  <div class="vl-mega-menu">
                                      <div class="vl-home-menu">                    
                                          <div class="row gx-3 row-cols-1 row-cols-md-1 row-cols-lg-5">
                                              <div class="col">
                                                      <div class="vl-home-thumb">
                                                          <div class="img1">
                                                            <img src="{{ asset('temas/finazze1/assets/img/all-images/demo/demo-img1.png') }}" alt="">
                                                          </div>
                                                         <a href="{{ route('home') }}">Finazze  - Homepage 01</a>
                                                         <div class="btn-area1">
                                                          <a href="{{ route('home') }}" class="vl-btn1">View Demo</a>

                                                         </div>
                                                         <div class="space20 d-lg-none d-block"></div>
                                                      </div>
                                              </div>
                                              <div class="col">
                                                      <div class="vl-home-thumb">
                                                        <div class="img1">
                                                          <img src="{{ asset('temas/finazze1/assets/img/all-images/demo/demo-img2.png') }}" alt="">
                                                        </div>
                                                        <a href="index2.html">Finazze  - Homepage 02</a>
                                                        <div class="btn-area1">
                                                          <a href="index2.html" class="vl-btn1">View Demo</a>

                                                         </div>
                                                        <div class="space20 d-lg-none d-block"></div>
                                                      </div>
                                              </div>
                                              <div class="col ">
                                                      <div class="vl-home-thumb">
                                                        <div class="img1">
                                                          <img src="{{ asset('temas/finazze1/assets/img/all-images/demo/demo-img3.png') }}" alt="">
                                                        </div>
                                                        <a href="index3.html">Finazze  - Homepage 03</a>
                                                        <div class="btn-area1">
                                                          <a href="index3.html" class="vl-btn1">View Demo</a>

                                                         </div>
                                                        <div class="space20 d-lg-none d-block"></div>
                                                      </div>
                                              </div>
                                              <div class="col ">
                                                      <div class="vl-home-thumb">
                                                        <div class="img1">
                                                          <img src="{{ asset('temas/finazze1/assets/img/all-images/demo/demo-img4.png') }}" alt="">
                                                        </div>
                                                        <a href="index4.html">Finazze  - Homepage 04</a>
                                                        <div class="btn-area1">
                                                          <a href="index4.html" class="vl-btn1">View Demo</a>

                                                         </div>
                                                        <div class="space20 d-lg-none d-block"></div>
                                                      </div>
                                              </div>

                                              <div class="col ">
                                                    <div class="vl-home-thumb">
                                                      <div class="img1">
                                                        <img src="{{ asset('temas/finazze1/assets/img/all-images/demo/demo-img5.png') }}" alt="">
                                                      </div>
                                                      <a href="index5.html">Finazze  - Homepage 05</a>
                                                      <div class="btn-area1">
                                                        <a href="index5.html" class="vl-btn1">View Demo</a>
                                                       </div>
                                                    </div>
                                            </div>
                                          </div>
                                      </div>
                                  </div>
                              </li>
                              <li class="has-dropdown">
                                <a href="index.html#">Pages <span><i class="fa-solid fa-angle-down d-lg-inline d-none"></i></span></a>
                                  <ul class="sub-menu">
                                      <li><a href="{{ route('sobre') }}">About Us</a></li>
                                      <li><a href="team.html">Our Team</a></li>
                                      <li><a href="pricing.html">Pricing Plan</a></li>
                                      <li><a href="testimonial.html">Testimonials</a></li>
                                      <li><a href="{{ route('contato') }}">Contact Us</a></li>
                                      <li><a href="faq.html">FAQ's</a></li>
                                      <li><a href="404.html">404</a></li>
                                  </ul>
                              </li>
                              <li><a href="index.html#">Services <span><i class="fa-solid fa-angle-down d-lg-inline d-none"></i></span></a>
                                <ul class="sub-menu">
                                  <li><a href="service.html">Our Service</a></li>
                                  <li><a href="index.html#" class="span-arrow">Service Details <span><i class="fa-solid fa-angle-right d-lg-block d-none"></i></span></a>
                                    <ul class="sub-menu menu1">
                                      <li><a href="service-left.html">Service Left</a></li>
                                      <li><a href="service-right.html">Service Right</a></li>
                                      <li><a href="service-single.html">Service Single</a></li>
                                    </ul>
                                  </li>
                              </ul>
                              </li>
                              <li><a href="index.html#">Project <span><i class="fa-solid fa-angle-down d-lg-inline d-none"></i></span></a>
                                <ul class="sub-menu">
                                  <li><a href="project.html">Our Project</a></li>
                                  <li><a href="index.html#" class="span-arrow">Project  Details <span><i class="fa-solid fa-angle-right d-lg-block d-none"></i></span></a>
                                    <ul class="sub-menu menu1">
                                      <li><a href="project-left.html">Project Left</a></li>
                                      <li><a href="project-right.html">Project Right</a></li>
                                      <li><a href="project-single.html">Project Single</a></li>
                                  </ul>
                                  </li>
                              </ul>
                              </li>
                              <li><a href="index.html#">Blogs <span><i class="fa-solid fa-angle-down d-lg-inline d-none"></i></span></a>
                                <ul class="sub-menu">
                                  <li><a href="blog.html">Our Blog</a></li>
                                  <li><a href="index.html#" class="span-arrow">Blog Details <span><i class="fa-solid fa-angle-right d-lg-block d-none"></i></span></a>
                                    <ul class="sub-menu menu1">
                                      <li><a href="blog-left.html">Blog Left</a></li>
                                      <li><a href="blog-right.html">Blog Right</a></li>
                                      <li><a href="blog-single.html">Blog Single</a></li>
                                  </ul>
                                  </li>
                              </ul>
                              </li>

                              <li><a href="{{ route('contato') }}">Contact Us</a></li>
                          </ul>
                      </nav>
                  </div>
              </div>
              <div class="col-lg-3 col-md-6 col-6">
                <div class="vl-hero-btn d-none d-lg-block text-end">
                  <div class="head-btn">
                    <div class="search-icon header__search header-search-btn">
                      <a href="index.html#"><img src="{{ asset('temas/finazze1/assets/img/icons/search1.svg') }}" alt=""></a>
                    </div>
                    <span class="vl-btn-wrap text-end">
                     <span class="icons">
                      <a href="tel:+4909233255"><img src="{{ asset('temas/finazze1/assets/img/icons/phone1.svg') }}" alt=""></a>
                     </span>
                     <span class="text">
                      <span>Hotline 24/7</span>
                      <a href="tel:+4909233255">+4930 9233255</a>
                     </span>
                    </span>
                  </div>
                </div>
                  <div class="vl-header-action-item d-block d-lg-none">
                      <button type="button" class="vl-offcanvas-toggle">
                        <i class="fa-solid fa-bars-staggered"></i>
                      </button>
                   </div>
              </div>
          </div>
      </div>
  </div>
</header>
 <!--=====HEADER END =======-->

  <!--===== MOBILE HEADER STARTS =======-->
<div class="homepage1-body">
  <div class="vl-offcanvas">
    <div class="vl-offcanvas-wrapper">
        <div class="vl-offcanvas-header d-flex justify-content-between align-items-center mb-90">
            <div class="vl-offcanvas-logo">
                <a href="{{ route('home') }}">@if(\App\Helpers\HeadHelper::getLogo())
                              <img src="{{ \App\Helpers\HeadHelper::getLogo() }}" alt="{{ \App\Helpers\HeadHelper::getMetaTitle('global') }}">
                          @else
                              <img src="{{ asset('temas/finazze1/assets/img/logo/logo1.png') }}" alt="finazze1">
                          @endif</a>
            </div>
            <div class="vl-offcanvas-close">
               <button class="vl-offcanvas-close-toggle"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>

        <div class="vl-offcanvas-menu d-lg-none mb-40">
            <nav></nav>
        </div>

        <div class="space20"></div>
        <div class="vl-offcanvas-info">
            <h3 class="vl-offcanvas-sm-title">Contact Us</h3>
            <div class="space20"></div>
            <span><a href="index.html#"> <i class="fa-regular fa-envelope"></i> +57 9954 6476</a></span>
            <span><a href="index.html#"><i class="fa-solid fa-phone"></i> hello@exdos.com</a></span>
            <span><a href="index.html#"><i class="fa-solid fa-location-dot"></i> Bhemeara,Kushtia</a></span>
        </div>
        <div class="space20"></div>
        <div class="vl-offcanvas-social">
            <h3 class="vl-offcanvas-sm-title">Follow Us</h3>
            <div class="space20"></div>
            <a href="index.html#"><i class="fab fa-facebook-f"></i></a>
            <a href="index.html#"><i class="fab fa-twitter"></i></a>
            <a href="index.html#"><i class="fab fa-linkedin-in"></i></a>
            <a href="index.html#"><i class="fab fa-instagram"></i></a>
         </div>

    </div>
</div>
<div class="vl-offcanvas-overlay"></div>
</div>
<!--===== MOBILE HEADER STARTS =======-->

<!--===== SIDEBAR STARTS=======-->
<div class="header-search-form-wrapper">
  <div class="tx-search-close tx-close"><i class="fa-solid fa-xmark"></i></div>
  <div class="header-search-container">
      <form role="search" class="search-form">
      <input type="search"  class="search-field" placeholder="Search …" value="" name="s">
      <button type="submit" class="search-submit"><img src="{{ asset('temas/finazze1/assets/img/icons/search1.svg') }}" alt=""></button>
      </form>
  </div>
</div>
<div class="body-overlay"></div>
<!--===== SIDEBAR ENDS STARTS=======-->