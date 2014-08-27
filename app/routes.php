<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::filter('all', function() {
	$boardModel = new Board;
	View::share('boards', $boardModel->getBoards());
});

Route::when('*', 'all');

Route::get('/', function()
{
	$boardModel = new Board;
	return View::make('main-page', array(
		'boards' => $boardModel->getBoards(),
		'threads' => $boardModel->getLatestThreads()
	));
});

Route::get('captcha', function() {
	$captcha = new CaptchaGenerator\Captcha;
	$string = $captcha->generateString(6);
	$img = $captcha->generate($string);

	Session::put('captcha', $string);

	$response = Response::make($img);
	$response->header('Content-Type', 'image/png');
	return $response;
});

Route::get('/faq', function() {
	return View::make('faq');
});

Route::get('/thread/{id}', 'BoardController@getThread')
	->where('id', '[0-9]+');

Route::post('/add_post', 'BoardController@addPost');

Route::post('/add_thread', 'BoardController@addThread');

Route::get('manage', 'ManageController@auth');
Route::post('manage/check_auth', 'ManageController@checkAuth');
Route::get('manage/logout', 'ManageController@logout');
Route::post('manage/remove_thread', 'ManageController@removeThread');
Route::post('manage/remove_post', 'ManageController@removePost');
Route::post('manage/set_fix', 'ManageController@setFix');

Route::get('{board}/{page?}', 'BoardController@getBoard')
	->where(array('board' => '[a-z]+', 'page' => '[0-9]+'));