<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-text">                
                <p>{!! \App\Helpers\NavbarHelper::getCopyrightFooter() !!}</p>
            </div>
            <div class="footer-social">
                @if(\App\Helpers\NavbarHelper::getLinkedin())                    
                    <a href="{{ \App\Helpers\NavbarHelper::getLinkedin() }}" class="social-link" target="_blank">
                        <i class="fab fa-linkedin"></i>
                    </a>
                @endif
                
                @if(\App\Helpers\NavbarHelper::getGithub())                    
                    <a href="{{ \App\Helpers\NavbarHelper::getGithub() }}" class="social-link" target="_blank">
                        <i class="fab fa-github"></i>
                    </a>
                @endif                 
                <a href="https://wa.me/55{{ preg_replace('/[()\s-]+/', '', \App\Helpers\NavbarHelper::getTelefone()) }}" class="social-link" target="_blank"><i class="fab fa-whatsapp"></i></a>
                <a href="mailto:{{ \App\Helpers\NavbarHelper::getEmailContato() }}" class="social-link"><i class="fas fa-envelope"></i></a>
            </div>
        </div>
    </div>
</footer>