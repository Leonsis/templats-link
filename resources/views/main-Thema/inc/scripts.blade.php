<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Analytics System - Templats-Link -->
<script src="{{ asset('js/analytics.js') }}"></script>

<!-- Contact Form Analytics -->
@include('analytics.contact-form-script')

<!-- Test Analytics (temporário) -->
@if(config('app.debug'))
{{-- Scripts de teste removidos da produção --}}
{{-- <script src="{{ asset('js/test-analytics.js') }}"></script> --}}
{{-- <script src="{{ asset('js/debug-analytics.js') }}"></script> --}}
@endif

@stack('scripts')
