<?php namespace openEssayist;

include_once 'controller.php';

/**
 * 
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class UserController implements IController
{
	/**
	 * Handler for the Welcome to openEssayist
	 */
	static public function Home()
	{
		// Check if we're already logged in, although we SHOULD never get here
		//if (\Epi\getSession()->get(Constants::LOGGED_IN) == false)
		//{
		//	\Epi\getRoute()->redirect('/login');
		//}
		$tpllogin = new \Epi\EpiTemplate();
		$ff = $tpllogin->get('welcome-widget.php');

		$template = new \Epi\EpiTemplate();
		$params = array();
		$params['heading'] = 'openEssayist';
		$params['content'] = $ff;

		$template->display('openEssayist-template.php', $params);
	}
	
	/**
	 * Handler for the Dashboard ("me")
	 */
	static public function Dashboard()
	{
		if (\Epi\getSession()->get(Constants::LOGGED_IN) == false)
		{
			\Epi\getRoute()->redirect('/login');
		}
		
		$tpllogin = new \Epi\EpiTemplate();
		$ff = $tpllogin->get('dashboard-widget.php');

		$template = new \Epi\EpiTemplate();
		$params = array();
		$params['heading'] = 'openEssayist';
		$params['content'] = $ff;

		$params['injectJS'] =
				"<script src=\"/bootstrap/flot/jquery.flot.js\"></script>".
				"<script src=\"/bootstrap/flot/jquery.flot.pie.js\"></script>".
				"<script src=\"/bootstrap/flot/jquery.flot.resize.js\"></script>".
				"<script src=\"/bootstrap/dashboard.js\"></script>";
		

		$template->display('openEssayist-template.php', $params);
	}
	
	/**
	 * 
	 */
	static public function Access()
	{
		// Check if we're already logged in, although we SHOULD never get here
		//if (\Epi\getSession()->get(Constants::LOGGED_IN) == false)
		//{
		//	\Epi\getRoute()->redirect('/login');
		//}
		$apiParams =  \Epi\getApi()->invoke('/user/UID.json');
		$apiTask =  \Epi\getApi()->invoke('/user/UID/task.json');
	
		$ff = '<ul class="thumbnails">';
		foreach ($apiTask['tasks'] as $task)
		{
			$tpllogin = new \Epi\EpiTemplate();
			$gg = $tpllogin->get('taskitem-widget.php',$task);
				
			$ff .= $gg;
		}
		$ff .= "</ul>";
	
	
		$template = new \Epi\EpiTemplate();
		$params = array();
		$params['heading'] = 'openEssayist';
		$params['content'] = $ff;
	
		$template->display('openEssayist-template.php', $params);
	}
	
	
	
	static public function ListofEssays($task)
	{
		if (\Epi\getSession()->get(Constants::LOGGED_IN) == false)
		{
			\Epi\getRoute()->redirect('/login');
		}
	
		$tpllogin = new \Epi\EpiTemplate();
		$ff = $tpllogin->get('draftitem.widget.php');
	
		$template = new \Epi\EpiTemplate();
		$params = array();
		$params['heading'] = 'openEssayist';
		$params['content'] = $ff;
	
//		$params['injectJS'] =
	//	"<script src=\"/bootstrap/flot/jquery.flot.js\"></script>".
		//"<script src=\"/bootstrap/flot/jquery.flot.pie.js\"></script>".
		//"<script src=\"/bootstrap/flot/jquery.flot.resize.js\"></script>".
		//"<script src=\"/bootstrap/dashboard.js\"></script>";
	
	
		$template->display('openEssayist-template.php', $params);
	}
	
	
	
	static public function SubmitEssay($task)
	{
		$tplsubmit = new \Epi\EpiTemplate();
		
		$tt["task"] = $task;
		
		$ff = $tplsubmit->get('submit-widget.php',$tt);
		
		$template = new \Epi\EpiTemplate();
		$params = array();
		$params['heading'] = 'openEssayist';
		$params['content'] = $ff;

		$template->display('openEssayist-template.php', $params);
	}
	
	static public function ProcessEssay($task)
	{
		var_dump($_REQUEST);
		//var_dump($_GET);
		//var_dump($_POST);
		//var_dump($_SERVER);
		
		$req = $_POST;
		$text = $req['text'];
		
		$operation = $req['operation'];
		
		// build a list of API tests
		$client = new APIEssayAnalyser();
		
		$ret = Null;
		if ($operation == "Dispersion")
			$ret = $client->getDispersion($text);
		else 
			$ret = $client->getAnalysis($text);
				
		var_dump($ret);

		$temp_dir = sys_get_temp_dir();
		$temp_oa_dir = $temp_dir . "/" . "oETemp";
		if (!is_dir($temp_oa_dir))
		{
			$res = mkdir($temp_oa_dir);
		}
		$file = $temp_oa_dir . "/" . $ret['essayID'] . '.txt';
		
		file_put_contents($file, json_encode($ret));

		

		$ff =  $_REQUEST['__route__'] ;
		$gg = Null;
		
		if ($operation == "Dispersion")
			$gg = str_replace('submit','essay/' . $ret['essayID'] . '/dispersion',$ff);
		else 
			$gg = str_replace('submit','essay/' . $ret['essayID'],$ff);
					

		//var_dump($gg);

		\Epi\getRoute()->redirect($gg);

				
	}

	static public function ShowDispersion($task,$essay)
	{
		//var_dump($_REQUEST);
		//var_dump($essay);
		//var_dump($task);
		//var_dump($_SERVER);
		$file = 'temp\\	' . $essay . '.txt';

		$temp_dir = sys_get_temp_dir();
		$temp_oa_dir = $temp_dir . "/" . "oETemp";
		$file = $temp_oa_dir . "/" . $essay . '.txt';
		

		if (!file_exists($file)) {

			echo "The file $file exists";

			die;

		}
		
		
		$homepage = file_get_contents($file);
		//var_dump($homepage);
		$ret = json_decode($homepage,true);
		//var_dump($ret);
		
		$tpllogin = new \Epi\EpiTemplate();
		$ff = $tpllogin->get('dispersion.widget.php',$ret);
		//var_dump($ff);

		$template = new \Epi\EpiTemplate();
		$params = array();
		$params['heading'] = 'openEssayist';
		//$params['twocolumn'] = 'twocolumn';
		//$params['content'] = str_repeat('Lorem ipsum ', 100);
		//$params['content'] .= print_r($apiParams,true) ;
		
		$params['content'] = $ff ;
		
		//$params['subcontent'] = "<div></div>" ;
		
		//var_dump($params);
		$keyword = json_encode($ret['keyword']);
		
		$markings = '';
		foreach ($ret['count'] as $item)
		{
			$markings .= '{ color: \'lightblue\', lineWidth: 2, xaxis: { from: '. $item .', to: '. $item .' } },';
		}
		$markings = substr_replace($markings ,"",-1);
		

		$points = json_encode($ret['points']);
		//var_dump($points);
		
		$script = <<<XXXXX
		<script type="text/javascript">
		$(function () {
			//alert("dFDFDDF");
			function suffixFormatter(val, axis) {
				toto = $keyword;
		    	if ((val > 0) && (val <= toto.length))
		        	return toto[val-1]
		    	else 
		    		return "";
			}
		    // setup background areas
						
		    var markings = [ $markings ];
		    var data = [
		    	{ label: "", data: $points, points: { symbol: "stick" } }
		    ];

		  	$.plot($("#placeholder"), data, {
		        series: { points: { show: true, radius: 5 } },
		        yaxis: { tickFormatter: suffixFormatter, tickSize: 1 },
		        grid: { markings: markings, hoverable: true },
		        tooltip: true,
		        tooltipOpts: {
		            content: "<div><span>%s</span> - <span>X: %x</span> <span>Y: %y</span></div>",
		            dateFormat: "%y-%0m-%0d %H:%M:%S",
		            shifts: {
		                x: 10,
		                y: 20
		                },
		            defaultTheme: false
		        }
		        
		    });
		});
		</script>		    
XXXXX;
		$params['injectJS'] =
				"<script src=\"/bootstrap/flot/jquery.flot.js\"></script>".
				"<script src=\"/bootstrap/flot/jquery.flot.tooltip.js\"></script>".
				"<script src=\"/bootstrap/flot/jquery.flot.symbol.js\"></script>".
				"<script src=\"/bootstrap/flot/openessayist.flot.symbol.js\"></script>".
				$script;
		
		$template->display('openEssayist-template.php', $params);
		
	}
	
	static public function ShowEssay($task,$essay)
	{
		var_dump($_REQUEST);
		//var_dump($essay);
		//var_dump($task);
		//var_dump($_SERVER);
		
		$temp_dir = sys_get_temp_dir();
		$temp_oa_dir = $temp_dir . "/" . "oETemp";
		$file = $temp_oa_dir . "/" . $essay . '.txt';
		

		if (!file_exists($file)) {

			echo "The file $file exists";

			die;

		}

		

		$homepage = file_get_contents($file);
		//var_dump($homepage);
		$ret = json_decode($homepage,true);
		//var_dump($ret);
		//die;
		
		$ggg = array();
		
		foreach ($ret['ranked'] as $ranking)
		{
			$ggg[] = $ranking[1];
			//$tttt = (array)$text;
			//$tttt['itemid'] = str_pad((int) $inc,4,"0",STR_PAD_LEFT);
			//$nav[]  = $tttt;
			//$nav2[] = $tttt;
			//$inc++;
		}
		//var_dump($ggg);
		$ret['ranking'] = $ggg;
		
		$tpllogin = new \Epi\EpiTemplate();
		$ff = $tpllogin->get('essay.widget.php',$ret);
		
		$template = new \Epi\EpiTemplate();
		$params = array();
		$params['heading'] = 'openEssayist';
		//$params['twocolumn'] = 'twocolumn';
		//$params['content'] = str_repeat('Lorem ipsum ', 100);
		//$params['content'] .= print_r($apiParams,true) ;
		
		$params['content'] = $ff ;
		
		//$params['subcontent'] = "<div></div>" ;
		
		//var_dump($params);
		
		$template->display('openEssayist-template.php', $params);
	}
	
	/**
	 * 
	 */
	static public function Login()
	{
		if (\Epi\getSession()->get(Constants::LOGGED_IN) == true)
		{
			\Epi\getRoute()->redirect('/me');
			die();
		}
		
		$tpllogin = new \Epi\EpiTemplate();
		$ff = $tpllogin->get('login-widget.php');

		$template = new \Epi\EpiTemplate();
		$params = array();
		$params['heading'] = 'openEssayist';
		$params['content'] = $ff;

		$template->display('openEssayist-template.php', $params);

	}

	/**
	 * 
	 */
	static public function ProcessLogin()
	{
		\Epi\getSession()->set(Constants::LOGGED_IN, true);

		\Epi\getRoute()->redirect('/me');
	}

	/**
	 * 
	 */
	static public function Logout()
	{
		// Redirect to the logged in home page
		\Epi\getSession()->set(Constants::LOGGED_IN, false);

		\Epi\getRoute()->redirect('/');
	}


	static public function Dashboard2()
	{
		if (\Epi\getSession()->get(Constants::LOGGED_IN) == false)
		{
			\Epi\getRoute()->redirect('/login');
		}
		
		$apiParams2 =  \Epi\getApi()->invoke('/user/UID.json');
		$apiParams2 =  \Epi\getApi()->invoke('/user/UID/task/UID.json');
		
		$apiParams = (array) \Epi\getApi()->invoke('/user/UID/task/UID/essay/UID.json');
		//$apiParams = json_decode($apiParams2,true);

		$apiParams2 =  \Epi\getApi()->invoke('/user/UID/task/UID/essay/UID/feedback.json');
		
		$fff = (array)$apiParams['text'];
		$ggg = $apiParams2['text'];
		
		
		$nav = array();
		$nav2 = array();
		foreach ($fff as $text)
		{
			$nav[]  = (array)$text;
		}
		$inc = 0;
		foreach ($ggg as $text)
		{
			$tttt = (array)$text;
			$tttt['itemid'] = str_pad((int) $inc,4,"0",STR_PAD_LEFT);
			$nav[]  = $tttt;
			$nav2[] = $tttt;
			$inc++;
		}
		
		//Obtain a list of columns
		foreach ($nav as $key => $row) {
			$volume[$key]  = $row['id'];
			$edition[$key] = $row['type'];
		}
		array_multisort($volume, SORT_ASC, $edition, SORT_ASC, $nav);
		
		//var_dump($nav);
		
		$apiParams['text'] = $nav;
		//$apiParams["user"] = "XXXXXXXXXXXXXXXXX";
		$tpllogin = new \Epi\EpiTemplate();
		$ff = $tpllogin->get('essay-widget.php',$apiParams);
		
		$template = new \Epi\EpiTemplate();
		$params = array();
		$params['heading'] = 'openEssayist';
		$params['twocolumn'] = 'twocolumn';
		//$params['content'] = str_repeat('Lorem ipsum ', 100);
		//$params['content'] .= print_r($apiParams,true) ;		

		$params['content'] = $ff ;
		
		$params['subcontent'] = $nav2 ;

		
		$template->display('openEssayist-template.php', $params);
	}	
}

?>

