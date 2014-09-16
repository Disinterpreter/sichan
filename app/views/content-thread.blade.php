@extends('board')

@section('title')
	sichan :: /{{ $content->board }}/ :: {{ $content->title }}
@endsection

@section('head')
	<script type="text/javascript" src="{{ URL::asset('assets/javascript/jEditor.js') }}"></script>
	<script type="text/javascript">
		$(function() {
			$("textarea[name=textarea]").editor();
		});
	</script>
	@if(Session::has('manage.username'))
		<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/css/manage.css') }}" />
		<script type="text/javascript" src="{{ URL::asset('assets/javascript/manage.js') }}"></script>
	@endif	
@endsection

@section('content')
	<!-- CURRENT THREAD -->
	<div class="thread">
	    <div class="thread_main_content">
	        <div class="thread_title">
	            {{ $content->title }}
	        </div>    
	        <div class="thread_text">
	            <div class="thread_image">
	                <img src='{{ URL::asset("/uploads/$content->image") }}' alt="thread_image" />
	            </div>          
                <div class="wrapper-text">         
	                <div class="thread_description">
	                    <div class="thread_separator">
	                        Anonymous
	                    </div>
	                    <div class="thread_separator">
	                        {{ $content->created_at }}
	                    </div>
	                    <div class="thread_separator post_id" id="thread_id">
	                    	ID: <span>{{ $content->id }}</span>
	                    </div>
	                    <div class="thread_separator post_number">
	                    	<a href="/thread/{{ $content->id }}">#{{ $content->id }}</a>
	                    </div>
	                </div>      
	                <div class="text">                                
	            		{{ $content->text }}
	            	</div>
	            </div>            
	        </div>
	         <!-- CLEARFIX -->
	        <div style="clear: both;"></div>
	        <div class="quote-post" style="margin: 10px 0 0 0; font-size: 12px;">
	            <img src='{{ URL::asset("/assets/images/quote.png") }}' alt="quote" /> Ответить
	        </div>
	    </div>    
	    <div class="posts_body">
	    	@foreach($posts as $key => $val)        
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
		                    <div class="thread_separator timestamp">
		                        {{ $val->created_at }}
		                    </div>
		                    <div class="thread_separator post_id">
		                    	ID: <span>{{ $val->id }}</span>
		                    </div>		                    
		                    <div class="thread_separator post_number">
		                        <a href="/thread/{{ $content->id }}#{{ $key + 1 }}">#{{ $key + 1 }}</a>
		                    </div>
		                </div>
		            </div>    
		            <div class="clearfix">
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
			        </div>
		            <div class="quote-post">
		            	<img src='{{ URL::asset("/assets/images/quote.png") }}' alt="quote" /> Ответить
		            </div>
		             <!-- CLEARFIX -->
		            <div style="clear: both;"></div>
		        </div>
		    @endforeach
	    </div> 
	    <div class="add_post">
	    	<button>Ответить в тред</button>
	    	<form>
	    		<div class="add_post_title">
	    			Ответ в тред
	    		</div>
	    		<textarea rows="10" cols="45" placeholder="Текст" name="textarea"></textarea>
	    		<input type="file" name="file" />
	    		<div class="captcha">
	    			<img id="captcha" src="/captcha" alt="captcha" />
	    			<input type="text" name="captcha" placeholder="Введите текст с картинки" />
	    		</div>
	    		<input type="submit" value="Отправить" />
	    	</form>
	    </div>               
	</div>
@endsection