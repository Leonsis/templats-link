<nav>
    <div class="logo">
        <a href="{{ route('home') }}">@if(\App\Helpers\HeadHelper::getLogo())
                              <img src="{{ \App\Helpers\HeadHelper::getLogo() }}" alt="{{ \App\Helpers\HeadHelper::getMetaTitle('global') }}">
                          @else
                              <img src="{{ asset('temas/teste-tema/assets/img/logo/logo1.png') }}" alt="teste-tema">
                          @endif</a>
    </div>
</nav>