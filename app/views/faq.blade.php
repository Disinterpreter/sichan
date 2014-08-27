@extends('board')

@section('title')
	sichan :: FAQ
@endsection

@section('content')
	<div class="question-wrapper">
		<div class="question">
			Как сделать спойлер, писать жирным шрифтом и т.д?
		</div>
		<div class="answer">
			<p>%% текст %% — текст под спойлером.</p>
			<p>** текст ** — текст жирным шрифтом.</p>
			<p>* текст * — текст наклонным шрифтом.</p>
			<p>& текст & — зачеркнутый текст.</p>
			<p>> текст — цитата.</p>
			<p>>> номер поста — ответ на пост.</p>
			<p>>> номер треда — ссылка на тред.</p>
		</div>
	</div>
	<div class="question-wrapper">
		<div class="question">
			Что за движок использует sichan?
		</div>
		<div class="answer">
			Это наш самописный движок для имиджборд. Написан за 3 дня на коленке, дорабатывается и, <a href="https://github.com/Exclumice/sichan" target="_blank">имеет открытый исходный код</a>.
		</div>
	</div>
	<div class="question-wrapper">
		<div class="question">
			Я нашел баг в движке, куда мне сообщить?
		</div>
		<div class="answer">
			Напишите нам на почту — support@sichan.in
		</div>
	</div>	
@endsection