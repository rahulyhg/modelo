<?php
namespace API\Middleware;
class TokenOverBasicAuth extends \Slim\Middleware{

    protected $settings = array(
        'realm' => 'Protected Area',
        'root'  => '/'
    );

    public function __construct(array $config = array()){
        if (!isset($this->app)) {
            $this->app = \Slim\Slim::getInstance();
        }
        $this->config = array_merge($this->settings, $config);
    }

    public function call(){
        $req = $this->app->request();
        $res = $this->app->response();
        if (preg_match('|^' . $this->config['root'] . '.*|',$req->getResourceUri() )) {
            // We just need the user
            $authToken = $req->headers('PHP_AUTH_USER');
            if (!($authToken && $this->verify($authToken))) {
                $res->status(401);
                $res->header('WWW-Authenticate',sprintf('Basic realm="%s"', $this->config['realm']));
            }
        }
        $this->next->call();
    }

    protected function verify($authToken){
        $user = \ORM::for_table('users')->where('apikey', $authToken)->find_one();
        if (false !== $user) {
            $this->app->user = $user->asArray();
            return true;
        }
        #die('ttttttttttt');
        return false;
    }
}