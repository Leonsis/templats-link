@extends('temas.Portfolio.layouts.app')

@section('content')
<!-- Home Section -->
    <section id="home" class="home">
        <div class="home-container">
            <div class="home-content">
                <h1 class="home-title">Olá, eu sou <span class="highlight">Caio</span></h1>
                <p class="home-subtitle">Desenvolvedor Full Stack</p>
                
                <p class="home-description">
                    Transformando suas ideias em soluções digitais 
                    com habilidade profissional que vão destacar seu produto no mercado.
                </p>
                <div class="home-buttons">
                    <a href="public/temas/Portfolio/assets/Curriculo.pdf" target="_blank" class="btn btn-primary">Download CV</a>
                    <a href="#projetos" class="btn btn-secondary">Ver projetos</a>
                </div>
            </div>
            <div class="home-image">
                <div class="profile-card">
                    <div class="profile-image">
                        <img src="{{ asset('temas/Portfolio/assets/images/people.png') }}" alt="Caio" class="profile-photo">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sobre Section -->
    <section id="sobre" class="sobre">
        <div class="container">
            <h2 class="section-title">Sobre Mim</h2>
            <div class="sobre-content">
                <div class="sobre-text">
                    <p>
                        Sou um desenvolvedor apaixonado por tecnologia, com experiência em desenvolvimento <strong>Full Stack</strong>. Minha jornada na programação começou com a curiosidade sobre como as coisas funcionam na web e evoluiu para uma paixão
                        genuína por criar soluções digitais inovadoras.
                    </p>
                    <p> Tenho experiência com diversas tecnologias e busco constantemente aprender novas ferramentas e metodologias para entregar os melhores resultados. Acredito que a tecnologia deve ser acessível e útil para todos.
                    </p>
                    <p>
                        Atuo como Desenvolvedor Full Stack, com experiência na criação de sites e sistemas web. Possuo conhecimentos em <strong>AWS</strong>, <strong>DigitalOcean</strong>, configuração de ambientes em <strong>cloud</strong>, implementação
                        de <strong>SSL</strong>, hospedagem de sites e gerenciamento de <strong>DNS</strong>.
                    </p>
                    <p>
                        Tive a oportunidade de contribuir no desenvolvimento do sistema de portaria eletrônica do
                        <strong>Colégio GISNO de Brasília</strong>, o que me proporcionou aprimorar minhas habilidades técnicas e de trabalho em equipe.
                    </p>
                    <p>
                        Além disso, desenvolvi excelentes habilidades interpessoais e de comunicação durante minha experiência na área de suporte, auxiliando usuários e explicando o funcionamento dos sistemas da empresa de forma clara e eficiente.
                    </p>
                    <div class="sobre-stats">                        
                        <div class="stat">
                            <h3>10+</h3>
                            <p>Projetos concluídos</p>
                        </div>
                        <div class="stat">
                            <h3>10+</h3>
                            <p>Tecnologias dominadas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Experiências Section -->
    <section id="experiencias" class="experiencias">
        <div class="container">
            <h2 class="section-title">Experiências</h2>
            <div class="timeline">
                <!-- Novo bloco -->
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h3>Desenvolvedor Web</h3>
                        <h4>WTR SISTEMES</h4>
                        <span class="timeline-date">Nov de 2025 - Atuando</span>
                        <p>
                            Desenvolvimento, manutenção e supervisão de atividades em sites e sistemas web sob demanda, incluindo a orientação de estagiário, com foco em performance, escalabilidade e usabilidade. Atuação direta na manutenção e evolução de sites e sistemas da Confederação da Agricultura e Pecuária do Brasil (CNA), além da criação de interfaces responsivas e otimizadas para diferentes dispositivos e integração de APIs e serviços de terceiros.
                        </p>
                        <p>
                            Uso contínuo de Git para versionamento, colaboração e controle de mudanças em projetos. Manutenção de sistemas modernos com melhorias contínuas em código, segurança e performance.
                        </p>
                        <div class="competencies">
                            <strong>Competências:</strong> Bootstrap | Javascript | Jquery | PHP | Zend | SQL | Git | Linux | JasperSoftStudio
                        </div>
                    </div>
                </div>
                <!-- Fim do novo bloco -->
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h3>Desenvolvedor Web</h3>
                        <h4>templats-link</h4>
                        <span class="timeline-date">Ago de 2024 - Nov de 2025</span>
                        <p>
                            Desenvolvimento e manutenção de sites e sistemas web sob demanda, com foco em performance, escalabilidade e usabilidade. Criação de interfaces responsivas e otimizadas para diferentes dispositivos. Integração de APIs e serviços de terceiros.
                        </p>
                        <p>
                            Gerenciamento de infraestrutura em AWS EC2: configuração de instâncias, deploys manuais/automáticos, monitoramento e escalabilidade básica. Uso contínuo de Git para versionamento, colaboração e controle de mudanças em projetos. Manutenção de sistemas
                            modernos com melhorias em código, segurança e performance.
                        </p>
                        <div class="competencies">
                            <strong>Competências:</strong> Bootstrap | Javascript | PHP | React | Laravel | SQL | Git | AWS - EC2 | Linux
                        </div>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <h3>Suporte Técnico</h3>
                        <h4>TRIX Tecnologia Inteligente</h4>
                        <span class="timeline-date">Out de 2023 - Ago de 2024</span>
                        <p>
                            Suporte técnico na área de redes e informática hospitalar. Auxílio na manipulação e correção de arquivos XML e HTML. Pesquisas e auxílio no banco de dados do sistema.
                        </p>
                        <p>
                            Atendimento via sistema Helpdesk para clientes. Apoio na configuração e manutenção de redes.
                        </p>
                        <div class="competencies">
                            <strong>Competências:</strong> Redes | XML | HTML | SQL | Suporte Técnico | Helpdesk
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stacks Section -->
    <section id="stacks" class="stacks">
        <div class="container">
            <h2 class="section-title">Tecnologias</h2>
            <div class="stacks-grid">
                <div class="stack-category">
                    <h3>Frontend</h3>
                    <div class="stack-items">
                        <div class="stack-item">
                            <i class="fab fa-js-square"></i>
                            <span>JavaScript</span>
                        </div>
                        <div class="stack-item">
                            <i class="fab fa-react"></i>
                            <span>React.js</span>
                        </div>
                        <div class="stack-item">
                            <i class="fa-solid fa-n"></i>
                            <span>Next.js</span>
                        </div>
                        <div class="stack-item">
                            <i class="fab fa-bootstrap"></i>
                            <span>Bootstrap</span>
                        </div>
                        <div class="stack-item">
                            <i class="fa-brands fa-css3-alt"></i>
                            <span>Tailwind</span>
                        </div>
                        <div class="stack-item">
                            <i class="fa-brands fa-js"></i>
                            <span>Jquery</span>
                        </div>
                    </div>
                </div>
                <div class="stack-category">
                    <h3>Backend</h3>
                    <div class="stack-items">
                        <div class="stack-item">
                            <i class="fab fa-php"></i>
                            <span>PHP</span>
                        </div>
                        <div class="stack-item">
                            <i class="fab fa-laravel"></i>
                            <span>Laravel</span>
                        </div>
                        <div class="stack-item">
                            <i class="fab fa-python"></i>
                            <span>Python</span>
                        </div>
                        <div class="stack-item">
                            <i class="fas fa-database"></i>
                            <span>MySQL</span>
                        </div>
                        <div class="stack-item">
                            <i class="fas fa-database"></i>
                            <span>Oracle SQL</span>
                        </div>
                    </div>
                </div>
                <div class="stack-category">
                    <h3>Infraestrutura</h3>
                    <div class="stack-items">
                        <div class="stack-item">
                            <i class="fab fa-aws"></i>
                            <span>AWS EC2</span>
                        </div>
                        <div class="stack-item">
                            <i class="fab fa-linux"></i>
                            <span>Linux</span>
                        </div>
                        <div class="stack-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>SSL/HTTPS</span>
                        </div>
                        <div class="stack-item">
                            <i class="fas fa-cloud"></i>
                            <span>Cloudflare</span>
                        </div>
                        <div class="stack-item">
                            <i class="fas fa-server"></i>
                            <span>Apache</span>
                        </div>
                    </div>
                </div>
                <div class="stack-category">
                    <h3>Ferramentas & SEO</h3>
                    <div class="stack-items">
                        <div class="stack-item">
                            <i class="fas fa-search"></i>
                            <span>SEO</span>
                        </div>
                        <div class="stack-item">
                            <i class="fas fa-database"></i>
                            <span>phpMyAdmin</span>
                        </div>
                        <div class="stack-item">
                            <i class="fas fa-globe"></i>
                            <span>DNS</span>
                        </div>
                        <div class="stack-item">
                            <i class="fas fa-server"></i>
                            <span>Hospedagens</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Educação Section -->
    <section id="educacao" class="educacao">
        <div class="container">
            <h2 class="section-title">Educação</h2>
            <div class="educacao-grid">
                <div class="educacao-item">
                    <div class="educacao-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="educacao-content">
                        <h3>Análise e Desenvolvimento de Sistemas</h3>
                        <h4>Estácio - Taguatinga, DF</h4>
                        <span class="educacao-date">2023 - 2026</span>
                        <p>
                            Graduação em Análise e Desenvolvimento de Sistemas com foco em desenvolvimento de software, programação, banco de dados e engenharia de software.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cursos Section -->
    <section id="cursos" class="cursos">
        <div class="container">
            <h2 class="section-title">Cursos e Certificações</h2>
            <div class="cursos-grid">
                <div class="curso-item">
                    <div class="curso-header">
                        <h3>Legacy JavaScript Algorithms and Data Structures</h3>
                        <span class="curso-badge">Concluído</span>
                    </div>
                    <p>
                        Certificação em algoritmos e estruturas de dados em JavaScript. Desenvolvimento de habilidades fundamentais em programação, resolução de problemas complexos, otimização de código e implementação de estruturas de dados eficientes para aplicações web modernas.
                    </p>
                    <div class="curso-footer">
                        <span class="curso-platform">freeCodeCamp</span>
                        <span class="curso-date">2025</span>
                    </div>
                </div>

                <div class="curso-item">
                    <div class="curso-header">
                        <h3>Front End Development Libraries V8</h3>
                        <span class="curso-badge">Concluído</span>
                    </div>
                    <p>
                        Certificação em bibliotecas e frameworks front-end essenciais. Desenvolvimento de aplicações modernas com React, Redux, jQuery, Bootstrap e SASS. Criação de interfaces interativas, gerenciamento de estado, componentes reutilizáveis e estilização avançada para aplicações web responsivas e escaláveis.
                    </p>
                    <div class="curso-footer">
                        <span class="curso-platform">freeCodeCamp</span>
                        <span class="curso-date">2025</span>
                    </div>
                </div>
                
                <div class="curso-item">
                    <div class="curso-header">
                        <h3>Curso Web Moderno Completo com JavaScript + Projetos</h3>
                        <span class="curso-badge">Concluído</span>
                    </div>
                    <p>
                        Curso completo de desenvolvimento web moderno cobrindo HTML5, CSS3, JavaScript ES6+, React, Vue, Bootstrap, Node.js, MongoDB, MySQL, PostgreSQL e Angular 9. Desenvolvimento de aplicações reais com foco em Full Stack.
                    </p>
                    <div class="curso-footer">
                        <span class="curso-platform">Desenvolvimento Web</span>
                        <span class="curso-date">2023</span>
                    </div>
                </div>

                <div class="curso-item">
                    <div class="curso-header">
                        <h3>Desenvolvimento Web Avançado com PHP, Laravel e Vue.JS</h3>
                        <span class="curso-badge">Concluído</span>
                    </div>
                    <p>
                        Curso avançado de desenvolvimento web com PHP, Laravel e Vue.js. Configuração de ambiente multi-plataforma, desenvolvimento com Laravel (Blade, Migrations, Eloquent ORM), autenticação JWT, APIs REST, integração com Redis e desenvolvimento de projetos
                        estruturados.
                    </p>
                    <div class="curso-footer">
                        <span class="curso-platform">Desenvolvimento Web</span>
                        <span class="curso-date">2023</span>
                    </div>
                </div>

                <div class="curso-item">
                    <div class="curso-header">
                        <h3>Banco de Dados SQL do Zero ao Avançado + Projetos Reais</h3>
                        <span class="curso-badge">Concluído</span>
                    </div>
                    <p>
                        Curso completo de SQL cobrindo desde conceitos básicos até consultas avançadas. Criação e modelagem de bancos de dados, consultas complexas em múltiplas tabelas, geração de relatórios e interação com bancos de dados existentes.
                    </p>
                    <div class="curso-footer">
                        <span class="curso-platform">Banco de Dados</span>
                        <span class="curso-date">2023</span>
                    </div>
                </div>

                <div class="curso-item">
                    <div class="curso-header">
                        <h3>Git e GitHub do básico ao avançado (c/ gist e GitHub Pages)</h3>
                        <span class="curso-badge">Concluído</span>
                    </div>
                    <p>
                        Curso completo de Git e GitHub para desenvolvedores. Comandos básicos e avançados, gerenciamento de repositórios, fluxo de trabalho profissional (Pull Requests, Code Review), GitHub Pages para portfólio, Gists e documentação com Markdown.
                    </p>
                    <div class="curso-footer">
                        <span class="curso-platform">Controle de Versão</span>
                        <span class="curso-date">2023</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Projetos Section -->
    <section id="projetos" class="projetos">
        <div class="container">
            <h2 class="section-title">Projetos</h2>
            <div class="projetos-grid">
                <div class="projeto-item">
                    <div class="projeto-image">
                        <img src="{{ asset('temas/Portfolio/assets/images/casol.png') }}" alt="Cascol Website" class="projeto-img">
                        <div class="projeto-overlay">
                            <div class="projeto-links">
                                <!--
                                <a href="https://github.com/caio/cascol" class="projeto-link" target="_blank">
                                    <i class="fab fa-github"></i>
                                    Código
                                </a>-->
                                <a href="https://www.cascol.com.br/" class="projeto-link" target="_blank">
                                    <i class="fas fa-external-link-alt"></i> Demo
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="projeto-content">
                        <h3>Cascol - Website Corporativo</h3>
                        <p>
                            Website corporativo desenvolvido em parceria com a agência templats-link. Site responsivo com sistema de localização de postos, blog integrado e design moderno.
                        </p>
                        <div class="projeto-tech">
                            <span class="tech-tag">Laravel 9</span>
                            <span class="tech-tag">PHP 8.1+</span>
                            <span class="tech-tag">MySQL</span>
                            <span class="tech-tag">Bootstrap 5</span>
                            <span class="tech-tag">Blade</span>
                            <span class="tech-tag">API Maps</span>
                        </div>
                    </div>
                </div>

                <div class="projeto-item">
                    <div class="projeto-image">
                        <div class="projeto-overlay">
                            <div class="projeto-links">
                                <a href="https://github.com/Leonsis/templats-link" class="projeto-link" target="_blank">
                                    <i class="fab fa-github"></i> Código
                                </a>
                                <!--
                                <a href="https://github.com/Leonsis/templats-link" class="projeto-link" target="_blank">
                                    <i class="fas fa-external-link-alt"></i>
                                    Demo
                                </a>-->
                            </div>
                        </div>
                    </div>
                    <div class="projeto-content">
                        <h3>Templats-Link - Sistema de Temas Dinâmicos</h3>
                        <p>
                            Sistema Laravel avançado para criação e gestão de sites com temas dinâmicos, painel administrativo completo, sistema de leads integrado e analytics avançado.
                        </p>
                        <div class="projeto-tech">
                            <span class="tech-tag">Laravel 10</span>
                            <span class="tech-tag">PHP 8.1+</span>
                            <span class="tech-tag">MySQL</span>
                            <span class="tech-tag">Bootstrap 5</span>
                            <span class="tech-tag">Blade</span>
                            <span class="tech-tag">GTM/GA4</span>
                        </div>
                    </div>
                </div>

                <div class="projeto-item">
                    <div class="projeto-image">
                        <img src="{{ asset('temas/Portfolio/assets/images/hortencia.png') }}" alt="Hortência Maria Website" class="projeto-img">
                        <div class="projeto-overlay">
                            <div class="projeto-links">
                                <a href="https://psihortenciamaria.com/" class="projeto-link" target="_blank">
                                    <i class="fas fa-external-link-alt"></i> Demo
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="projeto-content">
                        <h3>Hortência Maria - Site Institucional</h3>
                        <p>
                            Site institucional para psicóloga especializada em terapia online para mulheres. Desenvolvido em parceria com a agência templats-link, com sistema de agendamento e blog integrado.
                        </p>
                        <div class="projeto-tech">
                            <span class="tech-tag">Laravel 9</span>
                            <span class="tech-tag">PHP 8.1+</span>
                            <span class="tech-tag">MySQL</span>
                            <span class="tech-tag">Bootstrap 5</span>
                            <span class="tech-tag">Blade</span>
                        </div>
                    </div>
                </div>

                <div class="projeto-item">
                    <div class="projeto-image">
                        <div class="projeto-overlay">
                            <div class="projeto-links">
                                <a href="https://github.com/Leonsis/poupoV2" class="projeto-link" target="_blank">
                                    <i class="fab fa-github"></i> Código
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="projeto-content">
                        <h3>Poupo - Sistema de Gestão Financeira Pessoal</h3>
                        <p>
                            Sistema completo de gestão financeira pessoal desenvolvido com React, Node.js e SQLite, seguindo os princípios da Clean Architecture. Inclui IA integrada para conselhos financeiros.
                        </p>
                        <div class="projeto-tech">
                            <span class="tech-tag">React 18</span>
                            <span class="tech-tag">Node.js</span>
                            <span class="tech-tag">SQLite</span>
                            <span class="tech-tag">Tailwind CSS</span>
                            <span class="tech-tag">JWT</span>
                            <span class="tech-tag">IA Gemini</span>
                        </div>
                    </div>
                </div>

                <div class="projeto-item">
                    <div class="projeto-image">
                        <img src="{{ asset('temas/Portfolio/assets/images/simplus.png') }}" alt="Simplus Contabilidade Website" class="projeto-img">
                        <div class="projeto-overlay">
                            <div class="projeto-links">
                                <a href="https://simpluscontabil.com.br/" class="projeto-link" target="_blank">
                                    <i class="fas fa-external-link-alt"></i> Demo
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="projeto-content">
                        <h3>Simplus - Site Institucional Contabilidade</h3>
                        <p>
                            Site institucional para empresa de contabilidade online. Desenvolvido em parceria com a agência templats-link, com sistema de planos, área do cliente e plataforma de contabilidade digital.
                        </p>
                        <div class="projeto-tech">
                            <span class="tech-tag">PHP 8.1</span>
                            <span class="tech-tag">MySQL</span>
                            <span class="tech-tag">Bootstrap 5</span>
                            <span class="tech-tag">JavaScript</span>
                            <span class="tech-tag">CSS3</span>
                        </div>
                    </div>
                </div>

                <div class="projeto-item">
                    <div class="projeto-image">
                        <div class="projeto-overlay">
                            <div class="projeto-links">
                                <a href="https://github.com/Leonsis/Ferramentas" class="projeto-link" target="_blank">
                                    <i class="fab fa-github"></i> Código
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="projeto-content">
                        <h3>Ferramentas de Automação</h3>
                        <p>
                            Coleção de ferramentas desenvolvidas para automatizar tarefas repetitivas e otimizar o fluxo de trabalho. Inclui scripts PHP e Python para manipulação de HTML, CSS e deploy.
                        </p>
                        <div class="projeto-tech">
                            <span class="tech-tag">PHP</span>
                            <span class="tech-tag">Python</span>
                            <span class="tech-tag">HTML</span>
                            <span class="tech-tag">CSS</span>
                            <span class="tech-tag">cURL</span>
                            <span class="tech-tag">DOM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
