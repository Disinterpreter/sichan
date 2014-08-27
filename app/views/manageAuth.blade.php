@extends('board')

@section('title')
	sichan :: управление
@endsection

@section('head')
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/css/manage.css') }}" />
	<script type="text/javascript" src="{{ URL::asset('assets/javascript/manage.js') }}"></script>
@endsection

@section('content')
	<div class="big-form-wrapper" style="display: block;">
		<div class="big-form" style="min-width: 403px;">
			<div class="big-form-title">
				Авторизация
			</div>
			<form id="auth">
				<div class="form_input">
					<label>Пользователь:</label>
					<input type="text" placeholder="Пользователь" name="username" />
				</div>
				<div class="form_input">
					<label>Пароль:</label> 
					<input type="password" placeholder="Пароль" name="password" />
				</div>
				<div class="form_input">
					<label>Защитный код:</label>
					<img id="captcha" src="/captcha" alt="captcha" />
				</div>
				<div class="form_input">
					<label>Введите код:</label>
					<input type="text" placeholder="Защитный код" name="captcha" />
				</div>
				<input type="submit" value="Отправить" />
			</form>
		</div>
	</div>
@endsection