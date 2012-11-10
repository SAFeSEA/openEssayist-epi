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
include_once "data/constants.class.php";

//require_once "epi/firelogger.php";


/***************************************************************************************
 * Configure Epi
***************************************************************************************/
\Epi\Epi::setPath('base', './epi');
\Epi\Epi::setPath('view', './views');

\Epi\Epi::setSetting('debug', true);
\Epi\Epi::setSetting('exceptions', true);

\Epi\Epi::init('api','route','template','debug','session');


/***************************************************************************************
 * Create routes
***************************************************************************************/
\Epi\getRoute()->get('/', array('openEssayist\UserController','Home'));

\Epi\getRoute()->get('/admin', array('openEssayist\AdminController','Admin'));


\Epi\getRoute()->get('/login', array('openEssayist\UserController','Login'));
\Epi\getRoute()->post('/login', array('openEssayist\UserController','ProcessLogin'));
\Epi\getRoute()->get('/logout', array('openEssayist\UserController','Logout'));
\Epi\getRoute()->get('/user', array('openEssayist\UserController','Dashboard'));
\Epi\getRoute()->get('/me', array('openEssayist\UserController','Access'));
\Epi\getRoute()->get('/user/task/(\w+)', array('openEssayist\UserController','Dashboard'));

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
\Epi\getRoute()->get('.*', 'openEssayist\error404');

\Epi\getRoute()->run();

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
/*
 * ******************************************************************************************
* Define functions and classes which are executed by EpiCode based on the $_['routes'] array
* ******************************************************************************************
*/
class MyClass2
{
	static public function MyMethod()
	{
		$template = new \Epi\EpiTemplate();
		$params = array();
		$params['heading'] = 'This is a heading';
		$params['imageSrc'] = 'https://github.com/images/modules/header/logov3-hover.png';
		$params['content'] = str_repeat('Lorem ipsum ', 100);

		$template->display('sample-template.php', $params);
	}
}

/*
 * This is a sample page which uses native php sessions
* It's easy to switch the session backend by passing a different value to getInstance.
*  For example, EpiSession::getInstance(EpiSession::Memcached);
*/


/*
 * ******************************************************************************************
* Define functions and classes which are executed by EpiRoute
* ******************************************************************************************
*/
class MyClass
{
	static public function MyMethod()
	{
		if(isset($_GET['name']))
			\Epi\getCache()->set('name', $_GET['name']);

		$name = \Epi\getCache()->get('name');
		if(empty($name))
			$name = '[Enter your name]';
		echo '<h1>Hello '. $name . '</h1><p><form><input type="text" size="30" name="name"><br><input type="submit" value="Enter your name"></form></p>';
	}
}


/*
 * ******************************************************************************************
* Define functions and classes which are executed by EpiCode based on the $_['routes'] array
* ******************************************************************************************
*/

function showEndpoints()
{
	echo '<ul>
			<li><a href="/">/</a> -> (home)</li>
			<li><a href="/version">/version</a> -> (print the version of the api)</li>
			<li><a href="/users">/users</a> -> (print each user)</li>
			<li><a href="/users/javascript">/users/javascript</a> -> (make an ajax call to the users.json api)</li>
			<li><a href="/params">/params</a> -> (simulate get/post params to the api call)</li>
			<li><a href="/version.json">/version.json</a> -> (api endpoint for version.json)</li>
			<li><a href="/users.json">/users.json</a> -> (api endpoint for users.json)</li>
			<li><a href="/params-internal-from-external.json">/params-internal-from-external.json</a> -> (private api should be accessible from external)</li>
			<li><a href="/params-internal.json">/params-internal.json</a> -> (private api should not be accessible)</li>
			</ul>';
}

function showUsers()
{
	$users = \Epi\getApi()->invoke('/users.json', \Epi\EpiRoute::httpGet, array('_GET' => array('hello' => 'world')));
	echo '<ul>';
	foreach($users as $user)
	{
		echo "<li>{$user['username']}</li>";
	}
	echo '</ul>';
}

function showUsersJavaScript()
{
	echo <<<MKP
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <a href="/users.json">Click to be alerted of users via ajax</a>
    <script>
      $("a").click(function(ev) {
        var a = ev.target;
        $.get(a.href, {}, function(users) {
          var msg = 'Users are: ';
          for(i in users) {
            msg += users[i].username + " - ";
          }
          alert(msg);
        }, 'json');
        return false;
      });
    </script>
MKP;
}

function showVersion()
{
	echo 'The version of this api is: ' . \Epi\getApi()->invoke('/version.json');
}

function showParams()
{
	$apiParams = \Epi\getApi()->invoke('/params.json', \Epi\EpiRoute::httpGet, array('_GET' => array('caller' => 'api')));

	$apiParams_json = json_encode($apiParams);
	$httpParams = json_encode($_GET);
	echo <<<MKP
    <h3>The _GET params are:</h3>
    <pre>
      {$httpParams}
    </pre>
    <h3>The API params are:</h3>
    <pre>
      {$apiParams_json}
    </pre>
MKP;
}

function apiVersion()
{
	return '1.0';
}

function apiUsers()
{
	return array(
			array('username' => 'jmathai'),
			array('username' => 'stevejobs'),
			array('username' => 'billgates')
	);
}

function apiParams()
{
	return $_GET;
}

function apiParamsFromExternal()
{
	$res = \Epi\getApi()->invoke('/params-internal.json', \Epi\EpiRoute::httpGet);
	return $res;
}
