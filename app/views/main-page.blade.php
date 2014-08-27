@extends('board')

@section('title')
	sichan :: Главная страница
@endsection

@section('content')
	<!-- ALL BOARDS -->
	<div class="main-list">
		<div class="section_name">
			Доски
		</div>
		<div class="boards_list_content">
			<ul>
				@foreach($boards as $value)
					<li>
						<a href="/{{ $value->board }}/">/{{ $value->board }}/ — {{ $value->title }}</a>
					</li>
				@endforeach
			</ul>
		</div>
	</div>
	<!-- LATEST IMAGES -->
	<div class="main-list">
		<div class="section_name">
			Последние загруженные изображения
		</div>
		<div class="boards_list_content latest-images">
			@foreach($threads as $value)
				<div class="post_image">
					<img src='{{ URL::asset("/uploads/$value->image") }}' alt="thread-image" />
				</div>
			@endforeach
		</div>
	</div>
	<!-- LATEST THREADS -->	
	<div class="main-list">
		<div class="section_name">
			Последние созданные треды
		</div>
		<div class="boards_list_content latest-threads">
			<ul>
				@foreach($threads as $value)
					<li>
						<a href="/thread/{{ $value->id }}" target="_blank">{{ $value->title }}</a>
					</li>
				@endforeach
			</ul>
		</div>
	</div>
@endsection