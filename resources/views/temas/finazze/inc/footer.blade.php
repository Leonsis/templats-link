<!--===== FOOTER AREA STARTS =======-->
<div class="vl-footer1-section-area sp8">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-6">
        <div class="footer-logo1">
          @if(\App\Helpers\HeadHelper::getLogoFooter())
              <img src="{{ \App\Helpers\HeadHelper::getLogoFooter() }}" alt="{{ \App\Helpers\HeadHelper::getMetaTitle('global') }}">
          @else
              <img src="{{ asset('temas/finazze/assets/img/logo/logo1.png') }}" alt="finazze">
          @endif
          <div class="space24"></div>
          <p>{{ \App\Helpers\HeadHelper::getDescricaoFooter() ?: 'We are committed to providing with the highest level of service expertise business and finance if you have any.' }}</p>
          <div class="space24"></div>
          <ul>
            @php $redesSociais = \App\Helpers\HeadHelper::getRedesSociais(); @endphp
            @if($redesSociais['facebook'])
                <li><a href="{{ $redesSociais['facebook'] }}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a></li>
            @endif
            @if($redesSociais['linkedin'])
                <li><a href="{{ $redesSociais['linkedin'] }}" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a></li>
            @endif
            @if($redesSociais['instagram'])
                <li><a href="{{ $redesSociais['instagram'] }}" target="_blank"><i class="fa-brands fa-instagram"></i></a></li>
            @endif
            @if($redesSociais['youtube'])
                <li><a href="{{ $redesSociais['youtube'] }}" target="_blank" class="m-0"><i class="fa-brands fa-youtube"></i></a></li>
            @endif</ul>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="space30 d-md-none d-block"></div>
        <div class="vl-footer-widget first-padding">
          <h3>Quick Links</h3>
          <div class="space4"></div>
          <ul>
            <li><a href="http://localhost/templats-link/public/sobre">About Us</a></li>
            <li><a href="{{ route('tema.finazze.service') }}">Our Services</a></li>
            <li><a href="project.html">Case Studies</a></li>
             <li><a href="pricing.html">Pricing Plan</a></li>
            <li><a href="http://localhost/templats-link/public/contato">Contact Us</a></li>
          </ul>
        </div>
      </div> 
      <div class="col-lg-3 col-md-6">
        <div class="vl-footer-widget">
          <div class="space30 d-lg-none d-block"></div>
          <h3>Contact Us</h3>
          <ul>
            <li><a href="tel:+11234567890"><img src="{{ asset('temas/finazze/assets/img/icons/phn1.svg') }}" alt="">+1 123 456 7890</a></li>
            <li><a href="service-right.html#"><img src="{{ asset('temas/finazze/assets/img/icons/location1.svg') }}" alt="">421 Allen, Mexico 4233</a></li>
            <li><a href="https://html.vikinglab.agency/finazze/renevagency@com"><img src="{{ asset('temas/finazze/assets/img/icons/email1.svg') }}" alt="">finazzeconsult@com</a></li>
            <li><a href="service-right.html#"><img src="{{ asset('temas/finazze/assets/img/icons/global1.svg') }}" alt="">finazzeconsult.com</a></li>
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
                <img src="{{ asset('temas/finazze/assets/img/all-images/footer/footer-img1.png') }}" alt="">
                <div class="icons">
                  <a href="service-right.html#"><i class="fa-brands fa-instagram"></i></a>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-4">
              <div class="footer-img">
                <img src="{{ asset('temas/finazze/assets/img/all-images/footer/footer-img2.png') }}" alt="">
                <div class="icons">
                  <a href="service-right.html#"><i class="fa-brands fa-instagram"></i></a>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-4">
              <div class="footer-img">
                <img src="{{ asset('temas/finazze/assets/img/all-images/footer/footer-img3.png') }}" alt="">
                <div class="icons">
                  <a href="service-right.html#"><i class="fa-brands fa-instagram"></i></a>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-4">
              <div class="footer-img">
                <img src="{{ asset('temas/finazze/assets/img/all-images/footer/footer-img4.png') }}" alt="">
                <div class="icons">
                  <a href="service-right.html#"><i class="fa-brands fa-instagram"></i></a>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-4">
              <div class="footer-img">
                <img src="{{ asset('temas/finazze/assets/img/all-images/footer/footer-img5.png') }}" alt="">
                <div class="icons">
                  <a href="service-right.html#"><i class="fa-brands fa-instagram"></i></a>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-4">
              <div class="footer-img">
                <img src="{{ asset('temas/finazze/assets/img/all-images/footer/footer-img6.png') }}" alt="">
                <div class="icons">
                  <a href="service-right.html#"><i class="fa-brands fa-instagram"></i></a>
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
          <p>{{ \App\Helpers\HeadHelper::getCopyrightFooter() ?: '© Copyright 2025 - finazze. All Right Reserved' }}</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!--===== FOOTER AREA ENDS =======-->