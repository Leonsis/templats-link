@extends('temas.Portfolio.layouts.app')

@section('html')
  <html data-wf-page="68ae0f4abe2cd95cb8d9c529" data-wf-site="68ae0f48be2cd95cb8d9c3c8" lang="pt-BR">
@endsection

@section('content')
  <div class="banner-content-area">
      <div class="w-layout-blockcontainer container w-container">
        <div data-w-id="2ce49ce4-1793-fa1a-a9c5-3bfb98322c2d" style="opacity:0" class="banner-title-wrap">
          <h1 class="banner-title">Entre em contato <br>‍<span class="section-title-sub-text">hoje mesmo</span></h1>
        </div>
      </div>
    </div>
  </section>
  <section class="contact-section">
    <div class="w-layout-blockcontainer container w-container">
      <div class="contact-grid-wrap">
        <div class="w-layout-grid contact-grid">
          <div data-w-id="4e9c6d63-ea3f-9283-805e-44be500158ff" style="opacity:0" class="contact-image-wrap"><img src="{{ asset('temas/Portfolio/assets/images/Gemini_Generated_Image_pnycbcpnycbcpnyc.jpeg') }}" loading="lazy"  alt="" class="contact-image">
            <div data-w-id="e2c5d08f-72e7-aa9b-9a03-8dc71037ffa6" style="background-color:rgb(236,231,225);width:100%;height:100%" class="image-overlay"></div>
          </div>
          <div id="w-node-_7af8fa85-81f9-6132-176c-ac0dbb701ab7-b8d9c529" data-w-id="7af8fa85-81f9-6132-176c-ac0dbb701ab7" style="opacity:0" class="contact-form-area">
            <h2 class="contact-form-title">Entre em <span class="section-title-sub-text">contato!</span></h2>
            <div class="contact-form-block w-form">
              <form id="contactForm" name="wf-form-Contact-Form" data-name="Contact Form" method="get" class="contact-form" data-wf-page-id="68ae0f4abe2cd95cb8d9c529" data-wf-element-id="a28bb2bd-9a7a-07cf-b6ac-51b3fcfcfb4c">
                <div class="contact-form-input-area">
                    <div class="contact-form-input-wrap">
                        <input class="contact-form-input w-input" maxlength="256" name="name" data-name="name" placeholder="Nome" type="text" id="name" required="">
                        <input class="contact-form-input w-input" maxlength="256" name="phone" data-name="phone" placeholder="Telefone" type="tel" id="phone" required="">
                        
                    </div>
                    <div class="contact-form-input-wrap">
                        <input class="contact-form-input w-input" maxlength="256" name="email" data-name="Email" placeholder="Email" type="email" id="email" required="">
                    </div>
                    <style>
                        .style-Select {
                            background: none !important;
                            border-top: 0px !important;
                            border-bottom: 2px solid #e1e1e1 !important;
                            border-left: 0 !important;
                            border-right: 0 !important;
                            border-radius: 0 !important;
                        }
                        .style-label{
                            font-size: 18px !important;
                            font-family: var(--font--title) !important;
                            color: var(--font-color--secondary) !important;
                        }
                    </style>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="service" class="style-label">Planos *</label>
                        <select id="service" name="service" class="style-Select" required>
                            <option value="Particular">Particular</option>
                            <option value="AFEB">AFEB</option>
                            <option value="AFFEGO">AFFEGO</option>
                            <option value="ALLIANZ">ALLIANZ</option>
                            <option value="AMIL">AMIL</option>
                            <option value="ANAFE">ANAFE</option>
                            <option value="BACEN">BACEN</option>
                            <option value="BNDES">BNDES</option>
                            <option value="BRADESCO OPERADORA">BRADESCO OPERADORA</option>
                            <option value="BRADESCO SAUDE">BRADESCO SAUDE</option>
                            <option value="BRB SAÚDE">BRB SAÚDE</option>
                            <option value="CAEME GO">CAEME GO</option>
                            <option value="CAESAN">CAESAN</option>
                            <option value="CAPESESP">CAPESESP</option>
                            <option value="CARE PLUS">CARE PLUS</option>
                            <option value="CASEC/CODESVASF">CASEC/CODESVASF</option>
                            <option value="CASEMBRAPA">CASEMBRAPA</option>
                            <option value="CASSI">CASSI</option>
                            <option value="CNTI">CNTI</option>
                            <option value="CODEVASF">CODEVASF</option>
                            <option value="CONAB">CONAB</option>
                            <option value="DASA">DASA</option>
                            <option value="EMBRATEL">EMBRATEL</option>
                            <option value="EVIDA">EVIDA</option>
                            <option value="FAPES/BNDES">FAPES/BNDES</option>
                            <option value="FASCAL">FASCAL</option>
                            <option value="FUSEX">FUSEX</option>
                            <option value="FUSMA">FUSMA</option>
                            <option value="GAMA SAÚDE">GAMA SAÚDE</option>
                            <option value="GDF (Inas)">GDF (Inas)</option>
                            <option value="GEAP">GEAP</option>
                            <option value="GMEDI">GMEDI</option>
                            <option value="GRAVIA">GRAVIA</option>
                            <option value="HFA">HFA</option>
                            <option value="INTERCLINICAS">INTERCLINICAS</option>
                            <option value="LIFE EMPRESARIAL">LIFE EMPRESARIAL</option>
                            <option value="LUMINAR/EVIDA">LUMINAR/EVIDA</option>
                            <option value="NOTREDAME/INTERMEDICA">NOTREDAME/INTERMEDICA</option>
                            <option value="OAB/CAADF">OAB/CAADF</option>
                            <option value="OMINT">OMINT</option>
                            <option value="ONELIVE">ONELIVE</option>
                            <option value="PETROBRAS">PETROBRAS</option>
                            <option value="PLAN ASSISTE MPU">PLAN ASSISTE MPU</option>
                            <option value="PMDF">PMDF</option>
                            <option value="POLICIA FEDERAL">POLICIA FEDERAL</option>
                            <option value="PRO-SAUDE/CAMARA DOS DEPUTADOS">PRO-SAUDE/CAMARA DOS DEPUTADOS</option>
                            <option value="PROASA">PROASA</option>
                            <option value="REAL GRANDEZA">REAL GRANDEZA</option>
                            <option value="REDE MAIS SAUDE">REDE MAIS SAUDE</option>
                            <option value="SAMP/AGMP">SAMP/AGMP</option>
                            <option value="SAUDE CAIXA">SAUDE CAIXA</option>
                            <option value="SERPRO">SERPRO</option>
                            <option value="SIN SAUDE CARD">SIN SAUDE CARD</option>
                            <option value="SIS SENADO">SIS SENADO</option>
                            <option value="STF MED">STF MED</option>
                            <option value="STJ/PRO SER">STJ/PRO SER</option>
                            <option value="STM/PLAS JMU">STM/PLAS JMU</option>
                            <option value="SULAMERICA">SULAMERICA</option>
                            <option value="TJDFT PRO-SAUDE">TJDFT PRO-SAUDE</option>
                            <option value="TRE">TRE</option>
                            <option value="TRF/PRÓ SOCIAL">TRF/PRÓ SOCIAL</option>
                            <option value="TRT">TRT</option>
                            <option value="TST">TST</option>
                            <option value="UNAFISCO">UNAFISCO</option>
                            <option value="VAI BEM">VAI BEM</option>
                            <option value="VALE SAUDE SEMPRE- VIDA V">VALE SAUDE SEMPRE- VIDA V</option>
                            <option value="VALEMED">VALEMED</option>
                        </select>
                    </div>
                </div>
                <div class="contact-button-wrap">
                    <input type="submit" data-wait="Por favor, aguarde..." class="contact-button w-button" value="Enviar">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="contact-information-section">
    <div class="w-layout-blockcontainer container w-container">
      <div class="contact-information-grid-wrap">
        <div class="w-layout-grid contact-information-grid">
          <div data-w-id="34528b55-f54c-6304-49db-cc25ab4823da" style="opacity:0" class="contact-information-area">
            <div>
              <div class="contact-information-title-wrap">
                <h2 class="contact-information-title">Estamos aqui para você! <br>Entre em contato para qualquer suporte ou dúvida.</h2>
              </div>
              <div class="contact-information-card-wrap">
                <div class="contact-information-address">
                  <div class="contact-information-icon-wrap"></div>
                </div>
                <a href="tel:+5561992394714" class="contact-information-card w-inline-block">
                  <div class="contact-information-icon-wrap"><img src="{{ asset('temas/Portfolio/assets/images/Contact-information-icon-dark-02.svg') }}" loading="lazy" alt="Contact Icon" class="contact-initial-icon"><img src="{{ asset('temas/Portfolio/assets/images/Contact-information-icon-yellow-02.svg') }}" loading="lazy" alt="Contact Icon" class="contact-hover-icon"></div>
                  <div>(61) 9 9239-4714</div>
                </a>
                <a href="mailto:clinicas.ampiezza@gmail.com" class="contact-information-card w-inline-block">
                  <div class="contact-information-icon-wrap"><img src="{{ asset('temas/Portfolio/assets/images/Contact-information-icon-dark-03.svg') }}" loading="lazy" alt="Contact Icon" class="contact-initial-icon"><img src="{{ asset('temas/Portfolio/assets/images/Contact-information-icon-yellow-03.svg') }}" loading="lazy" alt="Contact Icon" class="contact-hover-icon"></div>
                  <div>Email</div>
                </a>
              </div>
            </div>
            <div class="contact-share-holder">
              <div>Siga-nos:</div>
              <div class="contact-share-area">
                <a href="https://www.facebook.com/Ampiezza/" target="_blank" class="contact-share-link w-inline-block"><img src="{{ asset('temas/Portfolio/assets/images/Contact-social-initial-01.svg') }}" loading="lazy" alt="Social Icon" class="contact-social-icon"></a>
                <a href="https://x.com/ampiezza1" target="_blank" class="contact-share-link w-inline-block"><img src="{{ asset('temas/Portfolio/assets/images/Contact-social-initial-02.svg') }}" loading="lazy" alt="Social Icon" class="contact-social-icon"></a>
                <a href="https://www.youtube.com/@ampiezzaclinicasintegradas" target="_blank" class="contact-share-link w-inline-block"><img src="{{ asset('temas/Portfolio/assets/images/Contact-social-initial-03.svg') }}" loading="lazy" alt="Social Icon" class="contact-social-icon"></a>
              </div>
            </div>
          </div>
          <div id="w-node-d3a00978-74a7-6264-9556-951752259cc8-b8d9c529" data-w-id="d3a00978-74a7-6264-9556-951752259cc8" style="opacity:0" class="contact-information-image-wrap"><img src="{{ asset('temas/Portfolio/assets/images/Gemini_Generated_Image_5ungp5ungp5ungp5-1-1.png') }}" loading="lazy" alt="" class="contact-information-image">
            <div data-w-id="c8191f23-2546-92f0-6121-b45bec40b0b9" style="background-color:rgb(236,231,225);width:100%;height:100%" class="image-overlay"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="location-section">
    <div class="w-layout-blockcontainer container w-container">
      <div data-w-id="cb46add2-42da-e03b-ac68-69f9c89bdca2" style="opacity:0" class="section-title-wrap center-align">
        <h2 class="section-title">Todas as <span class="section-title-sub-text">Unidades</span></h2>
        <p class="location-title-content">xplore nossos serviços de fisioterapia disponíveis em todas as unidades, oferecendo cuidados especializados e tratamento personalizado para todos os pacientes.</p>
      </div>
      <div class="location-grid-wrap">
        <div class="w-layout-grid location-grid">
          <div data-w-id="b0e44dfc-44dd-e9e7-e334-536297333c89" style="opacity:0" class="location-card">
            <div class="location-card-image-wrap"><img src="{{ asset('temas/Portfolio/assets/images/13.jpg') }}" loading="lazy" alt="" class="location-card-image">
              <div data-w-id="058cb81f-e320-7917-170e-97dc0d3f57de" style="background-color:rgb(236,231,225);width:100%;height:100%" class="image-overlay"></div>
            </div>
            <div>
              <div class="contact-information-address">
                <div class="contact-information-icon-wrap"><img src="{{ asset('temas/Portfolio/assets/images/Contact-information-icon-dark-01.svg') }}" loading="lazy" alt="Contact Icon" class="contact-initial-icon"><img src="{{ asset('temas/Portfolio/assets/images/Contact-information-icon-yellow-01.svg') }}" loading="lazy" alt="Contact Icon" class="contact-hover-icon"></div>
                <div><strong>Vista Shopping, R. das Figueiras, Lote 07 - Sala 1805, Águas Claras, Brasília - DF</strong></div>
              </div>
            </div>
          </div>
          <div class="location-card">
            <div class="location-card-image-wrap"><img src="{{ asset('temas/Portfolio/assets/images/21-1.jpg') }}" loading="lazy" alt="" class="location-card-image">
              <div data-w-id="8ca0d52c-b5ab-8520-c0e7-ef6c8019a0ba" style="background-color:rgb(236,231,225);width:100%;height:100%" class="image-overlay"></div>
            </div>
            <div>
              <div class="contact-information-address">
                <div class="contact-information-icon-wrap"><img src="{{ asset('temas/Portfolio/assets/images/Contact-information-icon-dark-01.svg') }}" loading="lazy" alt="Contact Icon" class="contact-initial-icon"><img src="{{ asset('temas/Portfolio/assets/images/Contact-information-icon-yellow-01.svg') }}" loading="lazy" alt="Contact Icon" class="contact-hover-icon"></div>
                <div><strong>BL A - Prime Excelência Médica, Setor C Norte, Lote 01 a 12, Sala 1006 a 1014, Taguatinga Norte</strong></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="contact-faq">
    <div class="w-layout-blockcontainer container w-container">
      <div class="faq-grid-wrap">
        <div class="w-layout-grid faq-grid">
          <div data-w-id="f8d98ea9-fbc0-8f9c-7aeb-31063075fafe" class="faq-title-wrap">
            <h2 class="section-title">Perguntas <span class="section-title-sub-text">Frequentes</span></h2>
          </div>
          <div data-w-id="f8d98ea9-fbc0-8f9c-7aeb-31063075fb03" class="faq-area">
            <div data-hover="false" data-delay="0" data-w-id="f8d98ea9-fbc0-8f9c-7aeb-31063075fb04" class="faq-dropdown w-dropdown">
              <div class="faq-dropdown-toggle w-dropdown-toggle">
                <div><strong>Quais condições vocês tratam?</strong></div>
                <div class="faq-dropdown-icon-wrap"><img src="{{ asset('temas/Portfolio/assets/images/FAQ-arrow-dark.svg') }}" loading="lazy" alt="FAQ Arrow" class="faq-arrow-image initial-arrow"><img src="{{ asset('temas/Portfolio/assets/images/FAQ-arrow-light.svg') }}" loading="lazy" alt="FAQ Arrow" class="faq-arrow-image hover-arrow"></div>
              </div>
              <nav class="faq-dropdown-list w-dropdown-list">
                <div class="faq-dropdown-content-wrap">
                  <p class="faq-dropdown-content">Tratamos dores nas costas, lesões esportivas, artrite, recuperação pós-cirúrgica e muito mais, com tratamentos de fisioterapia personalizados.</p>
                </div>
              </nav>
            </div>
            <div data-hover="false" data-delay="0" data-w-id="f8d98ea9-fbc0-8f9c-7aeb-31063075fb0f" class="faq-dropdown w-dropdown">
              <div class="faq-dropdown-toggle w-dropdown-toggle">
                <div><strong>Preciso de encaminhamento médico?</strong></div>
                <div class="faq-dropdown-icon-wrap"><img src="{{ asset('temas/Portfolio/assets/images/FAQ-arrow-dark.svg') }}" loading="lazy" alt="FAQ Arrow" class="faq-arrow-image initial-arrow"><img src="{{ asset('temas/Portfolio/assets/images/FAQ-arrow-light.svg') }}" loading="lazy" alt="FAQ Arrow" class="faq-arrow-image hover-arrow"></div>
              </div>
              <nav class="faq-dropdown-list w-dropdown-list">
                <div class="faq-dropdown-content-wrap">
                  <p class="faq-dropdown-content">Não é necessário encaminhamento! Você pode agendar diretamente conosco para iniciar sua jornada de fisioterapia e receber cuidados profissionais sem demora.</p>
                </div>
              </nav>
            </div>
            <div data-hover="false" data-delay="0" data-w-id="f8d98ea9-fbc0-8f9c-7aeb-31063075fb1a" class="faq-dropdown w-dropdown">
              <div class="faq-dropdown-toggle w-dropdown-toggle">
                <div><strong>O que devo vestir?</strong></div>
                <div class="faq-dropdown-icon-wrap"><img src="{{ asset('temas/Portfolio/assets/images/FAQ-arrow-dark.svg') }}" loading="lazy" alt="FAQ Arrow" class="faq-arrow-image initial-arrow"><img src="{{ asset('temas/Portfolio/assets/images/FAQ-arrow-light.svg') }}" loading="lazy" alt="FAQ Arrow" class="faq-arrow-image hover-arrow"></div>
              </div>
              <nav class="faq-dropdown-list w-dropdown-list">
                <div class="faq-dropdown-content-wrap">
                  <p class="faq-dropdown-content">Use roupas flexíveis e confortáveis, como roupas de academia, para garantir facilidade de movimento e permitir o acesso às áreas que precisam de tratamento.</p>
                </div>
              </nav>
            </div>
            <div data-hover="false" data-delay="0" data-w-id="f8d98ea9-fbc0-8f9c-7aeb-31063075fb25" class="faq-dropdown w-dropdown">
              <div class="faq-dropdown-toggle w-dropdown-toggle">
                <div><strong>Quanto tempo dura cada sessão?</strong></div>
                <div class="faq-dropdown-icon-wrap"><img src="{{ asset('temas/Portfolio/assets/images/FAQ-arrow-dark.svg') }}" loading="lazy" alt="FAQ Arrow" class="faq-arrow-image initial-arrow"><img src="{{ asset('temas/Portfolio/assets/images/FAQ-arrow-light.svg') }}" loading="lazy" alt="FAQ Arrow" class="faq-arrow-image hover-arrow"></div>
              </div>
              <nav class="faq-dropdown-list w-dropdown-list">
                <div class="faq-dropdown-content-wrap">
                  <p class="faq-dropdown-content">Nossas sessões de fisioterapia geralmente têm duração de 30 a 60 minutos, garantindo um cuidado personalizado para atender às suas necessidades específicas.</p>
                </div>
              </nav>
            </div>
            <div data-hover="false" data-delay="0" data-w-id="f8d98ea9-fbc0-8f9c-7aeb-31063075fb30" class="faq-dropdown w-dropdown">
              <div class="faq-dropdown-toggle w-dropdown-toggle">
                <div><strong>A fisioterapia é coberta pelo convênio?</strong></div>
                <div class="faq-dropdown-icon-wrap"><img src="{{ asset('temas/Portfolio/assets/images/FAQ-arrow-dark.svg') }}" loading="lazy" alt="FAQ Arrow" class="faq-arrow-image initial-arrow"><img src="{{ asset('temas/Portfolio/assets/images/FAQ-arrow-light.svg') }}" loading="lazy" alt="FAQ Arrow" class="faq-arrow-image hover-arrow"></div>
              </div>
              <nav class="faq-dropdown-list w-dropdown-list">
                <div class="faq-dropdown-content-wrap">
                  <p class="faq-dropdown-content">Muitos planos de seguro incluem cobertura para fisioterapia. Entre em contato com seu fornecedor de seguro para confirmar a extensão dos seus benefícios.</p>
                </div>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection