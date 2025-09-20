@extends('temas.Finazze.layouts.app')

@section('content')
<!--===== HERO AREA STARTS =======-->
<div class="inner-pages-section-area" style="background-image: url({{ asset('temas/Finazze/assets/img/all-images/bg/hero-bg1.png') }}); background-position: center; background-repeat: no-repeat; background-size: cover;">
  <div class="container">
    <div class="row">
        <div class="col-lg-12 m-auto">
            <div class="inner-header text-center">
            <h2>Pricing Plan</h2>
            <div class="space24"></div>
            <a href="{{ route('home') }}">Home <i class="fa-solid fa-angle-right"></i> <span>Pricing Plan</span></a>
            </div>
        </div>
    </div>
  </div>
</div>
<!--===== HERO AREA ENDS =======-->

<!--===== PRICING AREA STARTS =======-->
<div class="pricing-inner-section-area sp2">
    <div class="container">
      <div class="row">
        <div class="col-12 text-center d-none">
          <div class="plan-toggle-wrap" style="margin-top: 0;">
            <div class="toggle-inner" data-aos="fade-up" data-aos-duration="1000">
              <input id="ce-toggle" checked="" type="checkbox">
              <span class="custom-toggle"></span>
              <div class="t-month">
                <h4>Monthly</h4>
              </div>
              <div class="t-year">
                <h4>Yearly</h4>
              </div>
            </div>
          </div>
        </div>
  
        <div class="col-lg-12">
          <div class="tab-content">
            <div id="monthly">
              <div class="row">
                <div class="col-lg-4 col-md-6" data-aos="flip-right" data-aos-duration="800">
                  <div class="single-pricing-area">
                    <div class="pricing-box">
                      <h3>Starter Package</h3>
                      <div class="space26"></div>
                      <p>Our pricing plans are designed to meet the diverse needs of businesses at every stage.</p>
                      <div class="space38"></div>
                      <h2>$29 <span>/Per Month</span> <img src="{{ asset('temas/Finazze/assets/img/elements/elements41.png') }}"" alt="" class="elements19 keyframe5"></h2>
                      <div class="space32"></div>
                      <ul>
                        <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt="">Bookkeeping</li>
                        <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt="">Tax advisory</li>
                        <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt="">Tax preparation</li>
                        <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt="">Financial consulting</li>
                        <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt=""> Quarterly reports</li>
                      </ul>
                      <div class="space32"></div>
                      <div class="btn-area1">
                        <a href="{{ route('contato') }}" class="vl-btn1">Get Started</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="flip-right" data-aos-duration="800">
                  <div class="single-pricing-area active">
                    <div class="pricing-box">
                      <h3>Growth Package</h3>
                      <div class="space26"></div>
                      <p>Whether you’re a startup for foundational financial support an established company.</p>
                      <div class="space38"></div>
                      <h2>$49 <span>/Per Month</span> <img src="{{ asset('temas/Finazze/assets/img/elements/elements42.png') }}"" alt="" class="elements19 keyframe5"></h2>
                      <div class="space32"></div>
                      <ul>
                        <li><img src="{{ asset('temas/Finazze/assets/img/icons/check4.svg') }}"" alt="">Starter package</li>
                        <li><img src="{{ asset('temas/Finazze/assets/img/icons/check4.svg') }}"" alt="">Financial planning</li>
                        <li><img src="{{ asset('temas/Finazze/assets/img/icons/check4.svg') }}"" alt="">Auditing</li>
                        <li><img src="{{ asset('temas/Finazze/assets/img/icons/check4.svg') }}"" alt="">Priority support</li>
                        <li><img src="{{ asset('temas/Finazze/assets/img/icons/check4.svg') }}"" alt=""> Bi-monthly reports</li>
                      </ul>
                      <div class="space32"></div>
                      <div class="btn-area1">
                        <a href="{{ route('contato') }}" class="vl-btn1">Get Started</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="flip-right" data-aos-duration="800">
                  <div class="single-pricing-area">
                    <div class="pricing-box">
                      <h3>Enterprise Package</h3>
                      <div class="space26"></div>
                      <p>With no hidden fees and tailored solutions, you can choose the plan that best fits.</p>
                      <div class="space38"></div>
                      <h2>$99 <span>/Per Month</span> <img src="{{ asset('temas/Finazze/assets/img/elements/elements41.png') }}"" alt="" class="elements19 keyframe5"></h2>
                      <div class="space32"></div>
                      <ul>
                        <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt="">Growth package</li>
                        <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt="">Dedicated manager</li>
                        <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt="">TAdvanced auditing</li>
                        <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt="">Customized reports</li>
                        <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt=""> 24/7 Support</li>
                      </ul>
                      <div class="space32"></div>
                      <div class="btn-area1">
                        <a href="{{ route('contato') }}" class="vl-btn1">Get Started</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="yearly" style="display:none">
              <div class="row">
                <div class="col-lg-4 col-md-6" data-aos="flip-right" data-aos-duration="800">
                    <div class="single-pricing-area">
                      <div class="pricing-box">
                        <h3>Starter Package</h3>
                        <div class="space26"></div>
                        <p>Our pricing plans are designed to meet the diverse needs of businesses at every stage.</p>
                        <div class="space38"></div>
                        <h2>$29 <span>/Per Month</span> <img src="{{ asset('temas/Finazze/assets/img/elements/elements41.png') }}"" alt="" class="elements19 keyframe5"></h2>
                        <div class="space32"></div>
                        <ul>
                          <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt="">Bookkeeping</li>
                          <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt="">Tax advisory</li>
                          <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt="">Tax preparation</li>
                          <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt="">Financial consulting</li>
                          <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt=""> Quarterly reports</li>
                        </ul>
                        <div class="space32"></div>
                        <div class="btn-area1">
                          <a href="{{ route('contato') }}" class="vl-btn1">Get Started</a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6" data-aos="flip-right" data-aos-duration="800">
                    <div class="single-pricing-area active">
                      <div class="pricing-box">
                        <h3>Growth Package</h3>
                        <div class="space26"></div>
                        <p>Whether you’re a startup for foundational financial support an established company.</p>
                        <div class="space38"></div>
                        <h2>$49 <span>/Per Month</span> <img src="{{ asset('temas/Finazze/assets/img/elements/elements42.png') }}"" alt="" class="elements19 keyframe5"></h2>
                        <div class="space32"></div>
                        <ul>
                          <li><img src="{{ asset('temas/Finazze/assets/img/icons/check4.svg') }}"" alt="">Starter package</li>
                          <li><img src="{{ asset('temas/Finazze/assets/img/icons/check4.svg') }}"" alt="">Financial planning</li>
                          <li><img src="{{ asset('temas/Finazze/assets/img/icons/check4.svg') }}"" alt="">Auditing</li>
                          <li><img src="{{ asset('temas/Finazze/assets/img/icons/check4.svg') }}"" alt="">Priority support</li>
                          <li><img src="{{ asset('temas/Finazze/assets/img/icons/check4.svg') }}"" alt=""> Bi-monthly reports</li>
                        </ul>
                        <div class="space32"></div>
                        <div class="btn-area1">
                          <a href="{{ route('contato') }}" class="vl-btn1">Get Started</a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6" data-aos="flip-right" data-aos-duration="800">
                    <div class="single-pricing-area">
                      <div class="pricing-box">
                        <h3>Enterprise Package</h3>
                        <div class="space26"></div>
                        <p>With no hidden fees and tailored solutions, you can choose the plan that best fits.</p>
                        <div class="space38"></div>
                        <h2>$99 <span>/Per Month</span> <img src="{{ asset('temas/Finazze/assets/img/elements/elements41.png') }}"" alt="" class="elements19 keyframe5"></h2>
                        <div class="space32"></div>
                        <ul>
                          <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt="">Growth package</li>
                          <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt="">Dedicated manager</li>
                          <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt="">TAdvanced auditing</li>
                          <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt="">Customized reports</li>
                          <li><img src="{{ asset('temas/Finazze/assets/img/icons/check3.svg') }}"" alt=""> 24/7 Support</li>
                        </ul>
                        <div class="space32"></div>
                        <div class="btn-area1">
                          <a href="{{ route('contato') }}" class="vl-btn1">Get Started</a>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="compare-pricing-section sp1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
              <div class="pricing-section">
                <div class="pricing-header">
                  <div class="logo">
                   <img src="{{ asset('temas/Finazze/assets/img/logo/logo1.png') }}"" alt="">
                  </div>
                  <div class="starter">
                    <div class="package-title">Starter Package</div>
                    <div class="price">$29 <span>/ Per Month</span></div>
                  </div>
                  <div class="growth">
                    <div class="package-title">Growth Package</div>
                    <div class="price">$29 <span>/ Per Month</span></div>
                  </div>
                  <div class="enterprise">
                    <div class="package-title">Enterprise Package</div>
                    <div class="price">$29 <span>/ Per Month</span></div>
                  </div>
                </div>
                <div class="pricing-table">
                  <div class="row">
                    <span>Image Generation Tools</span>
                    <span><img src="{{ asset('temas/Finazze/assets/img/icons/check5.svg') }}"" alt=""> Bookkeeping</span>
                    <span><img src="{{ asset('temas/Finazze/assets/img/icons/check5.svg') }}"" alt=""> Starter package</span>
                    <span><img src="{{ asset('temas/Finazze/assets/img/icons/check5.svg') }}"" alt=""> Growth package</span>
                  </div>
                  <div class="row">
                    <span>Customization Options</span>
                    <span><img src="{{ asset('temas/Finazze/assets/img/icons/check5.svg') }}"" alt=""> Tax advisory</span>
                    <span><img src="{{ asset('temas/Finazze/assets/img/icons/check5.svg') }}"" alt=""> Financial planning</span>
                    <span><img src="{{ asset('temas/Finazze/assets/img/icons/check5.svg') }}"" alt=""> Dedicated manager</span>
                  </div>
                  <div class="row">
                    <span>Support System</span>
                    <span><img src="{{ asset('temas/Finazze/assets/img/icons/check5.svg') }}"" alt=""> Tax preparation</span>
                    <span><img src="{{ asset('temas/Finazze/assets/img/icons/check5.svg') }}"" alt=""> Auditing</span>
                    <span><img src="{{ asset('temas/Finazze/assets/img/icons/check5.svg') }}"" alt=""> Advanced auditing</span>
                  </div>
                  <div class="row">
                    <span>Access to Templates</span>
                    <span><img src="{{ asset('temas/Finazze/assets/img/icons/check5.svg') }}"" alt=""> Financial consulting</span>
                    <span><img src="{{ asset('temas/Finazze/assets/img/icons/check5.svg') }}"" alt=""> Priority support</span>
                    <span><img src="{{ asset('temas/Finazze/assets/img/icons/check5.svg') }}"" alt=""> Customized reports</span>
                  </div>
                  <div class="row">
                    <span>Ability to Generate</span>
                    <span><img src="{{ asset('temas/Finazze/assets/img/icons/check5.svg') }}"" alt=""> Quarterly reports</span>
                    <span><img src="{{ asset('temas/Finazze/assets/img/icons/check5.svg') }}"" alt=""> Bi-monthly reports</span>
                    <span><img src="{{ asset('temas/Finazze/assets/img/icons/check5.svg') }}"" alt=""> 24/7 Support</span>
                  </div>

                  <div class="row">
                    <span><a href="pricing.html#" class="vl-btn1 btn2" style="visibility: hidden;">Choose Plan</a></span>
                    <span><a href="{{ route('contato') }}" class="vl-btn1">Choose Plan</a></span>
                    <span><a href="{{ route('contato') }}" class="vl-btn1">Choose Plan</a></span>
                    <span><a href="{{ route('contato') }}" class="vl-btn1">Choose Plan</a></span>
                  </div>
                </div>
              </div>
              </div>
        </div>
    </div>
  </div>
  <!--===== PRICING AREA ENDS =======-->

<!--===== FAQ AREA STARTS =======-->
<div class="faq-pricing-section-area sp1">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 m-auto">
                <div class="heading1 text-center space-margin60">
                    <h5>Frequently Asked Questions</h5>
                    <div class="space16"></div>
                    <h2 class="text-anime-style-3">Your Questions Answered</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="accordion-area-pricing">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item" data-aos="fade-left" data-aos-duration="700">
                          <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                How do you determine the right financial strategy for my business?
                            </button>
                          </h2>
                          <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>We begin with a thorough assessment of your current financial situation, including an analysis of your goals, challenges, and industry landscape. This allows us to identify opportunities.</p>
                            </div>
                          </div>
                        </div>
                        <div class="space20"></div>
                        <div class="accordion-item" data-aos="fade-left" data-aos-duration="800">
                          <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                What is the typical timeline for seeing results?
                            </button>
                          </h2>
                          <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>We begin with a thorough assessment of your current financial situation, including an analysis of your goals, challenges, and industry landscape. This allows us to identify opportunities.</p>
                            </div>
                          </div>
                        </div>
                        <div class="space20"></div>
                        <div class="accordion-item" data-aos="fade-left" data-aos-duration="900">
                          <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Do you work with businesses of all sizes?
                            </button>
                          </h2>
                          <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                              <p>We begin with a thorough assessment of your current financial situation, including an analysis of your goals, challenges, and industry landscape. This allows us to identify opportunities.</p>
                            </div>
                          </div>
                        </div>
                        <div class="space20"></div>
                        <div class="accordion-item" data-aos="fade-left" data-aos-duration="1000">
                            <h2 class="accordion-header">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                How can your services benefit my business?
                              </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                              <div class="accordion-body">
                                <p>We begin with a thorough assessment of your current financial situation, including an analysis of your goals, challenges, and industry landscape. This allows us to identify opportunities.</p>
                              </div>
                            </div>
                          </div>
                          <div class="space20"></div>
                          <div class="accordion-item" data-aos="fade-left" data-aos-duration="1100">
                            <h2 class="accordion-header">
                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                What services do you offer?
                              </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                              <div class="accordion-body">
                               <p>We begin with a thorough assessment of your current financial situation, including an analysis of your goals, challenges, and industry landscape. This allows us to identify opportunities.</p>
                              </div>
                            </div>
                          </div>
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--===== FAQ AREA ENDS =======-->

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
