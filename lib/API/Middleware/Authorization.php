<?php
namespace API\Middleware;

class Authorization extends \Slim\Middleware{
	private $auth;
	private $acl;

	public function __construct(AuthenticationService $auth, Acl $acl){
		$this->auth = $auth;
		$this->acl = $acl;
	}

	public function call(){
		$app = $this->app;
		$auth = $this->auth;
		$acl = $this->acl;
		$role = $this->getRole($auth->getIdentity());
		$isAuthorized = function () use ($app, $auth, $acl, $role) {
			$resource = $app->router->getCurrentRoute()->getPattern();
			$privilege = $app->request->getMethod();
			$hasIdentity = $auth->hasIdentity();
			$isAllowed = $acl->isAllowed($role, $resource, $privilege);
			if ($hasIdentity && !$isAllowed) {
				throw new HttpForbiddenException();
			}
			if (!$hasIdentity && !$isAllowed) {
				return $app->redirect($app->urlFor('login'));
			}
		};
		$app->hook('slim.before.dispatch', $isAuthorized);
		$this->next->call();
	}

	private function getRole($identity = null){
		$role = null;
		if (is_object($identity)) {
			// TODO: check for IdentityInterface (?)
			$role = $identity->getRole();
		}
		if (is_array($identity) && isset($identity['role'])) {
			$role = $identity['role'];
		}
		if (!$role) {
			$role = 'guest';
		}
		return $role;
	}

}