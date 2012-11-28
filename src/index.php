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

include_once "epi/Epi.php";
include_once "controllers/api.class.php";
include_once "controllers/user.class.php";
include_once "controllers/admin.class.php";
include_once 'apiclients/APISpellCheck.class.php';
include_once 'apiclients/APIEssayAnalyser.class.php';
include_once "data/constants.class.php";

//require_once "epi/firelogger.php";


/***************************************************************************************
 * Configure Epi
***************************************************************************************/
\Epi\Epi::setPath('base', './epi');
\Epi\Epi::setPath('view', './views');

\Epi\Epi::setSetting('debug', true);
\Epi\Epi::setSetting('exceptions', faLse);

\Epi\Epi::init('api','route','template','debug','session');


/***************************************************************************************
 * Create routes
***************************************************************************************/
\Epi\getRoute()->get('/', array('openEssayist\UserController','Home'));

\Epi\getRoute()->get('/admin/api', array('openEssayist\AdminController','APIs'));
\Epi\getRoute()->get('/admin/service', array('openEssayist\AdminController','Services'));



\Epi\getRoute()->get('/login', array('openEssayist\UserController','Login'));
\Epi\getRoute()->post('/login', array('openEssayist\UserController','ProcessLogin'));
\Epi\getRoute()->get('/logout', array('openEssayist\UserController','Logout'));
//\Epi\getRoute()->get('/user', array('openEssayist\UserController','Dashboard'));
\Epi\getRoute()->get('/me', array('openEssayist\UserController','Dashboard'));
\Epi\getRoute()->get('/me/task', array('openEssayist\UserController','Access'));
\Epi\getRoute()->get('/me/task/([\w-_]+)', array('openEssayist\UserController','ListofEssays'));
\Epi\getRoute()->get('/me/task/([\w-_]+)/essay/([\w-_]+)', array('openEssayist\UserController','ShowEssay'));
\Epi\getRoute()->get('/me/task/([\w-_]+)/essay/([\w-_]+)/dispersion', array('openEssayist\UserController','ShowDispersion'));
\Epi\getRoute()->get('/me/task/([\w-_]+)/submit', array('openEssayist\UserController','SubmitEssay'));
\Epi\getRoute()->post('/me/task/([\w-_]+)/submit', array('openEssayist\UserController','ProcessEssay'));

// API routes
\Epi\getApi()->get('/version.json', array('openEssayist\APIController','Debug'), \Epi\EpiApi::external);
\Epi\getApi()->get('/user.json', array('openEssayist\APIController','Users'), \Epi\EpiApi::external);
\Epi\getApi()->get('/user/(\w+).json', array('openEssayist\APIController','UserID'), \Epi\EpiApi::external);
\Epi\getApi()->get('/user/(\w+)/task.json', array('openEssayist\APIController','Tasks'), \Epi\EpiApi::external);
\Epi\getApi()->get('/user/(\w+)/task/(\w+).json', array('openEssayist\APIController','TaskID'), \Epi\EpiApi::external);
\Epi\getApi()->get('/user/(\w+)/task/(\w+)/essay.json', array('openEssayist\APIController','Essays'), \Epi\EpiApi::external);
\Epi\getApi()->get('/user/(\w+)/task/(\w+)/essay/(\w+).json', array('openEssayist\APIController','EssayID'), \Epi\EpiApi::external);
\Epi\getApi()->get('/user/(\w+)/task/(\w+)/essay/(\w+)/stats.json', array('openEssayist\APIController','Debug'), \Epi\EpiApi::external);
\Epi\getApi()->get('/user/(\w+)/task/(\w+)/essay/(\w+)/feedback.json', array('openEssayist\APIController','EssayFeedback'), \Epi\EpiApi::external);

// catchall route (404)
\Epi\getRoute()->get('/test/([\w-_]+)', 'openEssayist\testReg');

\Epi\getRoute()->get('.*', 'openEssayist\error404');

\Epi\getRoute()->run();


function testReg($ffffff) {
	var_dump($ffffff);
	var_dump($route);
	echo "<h1>404 Page Does Not Exist</h1>";
}

/**
 *
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

