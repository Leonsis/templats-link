<footer>
    <div class="footer-logo">
        @if(\App\Helpers\HeadHelper::getLogoFooter())
              <img src="{{ \App\Helpers\HeadHelper::getLogoFooter() }}" alt="{{ \App\Helpers\HeadHelper::getMetaTitle('global') }}">
          @else
              <img src="{{ asset('temas/teste-tema/assets/img/logo/logo1.png') }}" alt="teste-tema">
          @endif
        <p>{{ \App\Helpers\HeadHelper::getDescricaoFooter() ?: 'We are committed to providing with the highest level of service expertise business and finance if you have any.' }}</p>
        <ul>
            <li><a href="#"><i class="fa-facebook"></i></a></li>
        </ul>
    </div>
    <p>{{ \App\Helpers\HeadHelper::getCopyrightFooter() ?: '© Copyright 2025 - teste-tema. All Right Reserved' }}</p>
</footer>