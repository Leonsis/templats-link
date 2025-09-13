<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                @if(\App\Helpers\HeadHelper::getLogoFooter())
                    <div class="footer-logo mb-3">
                        <img src="{{ \App\Helpers\HeadHelper::getLogoFooter() }}" 
                             alt="Logo Footer" 
                             class="footer-logo-img" 
                             style="max-height: 60px; max-width: 200px;">
                    </div>
                @else
                    <h5><i class="fas fa-link me-2"></i>Templats-link</h5>
                @endif
                
                @if(\App\Helpers\HeadHelper::getDescricaoFooter())
                    <p class="mb-0">{{ \App\Helpers\HeadHelper::getDescricaoFooter() }}</p>
                @else
                    <p class="mb-0">Sua plataforma de templates e soluções web.</p>
                @endif
            </div>
            <div class="col-md-6 text-md-end">
                <div class="social-links">
                    @if(\App\Helpers\HeadHelper::getFacebook())
                        <a href="{{ \App\Helpers\HeadHelper::getFacebook() }}" target="_blank" class="text-white me-3" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    @endif
                    @if(\App\Helpers\HeadHelper::getTwitter())
                        <a href="{{ \App\Helpers\HeadHelper::getTwitter() }}" target="_blank" class="text-white me-3" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                    @if(\App\Helpers\HeadHelper::getInstagram())
                        <a href="{{ \App\Helpers\HeadHelper::getInstagram() }}" target="_blank" class="text-white me-3" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    @endif
                    @if(\App\Helpers\HeadHelper::getLinkedin())
                        <a href="{{ \App\Helpers\HeadHelper::getLinkedin() }}" target="_blank" class="text-white me-3" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    @endif
                    @if(\App\Helpers\HeadHelper::getYoutube())
                        <a href="{{ \App\Helpers\HeadHelper::getYoutube() }}" target="_blank" class="text-white" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    @endif
                </div>
                <p class="mt-3 mb-0">{{ \App\Helpers\HeadHelper::getCopyrightFooter() }}</p>
            </div>
        </div>
    </div>
</footer>
