@extends('temas.Lumialto.layouts.app')

@section('content')
<div class="utility_component">
    <div class="utility_form-block w-form"><img src="https://d3e54v103j8qbb.cloudfront.net/static/page-not-found.211a85e40c.svg" alt="" class="utility_image">
        <h3>Page Not Found</h3>
        <div class="padding-xxsmall"></div>
        <div>The page you are looking for doesn&#x27;t exist or has been moved</div>
        <div class="padding-xxsmall"></div>
        <a href="{{ route('home') }}" class="button w-button">Go Home</a>
    </div>
</div>
@endsection
