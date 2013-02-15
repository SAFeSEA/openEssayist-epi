<?php namespace openEssayist;
/**
 * Implementation of the openEssayist [light] system
 *
 * This contains all the classes needed for the implementation of the API and
 * front-end of the system.
 *
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 * @version 0.1
 * @package openEssayist
 */

// Load the Epi package and openEssayist files 
include_once "epi/Epi.php";
include_once "controllers/api.class.php";
include_once "controllers/user.class.php";
include_once "controllers/admin.class.php";
include_once "controllers/swagger.class.php";
include_once 'apiclients/APISpellCheck.class.php';
include_once 'apiclients/APIEssayAnalyser.class.php';
include_once "data/constants.class.php";
include_once "data/essay.class.php";

// load all third-party libraries (installed by composer)
require_once 'vendor/autoload.php';

$loader = new \Twig_Loader_Filesystem('./templates');
$twig = new \Twig_Environment($loader, array(
		'cache' => '../.cache',
		'debug'=> true
));


/***************************************************************************************
 * Configure Epi
***************************************************************************************/
\Epi\Epi::setPath('base', './epi');
\Epi\Epi::setPath('view', './views');
\Epi\Epi::setPath('config', './config');

\Epi\Epi::setSetting('debug', true);
\Epi\Epi::setSetting('exceptions', false);

\Epi\Epi::init('api','route','template','debug','session','config');

\Epi\getConfig()->load('default.ini');


/***************************************************************************************
 * Create routes
***************************************************************************************/
\Epi\getRoute()->get('/', array('openEssayist\UserController','Home'));

// Administration routes
\Epi\getRoute()->get('/admin', array('openEssayist\AdminController','AdminPanel'));
\Epi\getRoute()->get('/admin/api', array('openEssayist\AdminController','APIs'));
\Epi\getRoute()->get('/admin/service', array('openEssayist\AdminController','Services'));
\Epi\getRoute()->get('/admin/service/([\w-_]+)', array('openEssayist\AdminController','TestServices'));

// Login/logout routes
\Epi\getRoute()->get('/login', array('openEssayist\UserController','Login'));
\Epi\getRoute()->post('/login', array('openEssayist\UserController','ProcessLogin'));
\Epi\getRoute()->get('/logout', array('openEssayist\UserController','Logout'));

// User, Essay and feedback routes
\Epi\getRoute()->get('/me', array('openEssayist\UserController','Dashboard'));
\Epi\getRoute()->get('/me/task', array('openEssayist\UserController','ListofTasks'));
\Epi\getRoute()->get('/me/task/([\w-_]+)', array('openEssayist\UserController','ListofEssays'));
\Epi\getRoute()->get('/me/task/([\w-_]+)/keyword', array('openEssayist\UserController','KeywordHistory'));
\Epi\getRoute()->get('/me/task/([\w-_]+)/essay/([\w-_]+)', array('openEssayist\UserController','ShowEssay'));
\Epi\getRoute()->get('/me/task/([\w-_]+)/essay/([\w-_]+)/update', array('openEssayist\UserController','UpdateEssay'));
\Epi\getRoute()->get('/me/task/([\w-_]+)/essay/([\w-_]+)/dispersion', array('openEssayist\UserController','ShowDispersion'));
\Epi\getRoute()->get('/me/task/([\w-_]+)/essay/([\w-_]+)/adjacency', array('openEssayist\UserController','ShowGraph'));
\Epi\getRoute()->get('/me/task/([\w-_]+)/submit', array('openEssayist\UserController','SubmitEssay'));
\Epi\getRoute()->post('/me/task/([\w-_]+)/submit', array('openEssayist\UserController','ProcessEssay'));

\Epi\getApi()->post('/me/savedata', array('openEssayist\UserController','setUserData'), \Epi\EpiApi::external);
\Epi\getApi()->get('/me/userdata', array('openEssayist\UserController','getUserData'), \Epi\EpiApi::external);


// API routes
\Epi\getApi()->get('/api.json', array('openEssayist\SwaggerController','APIs'), \Epi\EpiApi::external);

\Epi\getApi()->get('/version.json', array('openEssayist\APIController','Version'), \Epi\EpiApi::external);
\Epi\getApi()->get('/user.json', array('openEssayist\APIController','Users'), \Epi\EpiApi::external);
\Epi\getApi()->get('/user/(\w+).json', array('openEssayist\APIController','UserID'), \Epi\EpiApi::external);
\Epi\getApi()->get('/user/(\w+)/task.json', array('openEssayist\APIController','Tasks'), \Epi\EpiApi::external);
\Epi\getApi()->get('/user/(\w+)/task/(\w+).json', array('openEssayist\APIController','TaskID'), \Epi\EpiApi::external);
\Epi\getApi()->get('/user/(\w+)/task/(\w+)/essay.json', array('openEssayist\APIController','Essays'), \Epi\EpiApi::external);

\Epi\getApi()->get('/user/(\w+)/task/(\w+)/essay/(\w+).json', array('openEssayist\APIController','getEssayID'), \Epi\EpiApi::external);
\Epi\getApi()->put('/user/(\w+)/task/(\w+)/essay/(\w+).json', array('openEssayist\APIController','putEssayID'), \Epi\EpiApi::external);
\Epi\getApi()->post('/user/(\w+)/task/(\w+)/essay/(\w+).json', array('openEssayist\APIController','postEssayID'), \Epi\EpiApi::external);
\Epi\getApi()->delete('/user/(\w+)/task/(\w+)/essay/(\w+).json', array('openEssayist\APIController','deleteEssayID'), \Epi\EpiApi::external);

\Epi\getApi()->get('/user/(\w+)/task/(\w+)/essay/(\w+)/stats.json', array('openEssayist\APIController','Debug'), \Epi\EpiApi::external);
\Epi\getApi()->get('/user/(\w+)/task/(\w+)/essay/(\w+)/feedback.json', array('openEssayist\APIController','EssayFeedback'), \Epi\EpiApi::external);

// catchall route (404)
\Epi\getRoute()->get('.*', 'openEssayist\error404');

\Epi\getRoute()->run();


/**
 * Handler for unknown URLs
*/
function error404() {
	$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
	$code='404';
	$text='Not Found';
	header($protocol . ' ' . $code . ' ' . $text);
	//header("Status: 404 Not Found");
	//http_response_code(404);
	echo "<h1>404 Page Does Not Exist</h1>";
}

