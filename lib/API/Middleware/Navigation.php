<?php
namespace API\Middleware;

class Navigation extends \Slim\Middleware{
	private $auth;
	public function __construct($root = '',AuthenticationService $auth){
		$this->auth = $auth;
	}
	public function call(){
		$app = $this->app;
		$auth = $this->auth;
		$req = $app->request();
		$home = array('caption'=>'Home','href'=>'/');
		$home = array('caption'=>'Contacts','href'=>'/contacts');
		$home = array('caption'=>'Admin','href'=>'/admin');
		$home = array('caption'=>'Login','href'=>'/login');
		$home = array('caption'=>'Logout','href'=>'/logout');

		if($auth->hasIdentity(){
			$navigation = array($home,$contacts,$logout);
		} else {
			$navigation = array($home,$contacts,$login);
		}

		if(preg_match($urlPattern, $req->getPathInfo()) && !$auth->hasIdentity()){
			if($req->getPath() !== $config['login.url']){
				$app->redirect($config['login.url']);
			}
		}

		$this->app->hook('slim.before.router',function use (){
			foreach($navigation as &$link){
				if($link['href']==$req->getPath()){
					$link['class'] = 'active';
				} else {
					$link['class'] = '';
				}
			}
			$app->view()->appendData(array('navigation'=>$navigation));
		});
		$this->next->call();

	}
}