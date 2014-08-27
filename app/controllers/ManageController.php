<?
	class ManageController extends BaseController {
		
		public function auth() {
			if(Session::has('manage.username')) {
				return Redirect::to('/');
			}
			return View::make('manageAuth');
		}

		public function checkAuth() {
			$data = Input::all();

			$validator = Validator::make($data, array(
				'username'	=> 'required',
				'password'	=> 'required',
				'captcha'	=> 'required|captcha'
			));

			if($validator->fails()) {
				$messages = $validator->messages();

				return Response::json(array('error' => $messages->first()));
			}

			$manager = new Manager;
			$user = $manager->getUser($data['username'], $data['password']);

			if($user->isEmpty()) {
				return Response::json(array('error' => 'Пользователь не найден.'));
			}

			Session::push('manage.username', $user[0]->username);
			Session::push('manage.permissions', $user[0]->permissions);
		}

		public function logout() {
			Session::flush();
			return Redirect::to('/');
		}

		public function removeThread() {
			if(!Session::has('manage.username')) {
				return Response::json(array('error' => 'Вы не авторизованы.'));
			}

			$manager = new Manager;
			$manager->removeThread(Input::get('param'));
		}

		public function removePost() {
			if(!Session::has('manage.username')) {
				return Response::json(array('error' => 'Вы не авторизованы.'));
			}

			$manager = new Manager;
			$manager->removePost(Input::get('param'));
		}

		public function setFix() {
			if(!Session::has('manage.username')) {
				return Response::json(array('error' => 'Вы не авторизованы.'));
			}

			$manager = new Manager;
			$manager->setFix(Input::get('param'));			
		}
	}
?>