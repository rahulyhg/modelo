<?php
require_once dirname(__FILE__) . '/vendor/autoload.php';
use Slim\Slim;
use API\Application;
use API\Middleware\TokenOverBasicAuth;
use API\Middleware\SwiftMailer;
use Flynsarmy\SlimMonolog\Log\MonologWriter;
// Init application mode

if (empty($_ENV['SLIM_MODE'])) {
    $_ENV['SLIM_MODE'] = (getenv('SLIM_MODE'))? getenv('SLIM_MODE') : 'production';
}

// Init and load configuration
$config = array();
$configFile = dirname(__FILE__) . '/share/config/'. $_ENV['SLIM_MODE'] . '.php';
if (is_readable($configFile)) {
    require_once $configFile;
} else {
    require_once dirname(__FILE__) . '/share/config/default.php';
}
// Create Application


$logger = new \Flynsarmy\SlimMonolog\Log\MonologWriter(array(
    'handlers' => array(
        new \Monolog\Handler\StreamHandler('../logs/'.date('Y-m-d').'.log'),
    ),
));
$app = new \Slim\Slim(array(
    'log.writer' => $logger,
    'mode' => 'production',
));



// Only invoked if mode is "production"
$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'log.enable' => true,
        'log.level' => \Slim\Log::WARN,
        'debug' => false,
    ));
});
// Only invoked if mode is "development"
$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'log.enable' => true,
        'log.level' => \Slim\Log::DEBUG,
        'debug' => true,
    ));
});
// Get log writer
$log = $app->getLog();


#echo "<pre>";
#print_r($config);

try {
    if (!empty($config['db'])) {
        \ORM::configure($config['db']['dsn']);
        if (!empty($config['db']['username']) && !empty($config['db']['password'])) {
            \ORM::configure('username', $config['db']['username']);
            \ORM::configure('password', $config['db']['password']);
        }
    }
    
    \ORM::configure('logging',true);
    \ORM::configure('logger', function($log_string, $query_time) use($log){
        $log->debug($log_string .'-'. $query_time);
    });

    \ORM::configure('error_mode', \PDO::ERRMODE_WARNING);
} catch (\PDOException $e) {
    $log->error($e->getMessage());
}

$app = new Application($config['app']);
#$app->validaEmail(['email'=>'ffjff@jhjhg']);

// Cache Middleware (inner)
#$app->add(new API\Middleware\Cache('/api/v1'));
// Parses JSON body
$app->add(new \Slim\Middleware\ContentTypes());
#$app->add(new \Slim\Middleware\Navigation('/api/v1',$auth));

#$app->add(new \Slim\Middleware\Navigation('/api/v1',$auth));

// Manage Rate Limit
#$app->add(new API\Middleware\RateLimit('/api/v1'));
// JSON Middleware
#$app->add(new API\Middleware\JSON('/api/v1'));
// Auth Middleware (outer)
#$app->add(new API\Middleware\TokenOverBasicAuth(array('root' => '/api/v1')));
###################################################################
###################################################################

/*
*/

/*
$app->hook('slim.before.dispatch', function () use ($app) {
    $app->render('../../assets/templates/header.php');
});
  
$app->hook('slim.after.dispatch', function () use ($app) {
    $app->render('../../assets/templates/footer.php');
});
*/


function jsonpWrap($jsonp) {
    $app = Slim::getInstance();
    if (($jsonCallback = $app->request()->get('jsoncallback')) !== null) {
        $jsonp = sprintf("%s(%s);", $jsonCallback, $jsonp);
        $app->response()->header('Content-type', 'application/javascript');
    }
    return $jsonp;
}
#$app->response()->body(jsonpWrap(json_encode($payload)));
