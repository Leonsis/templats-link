<div class="page-wrapper">
    <div class="global-styles">
        <div class="style-overrides w-embed">
            <style>
                /* Ensure all elements inherit the color from its parent */
                
                a,
                .w-input,
                .w-select,
                .w-tab-link,
                .w-nav-link,
                .w-nav-brand,
                .w-dropdown-btn,
                .w-dropdown-toggle,
                .w-slider-arrow-left,
                .w-slider-arrow-right,
                .w-dropdown-link {
                    color: inherit;
                    text-decoration: inherit;
                    font-size: inherit;
                }
                /* Focus state style for keyboard navigation for the focusable elements */
                
                *[tabindex]:focus-visible,
                input[type="file"]:focus-visible {
                    outline: 0.125rem solid #4d65ff;
                    outline-offset: 0.125rem;
                }
                /* Get rid of top margin on first element in any rich text element */
                
                .w-richtext> :not(div):first-child,
                .w-richtext>div:first-child> :first-child {
                    margin-top: 0 !important;
                }
                /* Get rid of bottom margin on last element in any rich text element */
                
                .w-richtext> :last-child,
                .w-richtext ol li:last-child,
                .w-richtext ul li:last-child {
                    margin-bottom: 0 !important;
                }
                /* Prevent all click and hover interaction with an element */
                
                .pointer-events-off {
                    pointer-events: none;
                }
                /* Enables all click and hover interaction with an element */
                
                .pointer-events-on {
                    pointer-events: auto;
                }
                /* Create a class of .div-square which maintains a 1:1 dimension of a div */
                
                .div-square::after {
                    content: "";
                    display: block;
                    padding-bottom: 100%;
                }
                /* Make sure containers never lose their center alignment */
                
                .container-medium,
                .container-small,
                .container-large {
                    margin-right: auto !important;
                    margin-left: auto !important;
                }
                /* Apply "..." after 3 lines of text */
                
                .text-style-3lines {
                    display: -webkit-box;
                    overflow: hidden;
                    -webkit-line-clamp: 3;
                    -webkit-box-orient: vertical;
                }
                /* Apply "..." after 2 lines of text */
                
                .text-style-2lines {
                    display: -webkit-box;
                    overflow: hidden;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                }
                /* Adds inline flex display */
                
                .display-inlineflex {
                    display: inline-flex;
                }
                /* These classes are never overwritten */
                
                .hide {
                    display: none !important;
                }
                /* Remove default Webflow chevron from form select */
                
                select {
                    -webkit-appearance: none;
                }
                
                @media screen and (max-width: 991px) {
                    .hide,
                    .hide-tablet {
                        display: none !important;
                    }
                }
                
                @media screen and (max-width: 767px) {
                    .hide-mobile-landscape {
                        display: none !important;
                    }
                }
                
                @media screen and (max-width: 479px) {
                    .hide-mobile {
                        display: none !important;
                    }
                }
                
                .margin-0 {
                    margin: 0rem !important;
                }
                
                .padding-0 {
                    padding: 0rem !important;
                }
                
                .spacing-clean {
                    padding: 0rem !important;
                    margin: 0rem !important;
                }
                
                .margin-top {
                    margin-right: 0rem !important;
                    margin-bottom: 0rem !important;
                    margin-left: 0rem !important;
                }
                
                .padding-top {
                    padding-right: 0rem !important;
                    padding-bottom: 0rem !important;
                    padding-left: 0rem !important;
                }
                
                .margin-right {
                    margin-top: 0rem !important;
                    margin-bottom: 0rem !important;
                    margin-left: 0rem !important;
                }
                
                .padding-right {
                    padding-top: 0rem !important;
                    padding-bottom: 0rem !important;
                    padding-left: 0rem !important;
                }
                
                .margin-bottom {
                    margin-top: 0rem !important;
                    margin-right: 0rem !important;
                    margin-left: 0rem !important;
                }
                
                .padding-bottom {
                    padding-top: 0rem !important;
                    padding-right: 0rem !important;
                    padding-left: 0rem !important;
                }
                
                .margin-left {
                    margin-top: 0rem !important;
                    margin-right: 0rem !important;
                    margin-bottom: 0rem !important;
                }
                
                .padding-left {
                    padding-top: 0rem !important;
                    padding-right: 0rem !important;
                    padding-bottom: 0rem !important;
                }
                
                .margin-horizontal {
                    margin-top: 0rem !important;
                    margin-bottom: 0rem !important;
                }
                
                .padding-horizontal {
                    padding-top: 0rem !important;
                    padding-bottom: 0rem !important;
                }
                
                .margin-vertical {
                    margin-right: 0rem !important;
                    margin-left: 0rem !important;
                }
                
                .padding-vertical {
                    padding-right: 0rem !important;
                    padding-left: 0rem !important;
                }
                /* Apply "..." at 100% width */
                
                .truncate-width {
                    width: 100%;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                /* Removes native scrollbar */
                
                .no-scrollbar {
                    -ms-overflow-style: none;
                    overflow: -moz-scrollbars-none;
                }
                
                .no-scrollbar::-webkit-scrollbar {
                    display: none;
                }
            </style>
        </div>
        <div class="fonts w-embed">
            <style>
                @import url("https://fonts.googleapis.com/css?family=Aleo:500");
            </style>
            <style>
                @import url("https://fonts.googleapis.com/css?family=Lora:400,500");
            </style>
        </div>
        <div class="color-schemes w-embed">
            <style>
                .color-scheme-1 {}
                
                .color-scheme-2 {
                    --color-scheme-1--text: var(--color-scheme-2--text);
                    --color-scheme-1--background: var(--color-scheme-2--background);
                    --color-scheme-1--foreground: var(--color-scheme-2--foreground);
                    --color-scheme-1--border: var(--color-scheme-2--border);
                    --color-scheme-1--accent: var(--color-scheme-2--accent);
                }
                
                .color-scheme-3 {
                    --color-scheme-1--text: var(--color-scheme-3--text);
                    --color-scheme-1--background: var(--color-scheme-3--background);
                    --color-scheme-1--foreground: var(--color-scheme-3--foreground);
                    --color-scheme-1--border: var(--color-scheme-3--border);
                    --color-scheme-1--accent: var(--color-scheme-3--accent);
                }
                
                .color-scheme-4 {
                    --color-scheme-1--text: var(--color-scheme-4--text);
                    --color-scheme-1--background: var(--color-scheme-4--background);
                    --color-scheme-1--foreground: var(--color-scheme-4--foreground);
                    --color-scheme-1--border: var(--color-scheme-4--border);
                    --color-scheme-1--accent: var(--color-scheme-4--accent);
                }
                
                .color-scheme-5 {
                    --color-scheme-1--text: var(--color-scheme-5--text);
                    --color-scheme-1--background: var(--color-scheme-5--background);
                    --color-scheme-1--foreground: var(--color-scheme-5--foreground);
                    --color-scheme-1--border: var(--color-scheme-5--border);
                    --color-scheme-1--accent: var(--color-scheme-5--accent);
                }
                
                .w-slider-dot {
                    background-color: var(--color-scheme-1--text);
                    opacity: 0.2;
                }
                
                .w-slider-dot.w-active {
                    background-color: var(--color-scheme-1--text);
                    opacity: 1;
                }
                /* Override .w-slider-nav-invert styles */
                
                .w-slider-nav-invert .w-slider-dot {
                    background-color: var(--color-scheme-1--text) !important;
                    opacity: 0.2 !important;
                }
                
                .w-slider-nav-invert .w-slider-dot.w-active {
                    background-color: var(--color-scheme-1--text) !important;
                    opacity: 1 !important;
                }
            </style>
        </div>
    </div>
    <style>
        @media(min-width: 992px) {
            /* Esconde o menu por padrão */
            .navbar6_dropdown-list {
                display: none;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                position: absolute;
                /* garante que o dropdown fique sobreposto */
                top: 100%;
                /* aparece logo abaixo do botão */
                left: 0;
                z-index: 999;
            }
            /* Mostra o menu quando o mouse passa no item */
            .navbar6_menu-dropdown:hover .navbar6_dropdown-list {
                display: block;
                opacity: 1;
                visibility: visible;
            }
            nav {
                padding-left: 100px;
            }
        }
    </style>
    <div fs-scrolldisable-element="smart-nav" data-animation="default" data-collapse="medium" data-duration="400" data-easing="ease" data-easing2="ease" role="banner" class="navbar6_component color-scheme-4 w-nav">
        <div class="navbar6_container">
            <a href="http://localhost/templats-link/public" class="navbar6_logo-link w-nav-brand"><img width="100" sizes="100px" alt="" src="{{ asset('temas/Lumialto/assets/images/LOGO.2-03-1.png') }}" loading="lazy" srcset="images/LOGO.2-03-1-p-500.png 500w, images/LOGO.2-03-1-p-800.png 800w, images/LOGO.2-03-1.png 1080w" class="navbar6_logo"></a>
            <nav role="navigation" class="navbar6_menu w-nav-menu">
                <div class="navbar6_menu-left">
                    <a href="http://localhost/templats-link/public" class="navbar6_link w-nav-link">Home</a>
                    <a href="sobre" class="navbar6_link w-nav-link">Sobre Nós</a>
                    <!--<a href="http://localhost/templats-link/public/contato" class="navbar6_link w-nav-link">Contato</a>-->
                    <div data-delay="300" data-hover="true" class="navbar6_menu-dropdown w-dropdown">
                        <div class="navbar6_dropdown-toggle w-dropdown-toggle">
                            <div>Serviços</div>
                            <div class="dropdown-chevron w-embed"><svg width=" 100%" height=" 100%" viewbox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2.55806 6.29544C2.46043 6.19781 2.46043 6.03952 2.55806 5.94189L3.44195 5.058C3.53958 4.96037 3.69787 4.96037 3.7955 5.058L8.00001 9.26251L12.2045 5.058C12.3021 4.96037 12.4604 4.96037 12.5581 5.058L13.4419 5.94189C13.5396 6.03952 13.5396 6.19781 13.4419 6.29544L8.17678 11.5606C8.07915 11.6582 7.92086 11.6582 7.82323 11.5606L2.55806 6.29544Z" fill="currentColor"></path>
                    </svg></div>
                        </div>
                        <nav class="navbar6_dropdown-list w-dropdown-list">
                            <div class="navbar6_container">
                                <div class="navbar6_dropdown-content">
                                    <div class="navbar6_dropdown-content-left">
                                        <div class="navbar6_dropdown-column">
                                            <div class="margin-bottom margin-xsmall">
                                                <h4 class="text-size-small">Nossos Serviços</h4>
                                            </div>
                                            <div class="navbar6_dropdown-link-list">
                                                <a href="{{ route('tema.Lumialto.martelinho-de-ouro') }}" class="navbar6_dropdown-link w-inline-block">
                                                    <div class="navbar6_icon-wrapper">
                                                        <div class="icon-embed-xsmall w-embed">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 -960 960 960">
                                            <path fill="currentColor" d="M594.04-357v40q0 10.83 7.12 17.92 7.12 7.08 18 7.08 10.64 0 17.76-7.08 7.12-7.09 7.12-17.92v-40h41q14.45 0 24.23-9.49 9.77-9.49 9.77-23.51v-179q0-14.45-9.77-24.22-9.78-9.78-24.23-9.78h-126q-14.24 0-27.12 9.78-12.88 9.77-12.88 24.22v179q0 14.02 12.88 23.51Q544.8-357 559.04-357h35ZM291.2-446h100v64q0 10.83 7.11 17.92 7.12 7.08 18 7.08 10.65 0 17.77-7.08 7.12-7.09 7.12-17.92v-196q0-10.83-7.24-17.92-7.24-7.08-18-7.08t-17.76 7.08q-7 7.09-7 17.92v82h-100v-82q0-10.83-7.24-17.92-7.24-7.08-18-7.08t-17.76 7.08q-7 7.09-7 17.92v196q0 10.83 7.11 17.92 7.12 7.08 17.88 7.08 10.77 0 17.89-7.08 7.12-7.09 7.12-17.92v-64Zm277.84 39v-146h100v146h-100ZM142.15-154.02q-27.6 0-47.86-20.27-20.27-20.26-20.27-47.86v-515.7q0-27.7 20.27-48.03 20.26-20.34 47.86-20.34h675.7q27.7 0 48.03 20.34 20.34 20.33 20.34 48.03v515.7q0 27.6-20.34 47.86-20.33 20.27-48.03 20.27h-675.7Zm0-68.13h675.7v-515.7h-675.7v515.7Zm0 0v-515.7 515.7Z"></path>
                                        </svg>
                                                        </div>
                                                    </div>
                                                    <div class="navbar6_item-right">
                                                        <div class="text-weight-semibold">Martelinho Ouro</div>
                                                        <p class="text-size-small">Remoção de amassados com qualidade</p>
                                                    </div>
                                                </a>
                                                <a href="{{ route('tema.Lumialto.mecanica-geral') }}" class="navbar6_dropdown-link w-inline-block">
                                                    <div class="navbar6_icon-wrapper">
                                                        <div class="icon-embed-xsmall w-embed">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 -960 960 960">
                                            <path fill="currentColor" d="M736.22-664q27.5 0 46.75-19.25T802.22-730q0-27.5-19.25-46.75T736.22-796q-27.74 0-46.87 19.25T670.22-730q0 27.5 19.13 46.75T736.22-664Zm-16.2 84q-6.48 0-10.34-4.05-3.85-4.06-4.61-10.38l-3.29-32.05q-13.28-2.76-21.43-8.28t-14.94-13.04l-26.56 12.76q-5.48 2.47-11.46 1.36-5.98-1.12-9.22-6.36l-16.19-24.92q-3.48-5.47-2.19-11.58 1.29-6.1 6.67-10.09l24.52-17.8q-4.76-10.05-4.76-25.33 0-15.28 4.76-25.57l-24.76-18.04q-5.38-3.99-6.55-9.97-1.17-5.99 2.07-11.7l15.96-24.68q3.23-5.48 9.05-6.6 5.81-1.11 11.86 1.12l27.04 13.24q6.28-7.52 15.57-13.16 9.28-5.64 20.56-8.4l3.29-32.05q.76-6.32 4.61-10.38 3.86-4.05 10.34-4.05h32.15q6.48 0 10.34 4.05 3.86 4.06 4.86 10.38l3.28 32.29q11.05 2.52 20.33 8.16 9.28 5.64 15.8 13.16l26.81-13.24q6.05-2.47 11.86-1.35t9.05 6.59l15.96 24.92q3.24 5.71 2.07 11.7-1.17 5.98-6.55 9.97l-24.76 18.04q4.52 10.29 4.52 25.45t-4.52 25.21l24.52 18.04q5.38 3.99 6.67 10.09 1.29 6.11-2.19 11.58l-16.2 24.92q-3.24 5.47-9.16 6.59-5.91 1.12-11.51-1.35l-26.57-13q-6.78 7.52-14.93 13.04-8.15 5.52-21.2 8.28l-3.28 32.05q-1 6.32-4.86 10.38-3.86 4.05-10.34 4.05h-32.15ZM150-114.02q-15.14 0-25.56-10.42-10.42-10.42-10.42-25.56v-319.76q0-5.38.62-10.65.62-5.26 2.86-10.98l78.83-230.23q6.65-20.21 23.7-32.4 17.06-12.2 37.97-12.2h191.11q14.66 0 24.36 10.11t9.46 24.85q-.23 14.5-9.94 23.96-9.7 9.45-23.88 9.45H259.67L205.63-534h418.59q12.51 0 21.25 8.68 8.75 8.67 8.75 21.5 0 12.82-8.75 21.32-8.74 8.5-21.25 8.5H182.15v207.85h595.7V-464.7q0-14.42 9.87-24.24 9.87-9.82 24.37-9.82 14.5 0 24.31 9.82 9.82 9.82 9.82 24.24V-150q0 15.14-10.54 25.56-10.54 10.42-25.68 10.42h-13.5q-15.57 0-26.27-10.18-10.71-10.18-10.71-25.32v-48.5H200.48v48.5q0 15.14-10.42 25.32t-25.56 10.18H150ZM182.15-474v207.85V-474ZM285.2-315.2q23.33 0 39.66-15.75 16.34-15.75 16.34-38.25 0-23.33-16.27-39.66-16.26-16.34-39.5-16.34-23.23 0-38.73 16.27-15.5 16.26-15.5 39.5 0 23.23 15.75 38.73 15.75 15.5 38.25 15.5Zm388.84 0q23.34 0 39.67-15.75 16.33-15.75 16.33-38.25 0-23.33-16.26-39.66-16.27-16.34-39.5-16.34-23.24 0-38.74 16.27-15.5 16.26-15.5 39.5 0 23.23 15.75 38.73 15.75 15.5 38.25 15.5Z"></path>
                                        </svg>
                                                        </div>
                                                    </div>
                                                    <div class="navbar6_item-right">
                                                        <div class="text-weight-semibold">Mecânica Geral</div>
                                                        <p class="text-size-small">Serviços completos para seu carro</p>
                                                    </div>
                                                </a>
                                                <a href="{{ route('tema.Lumialto.lanternagem-e-pintura') }}" class="navbar6_dropdown-link w-inline-block">
                                                    <div class="navbar6_icon-wrapper">
                                                        <div class="icon-embed-xsmall w-embed">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewbox="0 -960 960 960">
                                        <path fill="currentColor" d="M246.96-315.2q23 0 38.62-15.75 15.62-15.75 15.62-38.25 0-23.33-15.87-39.66-15.87-16.34-38.13-16.34-23.58 0-39.79 16.27-16.21 16.26-16.21 39.5 0 23.23 16.14 38.73 16.15 15.5 39.62 15.5Zm388.98 0q21.67 0 36.57-14.3 14.9-14.3 15.38-35.63 0-6-1.38-12t-3.62-11q-16.31-2.4-30.63-10.18-14.33-7.78-25.8-19.26l-4.05-3.04q-18.8 3.52-30.58 18.04-11.79 14.52-11.79 33.37 0 22.5 16.22 38.25 16.21 15.75 39.68 15.75Zm-493.79 49.05V-474v84.51-1.92 125.26ZM74.02-463.76q0-5.46.62-10.69.62-5.22 2.86-10.94l81.76-246.52q5.48-15.92 18.61-25.11 13.13-9.2 30.13-9.2h143.87q14.42 0 24.24 10.01 9.82 10.02 9.82 24.49 0 14.48-9.82 24.18-9.82 9.69-24.24 9.69h-132.2L165.63-534h344.83q12.51 0 21.25 8.68 8.75 8.67 8.75 21.5 0 12.82-8.75 21.32-8.74 8.5-21.25 8.5H142.15v207.85h595.7v-104.02q0-14.4 9.87-24.35 9.87-9.96 24.35-9.96 14.48 0 24.31 9.96 9.84 9.95 9.84 24.35v213.04q0 17.96-12.7 30.54-12.69 12.57-30.83 12.57-18.02 0-30.59-12.62-12.58-12.63-12.58-30.73v-40.65H160.48v40.89q0 17.96-12.64 30.54-12.65 12.57-30.71 12.57-18.2 0-30.65-12.62-12.46-12.63-12.46-30.73v-306.39ZM600.18-730q12.82 0 21.32-8.68 8.5-8.67 8.5-21.5 0-12.82-8.68-21.32-8.67-8.5-21.5-8.5-12.82 0-21.32 8.68-8.5 8.67-8.5 21.5 0 12.82 8.68 21.32 8.67 8.5 21.5 8.5Zm68.52 270.67L460.61-667.41q-6.74-6.91-10.71-16.55-3.97-9.63-3.97-19.76v-159.37q0-20.69 15.16-35.83 15.15-15.15 35.82-15.15h159.37q10.13 0 19.76 3.97 9.64 3.97 16.55 10.71L900.67-691.3q14.92 14.66 14.92 36.17 0 21.52-14.92 36.43L741.3-459.33q-14.66 14.92-36.17 14.92-21.52 0-36.43-14.92Zm36.3-60.65L840.02-655 649.33-845.93H514.07v135.02L705-519.98Zm-27.96-162.98Z"></path>
                                    </svg>
                                                        </div>
                                                    </div>
                                                    <div class="navbar6_item-right">
                                                        <div class="text-weight-semibold">Lanternagem Pintura</div>
                                                        <p class="text-size-small">Transforme a aparência do seu veículo</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </nav>
                        </div>
                    </div>
                    <div class="navbar6_menu-right">
                        <a href="http://localhost/templats-link/public/contato" class="button is-small w-button">Contato</a>
                    </div>
            </nav>
            <div class="navbar6_menu-button w-nav-button">
                <div class="menu-icon5">
                    <div class="menu-icon1_line-top"></div>
                    <div class="menu-icon1_line-middle">
                        <div class="menu-icon1_line-middle-inner"></div>
                    </div>
                    <div class="menu-icon1_line-bottom"></div>
                </div>
            </div>
            </div>
        </div>