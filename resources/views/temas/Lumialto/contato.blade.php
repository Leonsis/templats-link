@extends('temas.Lumialto.layouts.app')

@section('content')
<main class="main-wrapper">
    <header class="section_header1 color-scheme-1">
        <div class="padding-global">
            <div class="container-large">
                <div class="padding-section-large">
                    <div class="header1_component">
                        <div class="w-layout-grid header1_content">
                            <div class="header1_content-left">
                                <div class="margin-bottom margin-small">
                                    <h1 class="heading-style-h1">Entre em Contato e Cuide do Seu Carro</h1>
                                </div>
                                <p class="text-size-medium">Estamos aqui para atender suas necessidades automotivas. Agende seu orçamento sem compromisso e descubra como podemos cuidar do seu carro com excelência.</p>
                                <div class="margin-top margin-medium">
                                    <div class="button-group">
                                        <a href="#!" onclick="openWhatsAppModal()" class="button w-button">Enviar</a>
                                    </div>
                                </div>
                            </div>
                            <div class="header1_image-wrapper"><img sizes="(max-width: 2048px) 100vw, 2048px"
                                    alt="" src="{{ asset('temas/Lumialto/assets/images/relume-291771.jpeg') }}"" loading="eager" class="header1_image"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <section class="section_contact17 color-scheme-4" style=" background-color: #b2b2b2;">
        <div class="padding-global">
            <div class="container-large">
                <div class="padding-section-large">
                    <div class="contact17_component">
                        <div class="w-layout-grid contact17_grid-list">
                            <div class="contact17_item">
                                <div class="margin-bottom margin-small">
                                    <div class="contact17_icon-wrapper">
                                        <div class="icon-embed-medium w-embed text-size-medium"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 24 24" fill="none" preserveaspectratio="xMidYMid meet" aria-hidden="true" role="img">
                            <path d="M3.05359 5.74219V18.9463H20.9462V6.13867L20.1708 6.64844L12.2147 11.8867C12.1544 11.9184 12.102 11.944 12.0565 11.9619C12.0534 11.9629 12.0366 11.9678 11.9999 11.9678C11.9626 11.9678 11.946 11.9628 11.9432 11.9619C11.8975 11.9439 11.8448 11.9186 11.7841 11.8867L4.05359 6.7959V6.39648L11.7264 11.4219L11.9999 11.6016L12.2733 11.4229L20.62 5.97266L21.6307 5.31152C21.6464 5.38933 21.6551 5.46973 21.6552 5.55371V18.4463C21.6552 18.7675 21.5429 19.041 21.2938 19.2891C21.0449 19.5371 20.7701 19.6494 20.4462 19.6494H3.55359C3.23187 19.6494 2.95869 19.5371 2.71082 19.2891L2.6239 19.1953C2.43623 18.9725 2.35046 18.7279 2.35046 18.4463V5.55371C2.35048 5.46519 2.35953 5.38047 2.37683 5.29883L3.05359 5.74219ZM3.55359 4.34473H20.4462C20.7696 4.34473 21.0438 4.4579 21.2928 4.70703H21.2938C21.4035 4.81669 21.485 4.93216 21.5438 5.05371H2.46082C2.5195 4.93172 2.60127 4.81595 2.71082 4.70605H2.71179C2.95976 4.45718 3.23251 4.34476 3.55359 4.34473Z" fill="currentColor" stroke="currentColor"></path>
                          </svg></div>
                                    </div>
                                </div>
                                <div class="margin-bottom margin-xsmall">
                                    <h3 class="heading-style-h4 text-size-medium">E-mail</h3>
                                </div>
                                <p class="text-size-medium">Entre em contato conosco para esclarecer suas dúvidas ou agendar um serviço.</p>
                                <div class="margin-top margin-small">
                                    <a href="mailto:contato@lumiauto.com.br" class="text-style-link text-size-medium">contato@lumiauto.com.br</a>
                                </div>
                            </div>
                            <div class="contact17_item">
                                <div class="margin-bottom margin-small">
                                    <div class="contact17_icon-wrapper">
                                        <div class="icon-embed-medium w-embed text-size-medium"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 24 24" fill="none" preserveaspectratio="xMidYMid meet" aria-hidden="true" role="img">
                            <path d="M4.11902 3.34473H7.61902C7.80275 3.34474 7.93038 3.39945 8.03503 3.49805C8.12854 3.58614 8.20705 3.70059 8.2655 3.85156L8.31726 4.01465L8.9823 7.02832C9.01497 7.26011 9.00744 7.44511 8.97253 7.5918C8.94139 7.72276 8.8796 7.83104 8.78015 7.92578L8.77332 7.93164L6.25574 10.415L5.97644 10.6904L6.17664 11.0264C6.60337 11.7435 7.05685 12.416 7.53699 13.042C8.01981 13.6715 8.55599 14.2696 9.14441 14.8369C9.75779 15.4701 10.399 16.0453 11.0682 16.5605C11.7434 17.0804 12.4488 17.5353 13.1844 17.9248L13.5155 18.0996L13.7762 17.832L16.1815 15.3643L16.1913 15.3535C16.3309 15.2021 16.4775 15.111 16.6337 15.0645C16.7647 15.0256 16.8944 15.012 17.0253 15.0215L17.1571 15.0391L19.9774 15.6631C20.1431 15.7092 20.2749 15.7806 20.3817 15.875L20.4813 15.9775C20.5981 16.1186 20.6552 16.2764 20.6552 16.4707V19.8809C20.6552 20.1178 20.581 20.2895 20.4374 20.4316C20.2909 20.5766 20.1181 20.6494 19.8866 20.6494C18.0114 20.6494 16.0911 20.1991 14.1219 19.2842C12.1538 18.3698 10.3229 17.0691 8.62976 15.376C7.0427 13.7889 5.79967 12.0798 4.89539 10.249L4.71863 9.88086C3.80159 7.90938 3.35046 5.99008 3.35046 4.11914C3.35048 3.94128 3.39174 3.79881 3.47351 3.67773L3.56921 3.56348C3.71327 3.41863 3.88502 3.34473 4.11902 3.34473ZM4.06628 4.5752C4.09721 5.28818 4.20923 6.0365 4.39929 6.81836C4.59086 7.60639 4.89058 8.47618 5.2948 9.4248L5.58386 10.1035L6.1073 9.58301L8.12683 7.5752L8.32019 7.38281L8.26257 7.11621L7.69421 4.44922L7.60925 4.05371H4.04382L4.06628 4.5752ZM19.9462 16.377L19.5477 16.2949L17.0624 15.7803L16.7909 15.7246L16.5985 15.9248L14.6356 17.9824L14.1542 18.4863L14.787 18.7812C15.4818 19.1045 16.2353 19.3664 17.0448 19.5693C17.8555 19.7725 18.6477 19.8945 19.4208 19.9336L19.9462 19.96V16.377Z" fill="currentColor" stroke="currentColor"></path>
                          </svg></div>
                                    </div>
                                </div>
                                <div class="margin-bottom margin-xsmall">
                                    <h3 class="heading-style-h4 text-size-medium">Telefone</h3>
                                </div>
                                <p class="text-size-medium">Ligue para nós e obtenha informações sobre nossos serviços e preços.</p>
                                <div class="margin-top margin-small">
                                    <a href="tel:+556130421331" class="text-style-link text-size-medium">(61) 3042-1331</a>
                                </div>
                            </div>
                            <div class="contact17_item">
                                <div class="margin-bottom margin-small">
                                    <div class="contact17_icon-wrapper">
                                        <div class="icon-embed-medium w-embed text-size-medium"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 0 24 24" fill="none" preserveaspectratio="xMidYMid meet" aria-hidden="true" role="img">
                            <path d="M11.9999 2.34473C14.0242 2.34473 15.8029 3.04933 17.3553 4.47754C18.8789 5.87913 19.6552 7.7626 19.6552 10.1826C19.6551 11.1679 19.4382 12.1526 18.996 13.1406C18.5425 14.154 17.9734 15.1246 17.288 16.0527C16.5966 16.9889 15.8458 17.8627 15.035 18.6738C14.212 19.4974 13.4447 20.2259 12.7333 20.8594L12.7274 20.8643L12.7225 20.8691C12.632 20.9548 12.5274 21.017 12.4052 21.0566C12.2587 21.1041 12.1215 21.126 11.9921 21.126C11.8628 21.126 11.7292 21.104 11.5897 21.0576C11.474 21.0191 11.3759 20.959 11.2899 20.875L11.2811 20.8662L11.2714 20.8584C10.5564 20.2252 9.78741 19.497 8.96472 18.6738C8.15398 17.8627 7.40315 16.9889 6.71179 16.0527C6.02653 15.1248 5.45811 14.1547 5.00671 13.1416C4.56634 12.1534 4.35052 11.1682 4.35046 10.1826C4.35046 7.76231 5.12659 5.8791 6.64832 4.47754C8.19864 3.04953 9.9756 2.34475 11.9999 2.34473ZM11.9999 3.05371C10.0646 3.05371 8.40981 3.72261 7.06824 5.05664C5.71865 6.39887 5.05359 8.12233 5.05359 10.1826C5.0537 11.6021 5.6475 13.1413 6.75183 14.7861C7.85668 16.4317 9.49736 18.2606 11.6591 20.2715L11.996 20.585L12.3358 20.2764C14.5486 18.2689 16.2062 16.4381 17.2889 14.7852C18.3677 13.138 18.9461 11.5989 18.9462 10.1826C18.9462 8.12228 18.2812 6.39888 16.9315 5.05664V5.05566C15.5897 3.72177 13.935 3.05375 11.9999 3.05371ZM11.9979 8.70215C12.3641 8.7022 12.6619 8.82522 12.9188 9.08105C13.175 9.33617 13.2977 9.63248 13.2977 9.99805C13.2977 10.3642 13.1747 10.6605 12.9198 10.915C12.6649 11.1694 12.3682 11.292 12.0018 11.292C11.6348 11.2919 11.3391 11.169 11.0848 10.916C10.8313 10.6634 10.708 10.3691 10.7079 10.0029C10.7079 9.68146 10.8022 9.41279 10.996 9.17871L11.0848 9.08105C11.3378 8.82567 11.6324 8.70215 11.9979 8.70215Z" fill="currentColor" stroke="currentColor"></path>
                          </svg></div>
                                    </div>
                                </div>
                                <div class="margin-bottom margin-xsmall">
                                    <h3 class="heading-style-h4 text-size-medium">Endereço</h3>
                                </div>
                                <p class="text-size-medium">Visite nossa oficina para um atendimento personalizado e serviços de qualidade.</p>
                                <div class="margin-top margin-small">
                                    <a href="#!" class="text-style-link text-size-medium">SIA Trecho 3 - Guará, Brasília</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section_contact2 color-scheme-1">
        <div class="padding-global">
            <div class="container-large">
                <div class="padding-section-large">
                    <div class="contact2_component">
                        <div class="margin-bottom margin-xxlarge">
                            <div class="text-align-center">
                                <div class="max-width-large align-center">
                                    <div class="margin-bottom margin-xsmall">
                                        <div class="text-style-tagline">Contato</div>
                                    </div>
                                    <div class="margin-bottom margin-small">
                                        <h2 class="heading-style-h2">Fale Conosco</h2>
                                    </div>
                                    <p class="text-size-medium">Entre em contato com nossa equipe para mais informações.</p>
                                </div>
                            </div>
                        </div>
                        <div class="max-width-large align-center">
                            <div class="contact2_form-block w-form">
                                <form id="wf-form-Contact-2-Form" name="wf-form-Contact-2-Form" data-name="Contact 2 Form" method="get" class="contact2_form" data-wf-page-id="68c41705c11fbbc98aa599a9" data-wf-element-id="83f04ad8-9b7e-02f3-dd94-35c94feb022d" method="POST" action="{{ route('contato.enviar') }}">@csrf
                                    <div class="form_field-2col">
                                        <div class="form_field-wrapper"><label for="Contact-2-First-Name-2" class="form_field-label">Nome</label><input class="form_input w-input" maxlength="256" name="Contact-2-First-Name" data-name="Contact 2 First Name" placeholder="" type="text"
                                                id="Contact-2-First-Name-2" required="" value="{{ old('Contact 2 First Name') }}"></div>
                                        <div class="form_field-wrapper"><label for="Contact-2-Last-Name" class="form_field-label">Sobrenome</label><input class="form_input w-input" maxlength="256" name="Contact-2-Last-Name" data-name="Contact 2 Last Name" placeholder="" type="text"
                                                id="Contact-2-Last-Name" required="" value="{{ old('Contact 2 Last Name') }}"></div>
                                    </div>
                                    <div class="form_field-2col is-mobile-1col">
                                        <div class="form_field-wrapper"><label for="Contact-2-Email-2" class="form_field-label">E-mail</label><input class="form_input w-input" maxlength="256" name="Contact-2-Email-2" data-name="Contact 2 Email 2" placeholder="" type="email" id="Contact-2-Email-2"
                                                required="" value="{{ old('Contact 2 Email 2') }}"></div>
                                        <div class="form_field-wrapper"><label for="Contact-2-Phone" class="form_field-label">Telefone</label><input class="form_input w-input" maxlength="256" name="Contact-2-Phone" data-name="Contact 2 Phone" placeholder="" type="tel" id="Contact-2-Phone"
                                                required="" value="{{ old('Contact 2 Phone') }}"></div>
                                    </div>
                                    <div class="form_field-wrapper"><label for="Contact-2-Select" class="form_field-label">Escolha um tópico</label><select id="Contact-2-Select" name="Contact-2-Select" data-name="Contact 2 Select" required="" class="form_input is-select-input w-select">
                          <option value="">Selecione um...</option>
                          <option value="First">First Choice</option>
                          <option value="Second">Second Choice</option>
                          <option value="Third">Third Choice</option>
                        </select></div>
                                    <div class="padding-vertical padding-xsmall">
                                        <div class="form_field-wrapper">
                                            <div class="margin-bottom margin-xsmall"><label for="Contact-2-Select-2" class="form_field-label">Como você nos conheceu?</label></div>
                                            <div class="w-layout-grid form_radio-2col"><label class="form_radio w-radio">
                              <div class="w-form-formradioinput w-form-formradioinput--inputType-custom form_radio-icon w-radio-input"></div><input type="radio" name="Contact-2-Radio" id="Contact-2-Radio-1" data-name="Contact 2 Radio" style="opacity:0;position:absolute;z-index:-1" value="Contact 2 Radio 1" value="{{ old('Contact 2 Radio') }}"><span for="Contact-2-Radio-1" class="form_radio-label w-form-label">Primeira opção</span>
                            </label><label class="form_radio w-radio">
                              <div class="w-form-formradioinput w-form-formradioinput--inputType-custom form_radio-icon w-radio-input"></div><input type="radio" name="Contact-2-Radio" id="radio" data-name="Contact 2 Radio" style="opacity:0;position:absolute;z-index:-1" value="Contact 2 Radio 2" value="{{ old('Contact 2 Radio') }}"><span for="radio" class="form_radio-label w-form-label">Segunda opção</span>
                            </label><label class="form_radio w-radio">
                              <div class="w-form-formradioinput w-form-formradioinput--inputType-custom form_radio-icon w-radio-input"></div><input type="radio" name="Contact-2-Radio" id="Contact 2 Radio -7" data-name="Contact 2 Radio" style="opacity:0;position:absolute;z-index:-1" value="Contact 2 Radio 3" value="{{ old('Contact 2 Radio') }}"><span for="Contact 2 Radio -7" class="form_radio-label w-form-label">Terceira opção</span>
                            </label><label class="form_radio w-radio">
                              <div class="w-form-formradioinput w-form-formradioinput--inputType-custom form_radio-icon w-radio-input"></div><input type="radio" name="Contact-2-Radio" id="radio" data-name="Contact 2 Radio" style="opacity:0;position:absolute;z-index:-1" value="Contact 2 Radio 4" value="{{ old('Contact 2 Radio') }}"><span for="radio" class="form_radio-label w-form-label">Quarta opção</span>
                            </label><label class="form_radio w-radio">
                              <div class="w-form-formradioinput w-form-formradioinput--inputType-custom form_radio-icon w-radio-input"></div><input type="radio" name="Contact-2-Radio" id="Contact 2 Radio -9" data-name="Contact 2 Radio" style="opacity:0;position:absolute;z-index:-1" value="Contact 2 Radio 5" value="{{ old('Contact 2 Radio') }}"><span for="Contact 2 Radio -9" class="form_radio-label w-form-label">Quinta opção</span>
                            </label><label class="form_radio w-radio">
                              <div class="w-form-formradioinput w-form-formradioinput--inputType-custom form_radio-icon w-radio-input"></div><input type="radio" name="Contact-2-Radio" id="Contact 2 Radio -10" data-name="Contact 2 Radio" style="opacity:0;position:absolute;z-index:-1" value="Contact 2 Radio 6" value="{{ old('Contact 2 Radio') }}"><span for="Contact 2 Radio -10" class="form_radio-label w-form-label">Outro</span>
                            </label></div>
                                        </div>
                                    </div>
                                    <div class="form_field-wrapper"><label for="Contact-2-Message" class="form_field-label">Mensagem</label><textarea id="Contact-2-Message" name="Contact-2-Message" maxlength="5000" data-name="Contact 2 Message" placeholder="Digite sua mensagem..." required=""
                                            class="form_input is-text-area w-input">{{ old('Contact 2 Message', '') }}</textarea></div>
                                    <div class="margin-bottom margin-xsmall"><label id="Contact-2-Checkbox" class="w-checkbox form_checkbox">
                          <div class="w-checkbox-input w-checkbox-input--inputType-custom form_checkbox-icon"></div><input type="checkbox" name="Contact-2-Checkbox" id="Contact 2 Checkbox" data-name="Contact 2 Checkbox" required="" style="opacity:0;position:absolute;z-index:-1" value="{{ old('Contact 2 Checkbox') }}"><span for="Contact-2-Checkbox" class="form_checkbox-label w-form-label">Aceito os Termos</span>
                        </label></div><input type="submit" data-wait="Please wait..." id="w-node-_83f04ad8-9b7e-02f3-dd94-35c94feb026b-8aa599a9" class="button w-button" value="Enviar">
                                </form>
                                <div class="form_message-success-wrapper w-form-done">
                                    <div class="form_message-success">
                                        <div class="success-text">Obrigado! Sua mensagem foi recebida com sucesso!</div>
                                    </div>
                                </div>
                                <div class="form_message-error-wrapper w-form-fail">
                                    <div class="form_message-error">
                                        <div class="error-text">Oops! Algo deu errado ao enviar o formulário.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
