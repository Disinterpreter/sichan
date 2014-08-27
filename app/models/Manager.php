<?php
	class Manager extends Eloquent {
		protected $table = 'users';

		public function createBoard($board, $title) {
			DB::table('boards')->insert(array(
				'board' => $board,
				'title' => $title
			));
		}

		public function removeThread($thread) {
			DB::delete(DB::raw('DELETE `threads`, `posts` FROM `threads` LEFT JOIN `posts` ON posts.thread = threads.id WHERE threads.id = :id'), array(
				'id' => $thread
			));
		}

		public function removePost($post) {
			DB::table('posts')
				->where('id', $post)
				->delete();
		}

		public function getUser($username, $password) {
			return Manager::where(array(
				'username' => $username,
				'password' => md5($password)
			))->get();
		}

		public function setFix($thread) {
			DB::update(DB::raw('UPDATE `threads` SET `fixed` = IF(`fixed` > 0, 0, 1) WHERE `id` = :id'), array(
				'id' => $thread
			));
		}
	}