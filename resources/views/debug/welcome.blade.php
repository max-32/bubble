
@extends('layout/main_bubble')

@section('content')

{{-- Показать ошибки --}}
@if($errors->any())
<p class='text-center alert alert-warning'>
    @foreach ($errors->all() as $error)
        <span>{{ $error }}</span>
    @endforeach
 </p>
@endif

{{-- Показать сообщения --}}
<p class='text-center'>
	@if (Session::has('message'))
	   <span class="alert alert-info">{{ Session::get('message') }}</span>
	@endif
</p>

{{-- Контент --}}
<p class='text-center'>Для продолжения Вам необходимо войти:</p>

<div class='text-center'>
	<div style="max-width:400px; margin:0 auto;">
		<a href="{{ $vk_auth_link }}" class="btn btn-block btn-social btn-vk">
	    	<span style="position:relative; margin:0 12px 0 -14px; padding-right:12px;" class="fa fa-vk"></span>через Vk
	  	</a>
		<a href="{{ $facebook_auth_link }}" class="btn btn-block btn-social btn-facebook">
	    	<span style="position:relative; margin:0 12px 0 -14px; padding-right:12px;" class="fa fa-facebook"></span>через Facebook
	  	</a>
		<a href="{{ $google_auth_link }}" class="btn btn-block btn-social btn-google">
	    	<span style="position:relative; margin:0 12px 0 -14px; padding-right:12px;" class="fa fa-google"></span>через Google
	  	</a>
		<a href="{{ $instagram_auth_link }}" class="btn btn-block btn-social btn-instagram">
	    	<span style="position:relative; margin:0 12px 0 -14px; padding-right:12px;" class="fa fa-instagram"></span>через Instagram
	  	</a>
	</div>
</div>
@endsection
