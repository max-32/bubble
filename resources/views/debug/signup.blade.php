
@extends('layout/main_bubble')

{{-- Add css to head --}}
@push('css-head')
<link href="assets/css/login_register.css" rel="stylesheet">
<style type="text/css">
  body {
    background: inherit;
  }
  .navbar {
    margin-bottom: 0px !important;
  }
  .page-content {
    margin-top: 70px !important;
  }
</style>
@endpush

{{-- Add js to bottom --}}
@push('js-bottom')
<script type="text/javascript">
  $(function() {
    // Animate login box when document is ready
    $('.card-group.animated').fadeIn(700);
  });
</script>
@endpush

{{-- Main Content --}}
@section('content')

<div class="parallax filter-black">
<div class="parallax-image"></div>
<div class="small-info">

<div class="col-sm-10 col-sm-push-1 col-md-6 col-md-push-3 col-lg-6 col-lg-push-3">
  <div class="card-group animated" style="display:none;">
    <div class="card">
      <div class="card-block">
      <div class="center">
        <h4 class="m-b-0">
          <span class="icon-text">
            <a href="https://ru.wikipedia.org/wiki/OAuth" target="_blank" title="Как это работает?">OAuth 2.0</a>
            <span style="color:#666;">Авторизация</span>
          </span>
        </h4>
        <p class="text-muted">Войдите, используя одну из соц. сетей:</p>
      </div>

      <!-- Login errors box -->
      <div class="login-errors-box">
        {{-- Показать ошибки --}}
        @if($errors->any())
            @foreach ($errors->all() as $error)
                <p style="color:#e46f61;"><b>{{ $error }}</b></p>
            @endforeach
        @endif

        {{-- Показать ошибки --}}
        @if (Session::has('error'))
           <p style="color:#e46f61;"><b>{{ Session::get('error') }}</b></p>
        @endif

        {{-- Показать сообщения --}}
        @if (Session::has('message'))
           <p style="color:#a0d468;"><b>{{ Session::get('message') }}</b></p>
        @endif
      </div>
      <!-- End login errors box -->

      <!-- Cocial buttons box -->
      <div class="cocial-buttons-box">
        <a href="{{ $vk_auth_link }}" class="btn btn-block btn-social btn-vk vk-btn social-buttons">
            <span class="fa fa-vk"></span>Вконтакте
        </a>
        <a href="{{ $facebook_auth_link }}" class="btn btn-block btn-social btn-facebook facebook-btn social-buttons">
            <span class="fa fa-facebook"></span>Facebook
        </a>
        <a href="{{ $google_auth_link }}" class="btn btn-block btn-social btn-google google-btn social-buttons">
            <span  class="fa fa-google"></span>Google
        </a>
        <a href="{{ $instagram_auth_link }}" class="btn btn-block btn-social btn-instagram instagram-btn social-buttons">
            <span class="fa fa-instagram"></span>Instagram
        </a>
      </div>
      <!-- End social buttons box -->

      </div>
    </div>
  </div>
</div>

</div>
</div>
@endsection