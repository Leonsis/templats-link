@extends('temas.Finazze.layouts.app')

@section('content')
<!--===== HERO AREA STARTS =======-->
<div class="inner-pages-section-area" style="background-image: url({{ asset('temas/Finazze/assets/img/all-images/bg/hero-bg1.png') }}); background-position: center; background-repeat: no-repeat; background-size: cover;">
  <div class="container">
    <div class="row">
        <div class="col-lg-12 m-auto">
            <div class="inner-header text-center">
            <h2>Our Team</h2>
            <div class="space24"></div>
            <a href="{{ route('home') }}">Home <i class="fa-solid fa-angle-right"></i> <span>Our Team</span></a>
            </div>
        </div>
    </div>
  </div>
</div>
<!--===== HERO AREA ENDS =======-->

<!--===== TEAM AREA STARTS =======-->
<div class="team1-section-area sp2">
    <div class="container">
  
      <div class="row">
        <div class="col-lg-4 col-md-6">
          <div class="team-boxarea">
            <div class="img1">
              <img src="{{ asset('temas/Finazze/assets/img/all-images/team/team-img1.png') }}"" alt="">
              <ul>
                <li><a href="team.html#"><i class="fa-brands fa-facebook-f"></i></a></li>
                <li><a href="team.html#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                <li><a href="team.html#"><i class="fa-brands fa-instagram"></i></a></li>
                <li><a href="team.html#" class="m-0"><i class="fa-brands fa-youtube"></i></a></li>
              </ul>
            </div>
            <div class="space24"></div>
            <div class="content-area">
              <a href="{{ route('tema.Finazze.team') }}">Erma Hansen</a>
              <div class="space8"></div>
              <p>Management Advisor</p>
            </div>
          </div>
        </div>
  
        <div class="col-lg-4 col-md-6">
          <div class="team-boxarea">
            <div class="img1">
              <img src="{{ asset('temas/Finazze/assets/img/all-images/team/team-img2.png') }}"" alt="">
              <ul>
                <li><a href="team.html#"><i class="fa-brands fa-facebook-f"></i></a></li>
                <li><a href="team.html#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                <li><a href="team.html#"><i class="fa-brands fa-instagram"></i></a></li>
                <li><a href="team.html#" class="m-0"><i class="fa-brands fa-youtube"></i></a></li>
              </ul>
            </div>
            <div class="space24"></div>
            <div class="content-area">
              <a href="{{ route('tema.Finazze.team') }}">Ignacio Goldner</a>
              <div class="space8"></div>
              <p>Management Advisor</p>
            </div>
          </div>
        </div>
  
        <div class="col-lg-4 col-md-6">
          <div class="team-boxarea">
            <div class="img1">
              <img src="{{ asset('temas/Finazze/assets/img/all-images/team/team-img3.png') }}"" alt="">
              <ul>
                <li><a href="team.html#"><i class="fa-brands fa-facebook-f"></i></a></li>
                <li><a href="team.html#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                <li><a href="team.html#"><i class="fa-brands fa-instagram"></i></a></li>
                <li><a href="team.html#" class="m-0"><i class="fa-brands fa-youtube"></i></a></li>
              </ul>
            </div>
            <div class="space24"></div>
            <div class="content-area">
              <a href="{{ route('tema.Finazze.team') }}">Jamie Veum</a>
              <div class="space8"></div>
              <p>Founder & CEO</p>
            </div>
          </div>
        </div>
  
        <div class="col-lg-4 col-md-6">
          <div class="team-boxarea">
            <div class="img1">
              <img src="{{ asset('temas/Finazze/assets/img/all-images/team/team-img5.png') }}"" alt="">
              <ul>
                <li><a href="team.html#"><i class="fa-brands fa-facebook-f"></i></a></li>
                <li><a href="team.html#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                <li><a href="team.html#"><i class="fa-brands fa-instagram"></i></a></li>
                <li><a href="team.html#" class="m-0"><i class="fa-brands fa-youtube"></i></a></li>
              </ul>
            </div>
            <div class="space24"></div>
            <div class="content-area">
              <a href="{{ route('tema.Finazze.team') }}">Jessie Bogisich</a>
              <div class="space8"></div>
              <p>Debt Management </p>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="team-boxarea">
              <div class="img1">
                <img src="{{ asset('temas/Finazze/assets/img/all-images/team/team-img6.png') }}"" alt="">
                <ul>
                  <li><a href="team.html#"><i class="fa-brands fa-facebook-f"></i></a></li>
                  <li><a href="team.html#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                  <li><a href="team.html#"><i class="fa-brands fa-instagram"></i></a></li>
                  <li><a href="team.html#" class="m-0"><i class="fa-brands fa-youtube"></i></a></li>
                </ul>
              </div>
              <div class="space24"></div>
              <div class="content-area">
                <a href="{{ route('tema.Finazze.team') }}">Sabrina Feest</a>
                <div class="space8"></div>
                <p>Management Advisor</p>
              </div>
            </div>
          </div>
    
          <div class="col-lg-4 col-md-6">
            <div class="team-boxarea">
              <div class="img1">
                <img src="{{ asset('temas/Finazze/assets/img/all-images/team/team-img7.png') }}"" alt="">
                <ul>
                  <li><a href="team.html#"><i class="fa-brands fa-facebook-f"></i></a></li>
                  <li><a href="team.html#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                  <li><a href="team.html#"><i class="fa-brands fa-instagram"></i></a></li>
                  <li><a href="team.html#" class="m-0"><i class="fa-brands fa-youtube"></i></a></li>
                </ul>
              </div>
              <div class="space24"></div>
              <div class="content-area">
                <a href="{{ route('tema.Finazze.team') }}">Alyssa Connelly</a>
                <div class="space8"></div>
                <p>Management Advisor</p>
              </div>
            </div>
          </div>
    
          <div class="col-lg-4 col-md-6">
            <div class="team-boxarea">
              <div class="img1">
                <img src="{{ asset('temas/Finazze/assets/img/all-images/team/team-img10.png') }}"" alt="">
                <ul>
                  <li><a href="team.html#"><i class="fa-brands fa-facebook-f"></i></a></li>
                  <li><a href="team.html#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                  <li><a href="team.html#"><i class="fa-brands fa-instagram"></i></a></li>
                  <li><a href="team.html#" class="m-0"><i class="fa-brands fa-youtube"></i></a></li>
                </ul>
              </div>
              <div class="space24"></div>
              <div class="content-area">
                <a href="{{ route('tema.Finazze.team') }}">Cesar Schuster</a>
                <div class="space8"></div>
                <p>Founder & CEO</p>
              </div>
            </div>
          </div>
    
          <div class="col-lg-4 col-md-6">
            <div class="team-boxarea">
              <div class="img1">
                <img src="{{ asset('temas/Finazze/assets/img/all-images/team/team-img8.png') }}"" alt="">
                <ul>
                  <li><a href="team.html#"><i class="fa-brands fa-facebook-f"></i></a></li>
                  <li><a href="team.html#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                  <li><a href="team.html#"><i class="fa-brands fa-instagram"></i></a></li>
                  <li><a href="team.html#" class="m-0"><i class="fa-brands fa-youtube"></i></a></li>
                </ul>
              </div>
              <div class="space24"></div>
              <div class="content-area">
                <a href="{{ route('tema.Finazze.team') }}">Velma Kulas</a>
                <div class="space8"></div>
                <p>Debt Management </p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6">
            <div class="team-boxarea">
              <div class="img1">
                <img src="{{ asset('temas/Finazze/assets/img/all-images/team/team-img9.png') }}"" alt="">
                <ul>
                  <li><a href="team.html#"><i class="fa-brands fa-facebook-f"></i></a></li>
                  <li><a href="team.html#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                  <li><a href="team.html#"><i class="fa-brands fa-instagram"></i></a></li>
                  <li><a href="team.html#" class="m-0"><i class="fa-brands fa-youtube"></i></a></li>
                </ul>
              </div>
              <div class="space24"></div>
              <div class="content-area">
                <a href="{{ route('tema.Finazze.team') }}">Jared Sipes V</a>
                <div class="space8"></div>
                <p>Debt Management  </p>
              </div>
            </div>
          </div>

          <div class="col-lg-12">
            <div class="space18"></div>
            <div class="pagination-area">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                      <li class="page-item">
                        <a class="page-link" href="team.html#" aria-label="Previous">
                            <i class="fa-solid fa-angle-left"></i>
                        </a>
                      </li>
                      <li class="page-item"><a class="page-link" href="team.html#">1</a></li>
                      <li class="page-item"><a class="page-link" href="team.html#">2</a></li>
                      <li class="page-item"><a class="page-link" href="team.html#">...</a></li>
                      <li class="page-item"><a class="page-link" href="team.html#">12</a></li>
                      <li class="page-item">
                        <a class="page-link" href="team.html#" aria-label="Next">
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
  <!--===== TEAM AREA ENDS =======-->

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
