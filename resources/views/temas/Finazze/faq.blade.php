@extends('temas.Finazze.layouts.app')

@section('content')
<!--===== HERO AREA STARTS =======-->
<div class="inner-pages-section-area" style="background-image: url({{ asset('temas/Finazze/assets/img/all-images/bg/hero-bg1.png') }}); background-position: center; background-repeat: no-repeat; background-size: cover;">
  <div class="container">
    <div class="row">
        <div class="col-lg-12 m-auto">
            <div class="inner-header text-center">
            <h2>Frequently Asked Questions</h2>
            <div class="space24"></div>
            <a href="{{ route('home') }}">Home <i class="fa-solid fa-angle-right"></i> <span>Frequently Asked Questions</span></a>
            </div>
        </div>
    </div>
  </div>
</div>
<!--===== HERO AREA ENDS =======-->

<!--===== FAQ AREA STARTS =======-->
<div class="faq-inner-section-area sp1">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 m-auto">
                <div class="heading1 text-center space-margin60">
                    <h2>Frequently Asked Question</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 m-auto">
                <div class="faq-widget-area text-center">
                    <ul class="nav nav-pills text-center" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                          <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">All</button>
                        </li>
                        <li class="nav-item" role="presentation">
                          <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Business</button>
                        </li>
                        <li class="nav-item" role="presentation">
                          <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Finance</button>
                        </li>
                      </ul>
                      <div class="space48"></div>
                      <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                            <div class="faq-section-area">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="accordian-area">
                                            <div class="accordion" id="accordionExample">
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                        What services does your company offer?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                    <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p>                                                   
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                        What industries do you specialize in?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                     <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                        How can I schedule a consultation?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                      <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                        Do you offer services for small businesses?
                                                      </button>
                                                    </h2>
                                                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                      <div class="accordion-body">
                                                        <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="space20"></div>
                                                  <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                                        What is your pricing structure?
                                                      </button>
                                                    </h2>
                                                    <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                      <div class="accordion-body">
                                                       <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                      </div>
                                                    </div>
                                                  </div>
                                              </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="accordian-area">
                                            <div class="accordion" id="accordionExample2">
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
                                                        How do I raise capital for my business?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionExample2">
                                                    <div class="accordion-body">
                                                     <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                                        How do I manage business debt effectively?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionExample2">
                                                    <div class="accordion-body">
                                                     <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                                        How often should I review my financial strategy?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseEight" class="accordion-collapse collapse" data-bs-parent="#accordionExample2">
                                                    <div class="accordion-body">
                                                      <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                                                        Can you assist with mergers and acquisitions?
                                                      </button>
                                                    </h2>
                                                    <div id="collapseNine" class="accordion-collapse collapse" data-bs-parent="#accordionExample2">
                                                      <div class="accordion-body">
                                                        <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="space20"></div>
                                                  <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                                                        Can you help with business valuation?
                                                      </button>
                                                    </h2>
                                                    <div id="collapseTen" class="accordion-collapse collapse" data-bs-parent="#accordionExample2">
                                                      <div class="accordion-body">
                                                       <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                      </div>
                                                    </div>
                                                  </div>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                            <div class="faq-section-area">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="accordian-area">
                                            <div class="accordion" id="accordionExample3">
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEleven" aria-expanded="true" aria-controls="collapseEleven">
                                                        What services does your company offer?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseEleven" class="accordion-collapse collapse show" data-bs-parent="#accordionExample3">
                                                    <div class="accordion-body">
                                                     <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                                                       What industries do you specialize in?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseTwelve" class="accordion-collapse collapse" data-bs-parent="#accordionExample3">
                                                    <div class="accordion-body">
                                                     <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen">
                                                        How can IT solutions benefit my business?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseThirteen" class="accordion-collapse collapse" data-bs-parent="#accordionExample3">
                                                    <div class="accordion-body">
                                                      <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourteen" aria-expanded="false" aria-controls="collapseFourteen">
                                                        What industries do you specialize in?
                                                      </button>
                                                    </h2>
                                                    <div id="collapseFourteen" class="accordion-collapse collapse" data-bs-parent="#accordionExample3">
                                                      <div class="accordion-body">
                                                        <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="space20"></div>
                                                  <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFifteen" aria-expanded="false" aria-controls="collapseFifteen">
                                                       What is the process for onboarding new client?
                                                      </button>
                                                    </h2>
                                                    <div id="collapseFifteen" class="accordion-collapse collapse" data-bs-parent="#accordionExample3">
                                                      <div class="accordion-body">
                                                       <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                      </div>
                                                    </div>
                                                  </div>
                                              </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="accordian-area">
                                            <div class="accordion" id="accordionExample4">
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThirtysix" aria-expanded="true" aria-controls="collapseThirtysix">
                                                        What services does your company offer?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseThirtysix" class="accordion-collapse collapse" data-bs-parent="#accordionExample4">
                                                    <div class="accordion-body">
                                                     <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThirtyseven" aria-expanded="false" aria-controls="collapseThirtyseven">
                                                       What industries do you specialize in?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseThirtyseven" class="accordion-collapse collapse" data-bs-parent="#accordionExample4">
                                                    <div class="accordion-body">
                                                     <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThirtyeight" aria-expanded="false" aria-controls="collapseThirtyeight">
                                                        How can IT solutions benefit my business?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseThirtyeight" class="accordion-collapse collapse" data-bs-parent="#accordionExample4">
                                                    <div class="accordion-body">
                                                      <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThirtynine" aria-expanded="false" aria-controls="collapseThirtynine">
                                                        What industries do you specialize in?
                                                      </button>
                                                    </h2>
                                                    <div id="collapseThirtynine" class="accordion-collapse collapse" data-bs-parent="#accordionExample4">
                                                      <div class="accordion-body">
                                                        <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="space20"></div>
                                                  <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourty" aria-expanded="false" aria-controls="collapseFourty">
                                                       What is the process for onboarding new client?
                                                      </button>
                                                    </h2>
                                                    <div id="collapseFourty" class="accordion-collapse collapse" data-bs-parent="#accordionExample4">
                                                      <div class="accordion-body">
                                                       <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                      </div>
                                                    </div>
                                                  </div>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
                            <div class="faq-section-area">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="accordian-area">
                                            <div class="accordion" id="accordionExample5">
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSixteen" aria-expanded="true" aria-controls="collapseSixteen">
                                                        What services does your company offer?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseSixteen" class="accordion-collapse collapse show" data-bs-parent="#accordionExample5">
                                                    <div class="accordion-body">
                                                     <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeventeen" aria-expanded="false" aria-controls="collapseSeventeen">
                                                       What industries do you specialize in?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseSeventeen" class="accordion-collapse collapse" data-bs-parent="#accordionExample5">
                                                    <div class="accordion-body">
                                                     <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEightteen" aria-expanded="false" aria-controls="collapseEightteen">
                                                        How can IT solutions benefit my business?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseEightteen" class="accordion-collapse collapse" data-bs-parent="#accordionExample5">
                                                    <div class="accordion-body">
                                                      <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNineteen" aria-expanded="false" aria-controls="collapseNineteen">
                                                        What industries do you specialize in?
                                                      </button>
                                                    </h2>
                                                    <div id="collapseNineteen" class="accordion-collapse collapse" data-bs-parent="#accordionExample5">
                                                      <div class="accordion-body">
                                                        <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="space20"></div>
                                                  <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwenty" aria-expanded="false" aria-controls="collapseTwenty">
                                                       What is the process for onboarding new client?
                                                      </button>
                                                    </h2>
                                                    <div id="collapseTwenty" class="accordion-collapse collapse" data-bs-parent="#accordionExample5">
                                                      <div class="accordion-body">
                                                       <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                      </div>
                                                    </div>
                                                  </div>
                                              </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="accordian-area">
                                            <div class="accordion" id="accordionExample6">
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourtyone" aria-expanded="true" aria-controls="collapseFourtyone">
                                                        What services does your company offer?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseFourtyone" class="accordion-collapse collapse" data-bs-parent="#accordionExample6">
                                                    <div class="accordion-body">
                                                     <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourtytwo" aria-expanded="false" aria-controls="collapseFourtytwo">
                                                       What industries do you specialize in?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseFourtytwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample6">
                                                    <div class="accordion-body">
                                                     <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                  <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourtythree" aria-expanded="false" aria-controls="collapseFourtythree">
                                                        How can IT solutions benefit my business?
                                                    </button>
                                                  </h2>
                                                  <div id="collapseFourtythree" class="accordion-collapse collapse" data-bs-parent="#accordionExample6">
                                                    <div class="accordion-body">
                                                      <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                    </div>
                                                  </div>
                                                </div>
                                                <div class="space20"></div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourtyfour" aria-expanded="false" aria-controls="collapseFourtyfour">
                                                        What industries do you specialize in?
                                                      </button>
                                                    </h2>
                                                    <div id="collapseFourtyfour" class="accordion-collapse collapse" data-bs-parent="#accordionExample6">
                                                      <div class="accordion-body">
                                                        <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="space20"></div>
                                                  <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourtyfive" aria-expanded="false" aria-controls="collapseFourtyfive">
                                                       What is the process for onboarding new client?
                                                      </button>
                                                    </h2>
                                                    <div id="collapseFourtyfive" class="accordion-collapse collapse" data-bs-parent="#accordionExample6">
                                                      <div class="accordion-body">
                                                       <p>We serve clients across various including technology, manufacturing, healthcare, retail, and more our financial strategies are customized.</p> 
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
