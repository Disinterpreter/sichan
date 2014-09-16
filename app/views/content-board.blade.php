@extends('board')

@section('title')
	sichan :: /{{ $title->board }}/ — {{ $title->title }}
@endsection

@section('head')
	<script type="text/javascript" src="{{ URL::asset('assets/javascript/jEditor.js') }}"></script>
	<script type="text/javascript">
		$(function() {
			$("textarea[name=textarea]").editor({
				insertBefore: $(".big-form > form")
			});
		});
	</script>
	@if(Session::has('manage.username'))
		<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/css/manage.css') }}" />
		<script type="text/javascript" src="{{ URL::asset('assets/javascript/manage.js') }}"></script>
	@endif	
@endsection

@section('content')
	<!-- CREATE THREAD -->
	<div class="create_thread">
	    <button>Создать тред</button>
	</div>
	<!-- CLEARFIX -->
	<div style="clear: both;"></div>
	<!-- CREATE THREAD -->
	<div class="big-form-wrapper" id="create-thread">
		<div class="big-form">
			<div class="big-form-title">
				Создание треда
			</div>
			<form>
				<div class="form_input">
					<label>Название треда:</label>
					<input type="text" placeholder="Название треда" name="title" />
				</div>
				<div class="form_input">
					<label>Текст:</label> 
					<textarea placeholder="Текст..." rows="10" cols="40" name="textarea"></textarea>
				</div>
				<div class="form_input">
					<label>Картинка:</label> 
					<input type="file" name="file" />
				</div>
				<div class="form_input">
					<label>Защитный код:</label>
					<div class="captcha-wrapper">
						<img id="captcha" src="/captcha" alt="captcha" />
					</div>
				</div>
				<div class="form_input">
					<label>Введите код:</label>
					<input type="text" placeholder="Защитный код" name="captcha" />
				</div>
				<input type="submit" value="Отправить" />
			</form>
		</div>
	</div>
	<!-- THREADS -->
	@foreach($threads as $value)
		<div class="thread">
		    <div class="thread_main_content">
		        @if(Session::has('manage.username'))
		        	<div class="manage_options">
		        		<div class="remove_thread" data-id="{{ $value->id }}">
		        			<img src="{{ URL::asset('assets/images/remove.png') }}" alt="thread_remove" />
		        		</div>
		        		<div class="fix_thread" data-id="{{ $value->id }}">
		        			<img src="{{ URL::asset('assets/images/fix.png') }}" alt="fix_thread" />
		        		</div>		        	
		        	</div>
		        @endif		    
		        <div class="thread_title">
		        	@if($value->fixed)
		        		<img src="{{ URL::asset('assets/images/fix.png') }}" alt="thread_fixed" />
		        	@endif
		            <a href="/thread/{{ $value->id }}">{{ $value->title }}</a>
		        </div>    
		        <div class="thread_text">
		            <div class="thread_image">
		                <img src='{{ URL::asset("/uploads/$value->image") }}' alt="thread_image" />
		            </div>          
	                <div class="wrapper-text"> 
		                <div class="thread_description">
		                    <div class="thread_separator">
		                        Anonymous
		                    </div>
		                    <div class="thread_separator">
		                        {{ $value->created_at }}
		                    </div>
		                    <div class="thread_separator">
		                    	Ответов: {{ $count[$value->id] }}
		                    </div>	                    
		                    <div class="thread_separator post_number">
		                        <a href="/thread/{{ $value->id }}">#{{ $value->id }}</a>
		                    </div>                   
		                </div> 	 
		                <div class="text">                                            
		            		{{ $value->text }}
		            	</div>
		            </div>
		        </div>
		         <!-- CLEARFIX -->
		        <div style="clear: both;"></div>
		    </div>
		    <div class="posts_body">
		    	@foreach($posts[$value->id] as $key => $val)        
			        <div class="post">
				        @if(Session::has('manage.username'))
				        	<div class="manage_options">
				        		<div class="remove_post" data-id="{{ $val->id }}">
				        			<img src="{{ URL::asset('assets/images/remove-post.png') }}" alt="post_remove" />
				        		</div>
				        	</div>
				        @endif
			            <div class="post-description-wrapper">
			                <div class="post_description">
			                    <div class="thread_separator">
			                        Anonymous
			                    </div>
			                    <div class="thread_separator">
			                        {{ $val->created_at }}
			                    </div>				                    
			                    <div class="thread_separator post_number">
			                        <a href="/thread/{{ $value->id }}#{{ $count[$value->id] - (sizeof($posts[$value->id]) - $key) + 1 }}">#{{ $count[$value->id] - (sizeof($posts[$value->id]) - $key) + 1 }}</a>
			                    </div>
			                </div>
			            </div>    
			            <div class="post_content">
			            	@if (!empty($val->image))
				                <div class="post_image">
				                    <img src='{{ URL::asset("/uploads/$val->image") }}' alt="post-image" />
				                </div>
				            @endif     
				            <div class="wrapper-text">                            
			                	{{ $val->text }}
			                </div>	
			            </div>
			             <!-- CLEARFIX -->
			            <div style="clear: both;"></div>
			        </div>
			    @endforeach
		    </div>                
		</div>
	@endforeach
	<footer>
	    <!-- PAGINATION BAR -->
	    <div class="pagination-bar">
	        <ul>
	        	@for($i = 0; $i < $pages; $i++)
	            	<li><a href="/{{ $board }}/{{ $i }}">{{ $i + 1 }}</a></li>
	            @endfor
	        </ul>
	    </div>
	</footer>
@endsection