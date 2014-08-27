<?php

class Board extends Eloquent {
	protected $table = 'threads';
	private $threadsPerPage = 10;

	public function getContent($board, $page) {
		$content = Board::where('board', '=', $board)
			->take($this->threadsPerPage)
			->skip($page * $this->threadsPerPage)
			->orderBy('fixed', 'DESC')
			->orderBy('id', 'DESC')
			->get();

		return $content;
	}
	public function getAllRows($board) {
		return Board::where('board', '=', $board)
			->take($this->threadsPerPage * 5)
			->count();
	}
	public function getThread($thread) {
		return Board::where('id', '=', $thread)->get();
	}
	public function getPosts($thread, $type, $timestamp = null) {
		$content = '';
		switch($type) {
			case 0: { // last posts
				$content = DB::select(DB::raw('SELECT * FROM (SELECT * FROM `posts` WHERE `thread` = :thread ORDER BY `id` DESC LIMIT 3) p ORDER BY p.id'), array(
						'thread' => $thread
					)
				);
				break;
			}
			case 1: { // all posts
				$content = DB::table('posts')
					->where('thread', '=', $thread)
					->orderBy('id', 'ASC')
					->get();
				break;
			}
			case 2: { // for long polling
				$content = DB::table('posts')
					->where('thread', $thread)
					->where('created_at', '>', $timestamp)
					->orderBy('id', 'ASC')
					->get();				
			}
		}
		return $content;
	}
	public function threadPostCount($thread) {
		return DB::table('posts')
			->where('thread', $thread)
			->count();
	}
	public function getBoards() {
		return DB::table('boards')->get();
	}
	public function addPost($thread, $text, $upload) {
		DB::table('posts')
			->insert(array(
				'thread' => $thread,
				'text' => $text,
				'image' => $upload
			));
	}
	public function isBoardExist($board) {
		return DB::table('boards')
			->where('board', '=', $board)
			->get();
	}
	public function addThread($board, $text, $image, $title) {
		return Board::insertGetId(array(
			'board' => $board,
			'title' => $title,
			'text' => $text,
			'image' => $image
		));
	}
	public function getLatestThreads() {
		return Board::take(10)
			->orderBy('id', 'DESC')
			->get();
	}
}