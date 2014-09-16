<!DOCTYPE HTML>
<html>
    <head>
        <title>
            @yield('title')
        </title>
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/css/style.css') }}">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script type="text/javascript" src="{{ URL::asset('assets/javascript/jquery-2.1.1.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('assets/javascript/jsMain.js') }}"></script>
        @yield('head')
    </head>
    <body>
        <div id="wrapper">
            <div id="layout">
                <div class="left-sidebar">
                    <div class="tabs">
                        <div class="tab-wrapper" id="expand">
                            <a href="#">
                                <div class="expand"></div>
                            </a>
                        </div> 
                        @if(Session::has('manage.username'))
                            <div class="tab-wrapper">
                                <a href="/manage/logout">
                                    <div class="logout"></div>
                                </a>
                            </div>
                        @endif
                        <div class="tab-wrapper scroll">
                        	<a href="#">
                        		<div class="scroll-down"></div>
                        	</a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-expanded">
                    <div class="sidebar-title">
                        Доски
                    </div>
                    <div class="sidebar-content">
                        @foreach($boards as $value)
                            <a href="/{{ $value->board }}/">/{{ $value->board }}/ — {{ $value->title }}</a>
                        @endforeach    
                    </div>
                </div>
                <!-- HEADER MENU -->
                <div class="board-menu-wrapper">
                    <div class="board-menu">
                        <ul>
                            <li><a href="/" class="active">Главная страница</a></li>
                            <li><a href="/faq">FAQ</a></li>
                            <li><a href="#" id="show_boards">Показать доски</a></li>
                        </ul>
                        <form class="search_wrapper">
                            <input type="text" placeholder="Поиск..." />
                            <div class="input-wrapper">
                                <input type="submit" value="" id="search-button" />
                            </div>    
                        </form>
                    </div>
                </div>            
                <div class="content-main">
                    <!-- yield -->
                    @yield('content')                      
                </div>
            </div>   
        </div>
    </body>
</html>