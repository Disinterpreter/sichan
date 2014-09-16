<?php

class BoardController extends BaseController {
	private $threadsPerPage = 10;
	private $boards = null;

	public function __construct() {
		$boardModel = new Board;
		$this->boards = $boardModel->getBoards();
	}

	public function getBoard($board, $page = 0)
	{
		$boardModel = new Board;
		$content = $boardModel->getContent($board, $page);

		$boardExist = $boardModel->isBoardExist($board);

		if(!$boardExist) {
			return App::abort(404);
		}

		$posts = array();
		$count = array();

		foreach($content as $value) {
			$posts[$value->id] = $boardModel->getPosts($value->id, 0);
			$count[$value->id] = $boardModel->threadPostCount($value->id);
		}

		$rows = $boardModel->getAllRows($board);

		return View::make('content-board', array(
			'board' 	=> $board, 
			'threads' 	=> $content, 
			'posts' 	=> $posts, 
			'count' 	=> $count, 
			'pages' 	=> $rows >= $this->threadsPerPage ? $rows / $this->threadsPerPage : 1,
			'title'		=> $this->getCurrentBoard($board)
		));
	}

	public function getThread($thread) {
		$boardModel = new Board;
		$content = $boardModel->getThread($thread);
		$posts = $boardModel->getPosts($thread, 1);

		if($content->isEmpty()) {
			return Redirect::to('/');
		}
		
		return View::make('content-thread', array('content' => $content[0], 'posts' => $posts));
	}

	public function addPost() {
		Input::merge(array_map(function($content) {
			$content = trim($content);
			$content = html_entity_decode($content);
			return $content;
		}, Input::all()));

		$data = Input::all();
		
		$messages = array(
			'textarea.required' => 'Заполните поле с текстом.',
			'textarea.min'		=> 'Минимальное количество символов в тексте — 4',
			'file.image'		=> 'Файл должен быть картинкой.',
			'file.max'			=> 'Максимальный размер файла: 500 кб.',
			'captcha.required'	=> 'Вы не ввели защитный код.',
			'captcha.captcha'	=> 'Защитный код указан неверно.'
		);

		$validator = Validator::make($data, array(
			'textarea' => 'required|min:4',
			'file' => 'image|max:500',
			'captcha' => 'required|captcha'
		), $messages);

		if(!empty($data['file'])) {
			$size = getImageSize($data['file']);

			if($size[0] < 100 || $size[1] < 100) {
				return Response::json(array('error' => 'Минимальный размер изображения 100х100 пикселей.'));
			}
		}	

		if($validator->fails()) {
			$messages = $validator->messages();

			return Response::json(array('error' => $messages->first()));
		}
		else {
			$upload = '';

			if(!empty($data['file'])) {
				$upload = BoardController::generateName().'.'.$data['file']->getClientOriginalExtension();

				$data['file']->move(public_path().'/uploads/', $upload);
			}

			$boardModel = new Board;
			$boardModel->addPost($data['thread'], $this->specialChars(nl2br(strip_tags($data['textarea']))), $upload);
		}
	}

	public function addThread() {
		Input::merge(array_map(function($content) {
			$content = trim($content);
			$content = html_entity_decode($content);
			return $content;
		}, Input::all()));

		$data = Input::all();

		$messages = array(
			'title.required'	=> 'Укажите заголовок треда.',
			'title.min'			=> 'Название треда должно содержать минимум 4 символа.',
			'title.max'			=> 'Название треда должно содержать максимум 32 символа.',
			'textarea.required'	=> 'Заполните поле с текстом.',
			'textarea.min'		=> 'Минимальное количество символов в тексте — 10.',
			'file.required'		=> 'Загрузите картинку.',
			'file.image'		=> 'Файл должен быть картинкой',
			'file.max'			=> 'Размер файла не должен превышать 500 кб.',
			'captcha.required'	=> 'Введите защитный код с картинки.',
			'captcha.captcha'	=> 'Защитный код указан неверно.'
		);

		$validator = Validator::make($data, array(
			'title' => 'required|min:4|max:32',			
			'textarea' => 'required|min:10',
			'file' => 'required|image|max:500',
			'captcha' => 'required|captcha'
		), $messages);

		if($validator->fails()) {
			$messages = $validator->messages();

			return Response::json(array('error' => $messages->first()));		
		}
		else {
			$size = getImageSize($data['file']);

			if($size[0] < 100 || $size[1] < 100) {
				return Response::json(array('error' => 'Минимальный размер изображения 100х100 пикселей.'));
			}

			$upload = BoardController::generateName().'.'.$data['file']->getClientOriginalExtension();

			$data['file']->move(public_path().'/uploads/', $upload);

			$boardModel = new Board;
			return Response::json(array(
				'link' => $boardModel->addThread(
					$data['board'], 
					$this->specialChars(nl2br(strip_tags($data['textarea']))),
					$upload, 
					strip_tags($data['title'])
				)
			));
		}
	}

	private static function generateName() {
		$abc = 'abcdefghijklmnopqrstuvwxyz1234567890';
		$res = '';

		for($i = 0; $i < 15; $i++) {
			$str = $abc[rand(0, strlen($abc) - 1)];
			$res .= rand(0, 1) ? $str : strtoupper($str);
		}
		return $res;
	}

	private function getCurrentBoard($board) {
		foreach($this->boards as $value) {
			if($value->board == $board) {
				return $value;
			}
		}
		return false;
	}

	private function specialChars($text) {
		$arr = array(
			'/\%\%(.*?)\%\%/' 								=> '<span class="spoiler">$1</span>',
			'/\*\*(.*?)\*\*/' 								=> '<b>$1</b>',
			'/\*(.*?)\*/'									=> '<i>$1</i>',
			//'/(?<!>)\>\s?([^>\n]+)/'						=> '<span class="quote">$1</span>',
			'/((https?:\/\/|(www\.))((.*)\.([^\s|\<]*)))/'	=> '<a href="//$4" target="_blank">$1</a>',
			'/(\>\>\s?([0-9]*))/'							=> '<a href="#" class="answer">$1</a>',
			'/\&(.*?)\&/'									=> '<strike>$1</strike>'
		);

		return preg_replace(array_keys($arr), array_values($arr), $text);
	}

}