<?php namespace openEssayist;

include_once 'controller.php';

/**
 * 
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class UserController extends IController {
	/**
	 * Handler for the Welcome to openEssayist
	 * @route: /
	 */
	static public function Home()
	{
		$widget = \Epi\getTemplate()->get('welcome-widget.php');
				
		$params = array(
				'heading' => 'Welcome',
				'content' => $widget
				);
		IController::showTemplate('openEssayist-template.php', $params);
	}

	/**
	 * Handler for the Dashboard
	 * @route: /me
	 */
	static public function Dashboard()
	{
		if (!self::isUser()) \Epi\getRoute()->redirect('/login');

		$widget = \Epi\getTemplate()->get('dashboard-widget.php');

		$params = array(
				'heading' => 'Dashboard',
				'content' => $widget
				);
		
		$params['injectJS'] = 
			"<script src=\"/bootstrap/flot/jquery.flot.js\"></script>" . 
			"<script src=\"/bootstrap/flot/jquery.flot.pie.js\"></script>" . 
			"<script src=\"/bootstrap/flot/jquery.flot.resize.js\"></script>" . 
			"<script src=\"/bootstrap/dashboard.js\"></script>";

		IController::showTemplate('openEssayist-template.php', $params);
	}

	/**
	 * Handler for the list of assignment
	 * @route: /me/task
	 */
	static public function ListofTasks()
	{
		if (!self::isUser()) \Epi\getRoute()->redirect('/login');
		
		//$apiParams = \Epi\getApi()->invoke('/user/UID.json');
		$apiTask = \Epi\getApi()->invoke('/user/UID/task.json');

		foreach ($apiTask['tasks'] as $task) {
			$widget = \Epi\getTemplate()->get('taskitem-widget.php', $task);
			$content .= $widget;
		}

		$params = array(
				'heading' => 'Assignments',
				'content' => "<ul class=\"thumbnails\">$content</ul>"
				);
		IController::showTemplate('openEssayist-template.php', $params);
	}


	static public function ListofEssays($task)
	{
		if (!self::isUser()) \Epi\getRoute()->redirect('/login');
		
		//var_dump($_REQUEST);
		//var_dump($_GET);
		//var_dump($_POST);
		//var_dump($_SERVER);
		
		//$apiUser = \Epi\getApi()->invoke('/user/UID.json', \Epi\EpiRoute::httpGet, array('params' => 'stats'));
		$apiUser = \Epi\getApi()->invoke('/user/UID.json');
		$apiTask = \Epi\getApi()->invoke('/user/UID/task/' . $task . '.json');

		$keywords = array();
		$inc = 0;
		foreach ($apiTask['essays'] as $item) {
			$apurl = '/user/UID/task/' . $task . '/essay/' . $item['ref'] . '.json';
			$apiEssay = \Epi\getApi()->invoke($apurl);

			//var_dump($apiEssay['stats']);
			$apiTask['essays'][$inc]['stats'] = $apiEssay['stats'];
			$apiTask['essays'][$inc]['metrics'] = $apiEssay['metric'];
			$apiTask['essays'][$inc]['kwords'] = array_slice($apiEssay['bigrams'], 0, 5);
			//var_dump($apiEssay['metric']);
			$inc++;

		}

		$widget = \Epi\getTemplate()->get('draftitem.widget.php', $apiTask);
		
		$params = array();
		$params['heading'] = 'Essays';
		$params['content'] = $widget;

		$breadcrumb = \Epi\getTemplate()->get('breadcrumb.widget.php', array('task' => $task));
		$params['breadcrumb'] = $breadcrumb;

		if (false && \Epi\Epi::getSetting('debug') && $inc >= 2)
		{	
			///// CRUDE: check difference between 2 sets of keywords

			$ar1 = $apiTask['essays'][0]['kwords']; //array(0 => array("a" => "green"), "b" => "brown", "c" => "blue", "red");
			$ar2 = $apiTask['essays'][1]['kwords'];

			$func = function($v) {
				$elt = $v['ngram'];
				return "".join(" ",$elt);
			};

			$names1 = array_map($func, $ar1);
			$names2 = array_map($func, $ar2);
			var_dump($names1);
			var_dump($names2);

			$result = array_diff($names1, $names2);
			var_dump($result);
		}

		IController::showTemplate('openEssayist-template.php', $params);
	}

	static public function SubmitEssay($task)
	{
		if (!self::isUser()) \Epi\getRoute()->redirect('/login');

		//var_dump($_REQUEST);
		//var_dump($_POST);
		//var_dump($_GET);
		//var_dump($_SERVER);

		$params = \Epi\getSession()->get('errors');
		if ($params) {
			//var_dump($params);		
			\Epi\getSession()->set('errors', Null);
			$tt["error"] = $params['error'];
			$tt["text"] = $params['text'];
		}
		
	

		// retrieve info on last essay submission
		$apiTask = \Epi\getApi()->invoke('/user/UID/task/' . $task . '.json');
		$last = end($apiTask['essays']);
		$version = str_pad((int) intval($last['ref'])+1, 4, "0", STR_PAD_LEFT);

		// generate submission form
		$ff = \Epi\getTemplate()->get('submit-widget.php', array(
				'task' => $task,
				'version' => $version
			));

		$bc = \Epi\getTemplate()->get('breadcrumb.widget.php', array(
				'task' => $task
			));

		$params = array();
		$params['heading'] = 'Submit Draft';
		$params['breadcrumb'] = $bc;
		$params['content'] = $ff;

		IController::showTemplate('openEssayist-template.php', $params);
	}

	static public function UpdateEssay($task, $essay)
	{
		if (!self::isUser()) \Epi\getRoute()->redirect('/login');
		
		$apurl = '/user/UID/task/' . $task . '/essay/' . $essay . '.json';
		$apiTask = \Epi\getApi()->invoke($apurl);
		
		$params = array();
		$params['heading'] = 'Submit Draft';
		$params['breadcrumb'] = $bc;
		$params['content'] = $ff;
		
		/** @var Essay */
		$basket = new Essay($apiTask);
		
		$textarr = $basket->getFullText();
		
		$apiTask = \Epi\getApi()->invoke($apurl,\Epi\EpiRoute::httpPut,array(
				'text' => $textarr
		));
		$apiTask = \Epi\getApi()->invoke($apurl,\Epi\EpiRoute::httpPost,array(
				'text' => $textarr
		));
		
		//var_dump($textarr);
		
		IController::showTemplate('openEssayist-template.php', $params);
	}
	
	static public function ProcessEssay($task) {

		if ("Cancel" === $_POST['action']) {
			$ff = $_REQUEST['__route__'];
			$ff = str_replace('/submit', '', $ff);
			\Epi\getRoute()->redirect($ff);
			die();
		}
		
		if (!self::isUser()) \Epi\getRoute()->redirect('/login');
				
		
		$temp_oa_dir = self::getTempDir($task);

		// get content of POST request 
		$text = $_POST['text'];
		
		
		//$file = $temp_oa_dir . '/OUTPUT-SAVE.txt';
		//file_put_contents($file, $text);
		
				
		//var_dump($text);
		//$text = str_replace(array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"), array("'", "'", '"', '"', '-', '--', '...'), $text);
		// Next, replace their Windows-1252 equivalents.
		//$text = str_replace(array(chr(145), chr(146), chr(147), chr(148), chr(150), chr(151), chr(133)), array("'", "'", '"', '"', '-', '--', '...'), $text);
		//var_dump($text);

		//$file = $temp_oa_dir . '/OUTPUT-PROCESS.txt';
		//file_put_contents($file, $text);
		
		//echo $text;

		$lastversion = $_POST['version'];

		// compute new version ID 
		$newversion = str_pad((int) intval($lastversion), 4, "0", STR_PAD_LEFT);

		// Call the various services
		$client = new APIEssayAnalyser();
		
		$stopwatch = -microtime(true);
		$jsonTxt = $client->getAnalysis($text);
		$stopwatch += microtime(true);
		self::debug($client->getCalledURL() . " " . sprintf('%f', $stopwatch));	
		
		
		$stopwatch = -microtime(true);
		$jsonKword = $client->getKeywords($text);
		$stopwatch += microtime(true);
		self::debug($client->getCalledURL() . " " . sprintf('%f', $stopwatch));	
		
		$client2 = new APISpellCheck();
		
		$stopwatch = -microtime(true);
		$jsonSpell = json_decode($client2->getStats($text), true);
		$stopwatch += microtime(true);
		self::debug($client->getCalledURL() . " " . sprintf('%f', $stopwatch));	
		self::debug('$jsonSpell => ' . print_r($jsonSpell,true));	
		//var_dump($jsonSpell);
		if (!isset($jsonSpell))
			$jsonSpell = array();

		//foreach ($jsonKword as $id => $data)
		//{
		//	$jsonKword[$id] = $data;
		//}
		//foreach ($jsonTxt as $id => $data)
		//{
		//	var_dump($id);
		//}

		//var_dump($jsonSpell['metric']);
		//$jsonTxt['metric'] = $jsonSpell['metric'];

		$res = array_merge_recursive($jsonTxt, $jsonKword, $jsonSpell);

		$ff = $_REQUEST['__route__'];

		if (isset($res['error'])) {
			$params = array();
			$params['error'] = $res['error'];
			$params['text'] = $text;
			//var_dump($params);
			\Epi\getSession()->set('errors', $params);

		} else {
			$temp_oa_dir = self::getTempDir($task);
			//var_dump($temp_oa_dir);
			//$tttt = str_pad((int) $inc1,4,"0",STR_PAD_LEFT);
			$file = $temp_oa_dir . "/" . $newversion . '.txt';
			$content = json_encode($res);
			//var_dump($content);
			file_put_contents($file, $content);
			//var_dump($file);
			$ff = str_replace('submit', 'essay/' . $newversion, $ff);
			//var_dump($ff);
		}
		//var_dump($ff);

		\Epi\getRoute()->redirect($ff);

		//$params = array();
		//$params['heading'] = 'openEssayist';
		//$params['content'] = '';

		//IController::showTemplate('openEssayist-template.php', $params);

	}

	static public function ShowEssay($task, $essay) {
		if (!self::isUser()) \Epi\getRoute()->redirect('/login');
		
		//var_dump($_REQUEST);
		//var_dump($essay);
		//var_dump($task);
		//var_dump($_SERVER);

		// Retrieve the essay data
		$apurl = '/user/UID/task/' . $task . '/essay/' . $essay . '.json';
		$apiTask = \Epi\getApi()->invoke($apurl);
		$ret = $apiTask;

		self::debug('$apiTask => ' . print_r(array_keys($apiTask), true));

		
		// Get a list of ranked sentence indexes
		$ggg = array();
		foreach ($ret['ranked'] as $ranking) {
			//var_dump($ranking);
			$ggg[] = $ranking[1];
		}
		$ret['ranking'] = $ggg;
		
		
		
		
		// Get a list of structure identifiers
		$hhh = array();
		foreach ($ret['struct'] as $ranking) {
			$hhh[$ranking[0]] = $ranking[1];
		}
		$ret['struct'] = $hhh;
		self::debug('$apiTask => ' . print_r($hhh, true));
		
		// Get the list of bigrams
		$kk = $ret['bigrams'];

		// Extract comma-sepatated list of terms
		$comma_separated = "";

		$inc = 0;
		foreach ($kk as $item) {
			$kword = $item['ngram'];
			if (strlen($kword[0]) > 2 && strlen($kword[1]) > 2)
				$comma_separated .= $kword[0] . " " . $kword[1] . " ";
		}
		$comma_separated = substr_replace($comma_separated, "", -1);
		self::debug('$comma_separated => ' . print_r($comma_separated, true));

		$ff = \Epi\getTemplate()->get('essay.widget.php', $ret);

		$params = array();
		$params['heading'] = 'openEssayist';
		$params['content'] = $ff;

		
		$ff = \Epi\getTemplate()->get('breadcrumb.widget.php', array('task' => $task, 'essay' => $essay));
		$params['breadcrumb'] = $ff;

		$params['injectCSS'] = <<<EOF
		<style>
			.inlinebar, .sparkbullet {
				float: right;
				}
		</style>
		<link rel="stylesheet" title="jTour" href="/bootstrap/jquery-tour/jquery.tour.css">
EOF;

		$params['injectJS'] = <<<EOF
		<script src="/bootstrap/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js"></script>
		<script src="/bootstrap/highlight.pack.js"></script>
		<script src="/bootstrap/jquery.sparkline.js"></script>
		<!--<script src="/bootstrap/jquery.peity.min.js"></script>-->
		<script>
        	hljs.OPENESSAYIST_KEYWORDS = '$comma_separated';
        </script>
		<script src="/bootstrap/highlight.openessayist.js"></script>
	<script>
        			
		$(function() {
        	$('.btn-setting').click(function(e){
				e.preventDefault();
				$('#myModal').modal('show');
			}); 		
        			
        	$('.inlinebar').sparkline('html', {
        			type: 'bar', 
        			chartRangeMin: 0, 
        			chartRangeMax: .25 , 
        			tooltipPrefix: '',
        			tooltipSuffix: '', 
        			disableHiddenCheck: true, 
        			height: '18px', 
        			width: '100px', 
        			barColor: 'red', 
        			numberDigitGroupCount: 5,
        			colorMap: {
        				'.25:1': 'green',
        				'.1:.2499999': 'red',
        				'0:.0999999': 'lightblue'
					}
				});
			//$('.inlinebar').peity("bar");        			
        	//$('.inlinesparkline').sparkline(); 
        	//$('.sparklines').sparkline();         			
        	//$('.sparkbullet').sparkline('html', {type: 'bullet', disableHiddenCheck: true, height: '18px', width: '20px',performanceColor: '#8484f4'});         			
		});
        			
		function checkStructure(checkbox) {
        	var indicators = checkbox.value.split(";");
        	indicators.forEach(function(elt,idx,arr) {
				var classname = "span.oe-snt[data-struct='" + elt + "']" ;
				if (checkbox.checked)
					$(classname).show()
				else
					$(classname).hide("slow");
        			
        			});
        			
        		
		}
        			
		function checkAddress(checkbox) {
			var classname = 'span.class' + checkbox.value;
			if (checkbox.checked)
				$(classname).removeClass('class0');
			else
				$(classname).addClass('class0');
		}

		function checkBlur(checkbox) {
			var classname = 'span.oe-snt';
			if (checkbox.checked)
				{
				$(classname).addClass('oe-blur');
				//$(classname).attr('data-text',$(classname).text());
				//$(classname).text('xxxxxxx');
				}
			else
				{
								
				$(classname).removeClass('oe-blur');
				//$(classname).text($(classname).attr('data-text'));
				}
		
		}

		$(document).ready(function() {
			$('div.myessay').each(function(i, e) {
				hljs.highlightBlock(e)
			});
        			
        			
		$( ".droptrue" ).sortable({
			connectWith: ".connectedSortable",
        	placeholder: "ui-state-highlight",
        	update: function(event, ui) {
				var order = $(this).sortable('toArray').toString();
				console.log(this.id + " " + order);
			}			
		});
$( ".dropfalse" ).sortable({
connectWith: ".connectedSortable",
        			placeholder: "ui-state-highlight",
dropOnEmpty: false
});
$( "#sortable0, #sortable1, #sortable2, #sortable3" ).disableSelection();

        			
        			
			$('#mytabs a:first').tab('show');
			$('#mytabs a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
})
			$( "#slider-range" ).slider({
	            range: true,
	            min: 0,
	            max: 15,
	            values: [ 0, 1 ],
	            slide: function( event, ui ) {
	                $( "#amount" ).text( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
	                
	                $('span.oe-snt-rank').each(function(){
		               	var tt =  parseInt($(this).attr('data-snt-rank'));
		               	var mina = ui.values[ 0 ];
		               	var maxa = ui.values[ 1 ];
		               	
		               	$(this).removeClass('show');
		               	$(this).removeClass('dimshow');
		                if (mina <= tt && tt <= maxa)
		                	{
		                		//if (tt == maxa)
		                			$(this).addClass('show');
		                		//else
		                		//	$(this).addClass('dimshow');
		                		
		                	}
		                	                });
	                
	            }
	        });
	        $( "#amount" ).text(  $( "#slider-range" ).slider( "values", 0 ) +
	            " - " + $( "#slider-range" ).slider( "values", 1 ) );

		});
	</script>	
	<script src="/bootstrap/jquery-tour/jquery.tour.js"></script>        			
	<script>
	(function(u, d, t) {
		window.rx = true;
		//console.log("fd");
	})();

	$(document)
				.ready(
						function() {
							
							var tourdata = [
									{
										html : "Hello World",
        								showTab: $('#mytabs a[href="#review"]')
									},
									{
										html : "Follow this rule - it's very important!",
										element : $('div.alert-block'),
										overlayOpacity : 0.5,
										expose : true,
										position : 'n'
									},
									{
										html : "This is a codeblock which shows the example code",
										element : $('pre'),
										position : 'n'
									}, {
										html : "This is a paragraph",
										element : 'div.btn-group',
        								showTab: $('#mytabs a[href="#keywords"]'),
        			overlayOpacity : 0.5,
										expose : true,
										position : 's'
									}, {
										html : "This is the second list entry",
										element : $('div#keywords div.span3').eq(1),
        			overlayOpacity : 0.5,
										expose : true,
										position : 'n'
									} ];
							var myTour = jTour(
									tourdata,
									{
										axis : 'y',
										onStop : function() {
        										//alert("!ffddffddfdf");
											
										},
										onChange : function(current) {
											
										},
										onFinish : function(current) {
											
										},
										scrollBack : true
									});
							$('#starttour').click(function() {
								
        						$('#mytabs a:first').click();
        						myTour.restart();
							});
						});
	</script>
		
EOF;

		IController::showTemplate('openEssayist-template.php', $params);
	}

	/**
	 * 
	 */
	static public function Login() {
		if (\Epi\getSession()->get(Constants::LOGGED_IN) == true) {
			\Epi\getRoute()->redirect('/me');
			die();
		}

		$ff = \Epi\getTemplate()->get('login-widget.php');

		$params = array();
		$params['heading'] = 'openEssayist';
		$params['content'] = $ff;
		IController::showTemplate('openEssayist-template.php', $params);

	}

	/**
	 * 
	 */
	static public function ProcessLogin()
	{
		// clean user & password
		$user = strip_tags(substr($_POST['username'],0,32));
		$pw = strip_tags(substr($_POST['password'],0,32));

		// RIDICULOUS but let's try
		$config = \Epi\getConfig()->get("admin");
		$admin_pwd = crypt($config);
		
		// check admin password		
		if (crypt($pw, $admin_pwd) == $admin_pwd) {
			\Epi\getSession()->set(Constants::ADMIN_IN, true);
			
		}
		
		\Epi\getSession()->set(Constants::USERNAME, $user);
		\Epi\getSession()->set(Constants::LOGGED_IN, true);
		\Epi\getRoute()->redirect('/me');
	}

	/**
	 * 
	 */
	static public function Logout()
	{
		\Epi\getSession()->end();
		\Epi\getRoute()->redirect('/');
	}

	static public function Dashboard2() {
		if (!self::isUser()) \Epi\getRoute()->redirect('/login');
		
		$apiParams2 = \Epi\getApi()->invoke('/user/UID.json');
		$apiParams2 = \Epi\getApi()->invoke('/user/UID/task/UID.json');

		$apiParams = (array) \Epi\getApi()->invoke('/user/UID/task/UID/essay/UID.json');
		//$apiParams = json_decode($apiParams2,true);

		$apiParams2 = \Epi\getApi()->invoke('/user/UID/task/UID/essay/UID/feedback.json');

		$fff = (array) $apiParams['text'];
		$ggg = $apiParams2['text'];

		$nav = array();
		$nav2 = array();
		foreach ($fff as $text) {
			$nav[] = (array) $text;
		}
		$inc = 0;
		foreach ($ggg as $text) {
			$tttt = (array) $text;
			$tttt['itemid'] = str_pad((int) $inc, 4, "0", STR_PAD_LEFT);
			$nav[] = $tttt;
			$nav2[] = $tttt;
			$inc++;
		}

		//Obtain a list of columns
		foreach ($nav as $key => $row) {
			$volume[$key] = $row['id'];
			$edition[$key] = $row['type'];
		}
		array_multisort($volume, SORT_ASC, $edition, SORT_ASC, $nav);

		//var_dump($nav);

		$apiParams['text'] = $nav;
		//$apiParams["user"] = "XXXXXXXXXXXXXXXXX";
		$ff = \Epi\getTemplate()->get('essay-widget.php', $apiParams);

		$params = array();
		$params['heading'] = 'openEssayist';
		$params['twocolumn'] = 'twocolumn';
		//$params['content'] = str_repeat('Lorem ipsum ', 100);
		//$params['content'] .= print_r($apiParams,true) ;		

		$params['content'] = $ff;

		$params['subcontent'] = $nav2;

		IController::showTemplate('openEssayist-template.php', $params);
	}

	static public function KeywordHistory($task) {
		if (!self::isUser()) \Epi\getRoute()->redirect('/login');
		
		//var_dump($_REQUEST);
		//var_dump($_GET);
		//var_dump($_POST);
		//var_dump($_SERVER);

		$apiUser = \Epi\getApi()->invoke('/user/UID.json', \Epi\EpiRoute::httpGet, array('params' => 'stats'));
		$apiTask = \Epi\getApi()->invoke('/user/UID/task/' . $task . '.json');
		//var_dump($apiUser);

		$table = array();
		$round_names = array();
		$total = array();

		$report = array();

		$ref = array();
		$count = array();
		$labels = array();
		foreach ($apiTask['essays'] as $index => $item) {

			if ($index == count($apiTask['essays'])-1) continue	;
			$serie = array();
			$count[$index] = array();
			$apurl = '/user/UID/task/' . $task . '/essay/' . $item['ref'] . '.json';
			$apiEssay = \Epi\getApi()->invoke($apurl);

			//var_dump($apiEssay['stats']);
			//$apiTask['essays'][$inc]['stats'] = $apiEssay['stats'];
			//$apiTask['essays'][$inc]['metrics'] = $apiEssay['metric'];
			$apiTass = array_slice($apiEssay['bigrams'], 0, 150);

			//var_dump($index);
			foreach ($apiTass as $id => $kstruct) {
				$kk = "" . join(" ", $kstruct['ngram']);

				$round_names[] = $kk;
				$table[$item['ref']][$kk] = $kstruct['count'];
				$total[$kk] += $kstruct['count'];

				$report[$item['ref']][$kk] = $kstruct['count'];
				if (!isset($ref[$kk])) {
					$ff = count($ref);
					//var_dump($ref);
					$ref[$kk] = $ff;
					$labels[$ff] = $kk;
					//$count2[] = array();
					//$count2[$index] = $kstruct['count'];
					//$count[] = $count2;
					$count[$index][] = array($ff, $kstruct['count']);
				} else {
					$count[$index][] = array($ref[$kk], $kstruct['count']);
				}
			}

			$inc++;

		}
		//var_dump($ref);var_dump($labels);var_dump();
		$ggggg = json_encode($count);
		//var_dump($table);
		//var_dump($round_names);
		$round_names = array_unique($round_names);
		$round_names = array_values($round_names);
		//var_dump($round_names);
		//var_dump($total);

		//echo "<pre>";
		$matrix = array();
		foreach ($table as $player => $rounds) {
			$row = array();
			//echo "$player\t";
			foreach ($round_names as $index => $round) {
				//$row[] = array($index+1,empty($rounds[$round]) ? 0 :$rounds[$round] );
				$row[] = empty($rounds[$round]) ? 0 : $rounds[$round];
				//if (!empty($rounds[$round]))
				//$row[] = array($index,$rounds[$round] );
				//echo (empty($rounds[$round]) ? "0" : $rounds[$round]) . " ";
			}

			//echo "$total[$round]\n";
			$matrix[] = $row;
		}
		//var_dump($matrix);
		//self::debug('$round_names => ' .  print_r($round_names,true));
		//self::debug('$table => ' .  print_r(array_keys($table),true));
		//self::debug('$matrix => ' .  print_r($matrix,true));

		$htmltable = "<table id='datatable' class='table table-striped table-bordered'>";
		$htmltable .= "<thead><tr>";
		$htmltable .= "<th></th>";

		/*		foreach ($round_names as $index => $round)
		        {
		            //$title = $item['ref'];
		            $htmltable .= "<th>$round</th>";
		                
		        }
		        $htmltable .= "</tr></thead>";
		        $htmltable .= "<tbody>";
		        foreach ($matrix as $word => $count)
		        {
		            $tt = array_keys($table);
		            $htmltable .= "<tr>";
		            $htmltable .= "<td>$tt[$word]</td>";
		            $htmltable .= "<td>".join("</td><td>",$count)."</td>";
		            $htmltable .= "</tr>";
		        }*/

		foreach ($table as $essay => $count) {
			//$title = $item['ref'];
			$htmltable .= "<th>$essay</th>";
		}
		$htmltable .= "</tr></thead>";
		$htmltable .= "<tbody>";
		foreach ($round_names as $index => $word) {
			$htmltable .= "<tr>";
			$htmltable .= "<td>$word</td>";
			foreach ($matrix as $essay => $count) {
				$gg =  $count[$index];
				//var_dump($gg);
				if ($gg == 0) $gg = "";
				$htmltable .= "<td>" . $gg . "</td>";

			}
			$htmltable .= "</tr>";
		}

		$htmltable .= "</tbody>";
		$htmltable .= "</table>";

		//var_dump($matrix);
		$matrix = json_encode(array_slice($matrix, 0));
		//var_dump($matrix);
		$round_names = json_encode($round_names);
		//echo "</pre>";

		$tep = <<<EOF

		<div class="row-fluid">
			<div class="widget">
				<div class="widget-content"> 
				    <div class="btn-group" data-toggle="buttons-radio">
					    <button id="b3" type="button" data-chart="column" class="btn btn-primary active">Bar</button>
					    <button id="b3" type="button" data-chart="area" class="btn btn-primary">Area</button>
					    <button id="b3" type="button" data-chart="bar" class="btn btn-primary">Column</button>
					    </div>
				 	<button id="b1" type="button" class="btn active" data-toggle="button">Stack</button>
				    <button id="b2" type="button" class="btn"> Swap keywords/essay </button>
				</div> <!-- /widget-content -->
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="widget">
				<div class="widget-header">
					<i class="icon-picture"></i>
					<h3>Keywords (top 5)</h3>
				</div> <!-- /widget-header -->
				<div class="widget-content"> 
				 	<div id="chart1" style="min-width: 450px; height: 400px; margin: 0 auto"></div>
				</div> <!-- /widget-content -->
			</div>
		</div>
				
		<div class="row-fluid">
			<div class="container">
				$htmltable
			</div>	
		</div>
EOF;

		$params = array();
		$params['heading'] = 'openEssayist';
		$params['content'] = $tep;

		$ff = \Epi\getTemplate()->get('breadcrumb.widget.php', array('task' => $task));
		$params['breadcrumb'] = $ff;




		$params['injectCSS'] = <<<EOF
		<style>
			
			#datatable th {
				//-webkit-transform: rotate(-90deg);
				//-moz-transform: rotate(-90deg);
			}
		
			#datatable td
			{
			padding: 0 8px;
			}
		</style>
EOF;
		
		$params['injectJS'] = <<<EOF
			<script src="/bootstrap/highcharts/js/highcharts.js">	</script>
			<script>
				
 $(document).ready(function() {
        /**
         * Visualize an HTML table using Highcharts. The top (horizontal) header
         * is used for series names, and the left (vertical) header is used
         * for category names. This function is based on jQuery.
         * @param {Object} table The reference to the HTML table to visualize
         * @param {Object} options Highcharts options
         */
		var chart;
			var options;
				var table;
				
    $("#b1").click(function() {
		var sta = options.plotOptions.series.stacking;
        if (!sta)
				options.plotOptions.series.stacking = 'normal';
		else
				options.plotOptions.series.stacking = null;
        // force toredraw:
				 table = document.getElementById('datatable');
        Highcharts.visualize(table, options);
    });		
				
    $("#b3,#b4").click(function() {
		var type = options.chart.type;
		var action = $(this).attr('data-chart');				
		if (type!==action && action !== null )	
				{
				options.chart.type = action;
				table = document.getElementById('datatable');
        Highcharts.visualize(table, options);
				}	

	});

	$("#b2").click(function() {
    var t = $('#datatable tbody').eq(0);

				
var oldTable = $('table#datatable');
var newTable = $('<table></table>');
newTable.addClass(oldTable.attr('class'));
newTable.attr('id',oldTable.attr('id'));
//newTable.attr('border',1);
				
//subtract one to compensate for the row of headers
var oldNumRows = $('table#datatable tr').length  ;
//using number of headers to find number of columns
var oldNumCols = $('table#datatable th').length;
var numRows = oldNumCols;
var numCols = oldNumRows;

//now iterate and store the values of the old table
//(omitted this part out of laziness)

var headers = $('<thead></thead>');
var th = $('<tr></tr>');
headers.append(th);
for(i=0; i<numCols; i++) {
  var header = $('<th></th>');
  header.addClass($('table#datatable th').attr('class'));
  fgfgfg = 	$('table#datatable tbody tr');
  //rr = fgfgfg[i].cells[0];

  header.text((i==0)? "" : fgfgfg[i-1].cells[0].innerHTML);
  th.append(header);
}

newTable.append(headers);

var tableData;

//string manipulation will probably save you a headache here if you just want to inject the cells
for(i=1; i<numRows; i++) { 
	tableData += '<tr>';
  for(j=0; j<numCols; j++) {
    tableData += '<td>';
    //the real data would be inserted here
	fgfgfg = 	$('table#datatable tr')[j];
		
	rr = fgfgfg.cells[i];
	//console.log(rr.innerHTML);					
	tableData += rr.innerHTML;
   // tableData += '(' + i + ',' + j + ')';
    tableData += '</td>';
  }
tableData += '</tr>';
}

newTable.append("<tbody>" + tableData + "</tbody>");

//newTable.insertAfter(oldTable);
oldTable.replaceWith(newTable);
				 Highcharts.visualize(newTable, options);
	});
				
				
        Highcharts.visualize = function(table, options) {
            // the categories
            options.xAxis.categories = [];
            //$('tbody th', table).each( function(i) {
            //    options.xAxis.categories.push(this.innerHTML);
            //});
    
            // the data series
            options.series = [];
            $('tr', table).each( function(i) {
                var tr = this;
                $('th, td', tr).each( function(j) {
                    if (j > 0) { // skip first column
                        if (i == 0) { // get the name and init the series
                            options.series[j - 1] = {
                                name: this.innerHTML,
                                data: []
                            };
                        } else { // add values
							var gg = parseFloat(this.innerHTML);
							if (isNaN(gg))
								if (options.chart.type == 'area')
									gg = 0;
								else 
					gg = null;
                            options.series[j - 1].data.push(gg);
                        }
                    }
				else
				{ if (i > 0) 
					options.xAxis.categories.push(this.innerHTML);
				}
                });
            });
    
            chart = new Highcharts.Chart(options);
        }
    
        table = document.getElementById('datatable');
        options = {
			credits: {
				enabled: false
				},
			plotOptions: {
                series: {
                                stacking: 'normal', // 'normal'
            groupPadding: 0,
            pointPadding: 0.1
                }
            },
				legend: {
            verticalAlign: 'top'
        },
            chart: {
                renderTo: 'chart1',
                type: 'column',
				 marginBottom: 80
            },
            title: {
                /*text: 'Data extracted from a HTML table in the page'*/
            },
            xAxis: {
				
            labels: {
                rotation: 90,
				align: "left"
				}
				
				
				
				
				
            },title: {
            text: '',
            align: 'left',
            x: 70
        },
            yAxis: {
				//type: 'logarithmic',
				//minorTickInterval: 0.1,
                title: {
                    text: 'Units'
                }
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x.toLowerCase() +'</b><br/>'+
                        '<b>' + this.y +'</b> ('+ this.series.name + ')';
                }
            }
        };
    
        Highcharts.visualize(table, options);
    });			
				
				</script>	
		
EOF;

		IController::showTemplate('openEssayist-template.php', $params);
	}

	static private function searchInArray($haystack, $needle) {
		$keys = array();
		//var_dump($haystack[1]);
		$count = count($haystack) - 1;
		//var_dump($haystack[1]);
		//var_dump($count);

		for ($i = 0; $i < $count; $i++) {
			//var_dump($haystack[$i]);
			if ($haystack[$i] == $needle[0] && $haystack[$i + 1] == $needle[1])
				$keys[] = $i;
		}

		return $keys;
	}

	static public function ShowDispersion($task, $essay) {
		
		if (!self::isUser()) \Epi\getRoute()->redirect('/login');
				
		
		// Retrieve the essay data
		$apurl = '/user/UID/task/' . $task . '/essay/' . $essay . '.json';
		$apiTask = \Epi\getApi()->invoke($apurl);
		$ret = $apiTask;

		self::debug('$apiTask => ' . print_r(array_keys($apiTask), true));

		// Get the 5 top keywords
		$keywords = array_slice($apiTask['bigrams'], 0, 25);
		// Format them as a string
		foreach ($keywords as $index => &$data)
			$data = $data['ngram'];

		// get the full text
		$tt = $apiTask['parasenttok'];
		// Join the array into a single string
		foreach ($tt as $index => &$par)
			$par = "" . join(" ", $par);
		$tt = "" . join(" ", $tt);
		// Split by words
		$tt = str_word_count($tt, 1);

		/*function array_value_intersect_keys( $array_haystack, $array_needle ){
		 $intersected = array_intersect( $array_haystack, $array_needle );
		return array_keys( $intersected );
		}*/

		//var_dump(array_slice($tt,0,10));
		//var_dump($keywords);
		//var_dump($tt[0]);
		//var_dump($tt[1]);
		// search for keywords
		
		$gggg = $apiTask['struct'];
		//$gggg = array_flip($gggg);
		//var_dump($gggg);
		$hhh = array();
		foreach ($gggg as $k => $item)
		{
			if (!isset($hhh[$item[1]]))
				$hhh[$item[1]] = array();
			$hhh[$item[1]][] = $item [0];
		}
		//var_dump($hhh);

		$kkkk = $apiTask['parasenttok'];
		$lll = array();
		foreach ($kkkk as $piece)
		{	//var_dump($piece);
			$lll = array_merge($lll,$piece);
		}
	
		
		
		$deb = $hhh['#+s:i#'];
		$firsta = array_shift($deb);
		$lasta = array_pop($deb);
		//var_dump($firsta . " " . $lasta);
		$comma_separated = implode(" ", array_slice($lll,0,$firsta-1));
		$first = str_word_count($comma_separated);
		$comma_separated = implode(" ", array_slice($lll,0,$lasta+1));
		$last = str_word_count($comma_separated);

		$deb = $hhh['#+s:c#'];
		$firsta = array_shift($deb);
		$lasta = array_pop($deb);
		//var_dump($firsta . " " . $lasta);
		$comma_separated = implode(" ", array_slice($lll,0,$firsta-1));
		$firsta = str_word_count($comma_separated);
		$comma_separated = implode(" ", array_slice($lll,0,$lasta+1));
		$lasta = str_word_count($comma_separated);
		
		//var_dump($first . " " . $last);
		//$deb2 = $hhh['#+s:c#'];
		//$first2 = array_shift($deb2);
		//$last2 = array_pop($deb2);
		//var_dump($first2 . " " . $last2);
		//$comma_separated = explode(" ",implode(" ", array_slice($lll,0,$first2-1)));
		//var_dump(count($comma_separated));
		//$comma_separated = explode(" ",implode(" ", array_slice($lll,$first2,$last2-$first2+1)));
		//var_dump(count($comma_separated));
		
		$series = array();
		foreach ($keywords as $index => $keyword) {
			$serie = array();
			$gg = self::searchInArray($tt, $keyword);
			$serie['name'] = "" . join(" ", $keyword);
			//$series['color'] = "".join(" ",$keywords[0]);
			$serie['data'] = array();
			foreach ($gg as $index1 => $value) {
				$serie['data'][] = array($value, $index);
				//var_dump($tt[$value] . " " . $tt[$value+1]);
			}
			$series[] = $serie;
		}

		$categories = $keywords;
		foreach ($categories as $index => &$data)
			$data = "" . join(" ", $data);
		$categories = json_encode($categories);

		$series = json_encode($series);
		//var_dump($series);
		//series: [{
		//     name: 'Female',
		//     color: 'rgba(223, 83, 83, .5)',
		//     data: [[161.2, 51.6]]

		/** Generate the template **/
		$ff = \Epi\getTemplate()->get('breadcrumb.widget.php', array(
				'task' => $task, 
				'essay' => $essay,
				'graph'=> 'dispersion'));
								

		$params = array();
		$params['heading'] = 'openEssayist';
		$params['breadcrumb'] = $ff;

		$params['content'] = <<<EOF
<div id="resizer" style="min-width: 350px; min-height: 200px">
    <div id="inner-resizer">
    <div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
    </div>
    </div>
EOF;




		$params['injectJS'] = <<<EOF
<script src="/bootstrap/highcharts/js/highcharts.js"></script>
<script src="/bootstrap/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js"></script>
<script>

$(function () {

$('#resizer').resizable({
	ghost: true,
    // On resize, set the chart size to that of the 
    // resizer minus padding. If your chart has a lot of data or other
    // content, the redrawing might be slow. In that case, we recommend 
    // that you use the 'stop' event instead of 'resize'.
    stop: function() {
        chart.setSize(
            this.offsetWidth - 20, 
            this.offsetHeight - 20,
            false
        );
    }
});



var chart;

$(document).ready(function() {
	chart = new Highcharts.Chart({
		chart: {
			renderTo: 'container',
			type: 'scatter',
			zoomType: 'xy'
		},
	title: {
		text: 'Keyword Dispersion'
	},
	
	xAxis: {
		min: 0,
    	title: {
	    	enabled: true,
	        text: 'word count'
		},
		startOnTick: true,
		endOnTick: true,
		showLastLabel: true,
		plotBands: [{ // Light air
                    from: $first,
                    to: $last,
                    color: 'rgba(68, 170, 213, 0.1)',
                    label: {
                        text: 'Introduction',
                        style: {
                            color: '#606060'
                        }
                    }
                },{ // Light air
                    from: $firsta,
                    to: $lasta,
                    color: 'rgba(68, 170, 213, 0.1)',
                    label: {
                        text: 'Conclusion',
                        style: {
                            color: '#606060'
                        }
                    }
                }
                ]
	},
	yAxis: {
		title: {
			enabled: false,
			text: null
		},
		categories: $categories,
		labels: {}
	},
	tooltip: {
		formatter: function() {
			return this.series.name + ' ' + this.x;
                }
    },
    legend: {
		// layout: 'vertical',
		//align: 'left',
    	verticalAlign: 'top',
	    // x: 100,
	    y: 20,
	    floating: false,
	    backgroundColor: '#FFFFFF',
	    borderWidth: 1
	},
	plotOptions: {
		series: {
			stickyTracking: false
		},
		scatter: {
			marker: {
				symbol: 'square',
				radius: 5,
				states: {
					hover: {
						enabled: true,
						lineColor: 'rgb(100,100,100)'
					}
				}
			},
			states: {
				hover: {
					marker: {
						enabled: false
					}
				}
			}
		}
	},
	series: $series
	 
	});
	});
	 
	});
	</script>
EOF;

		IController::showTemplate('openEssayist-template.php', $params);

	}
	
	static public function ShowGraph($task, $essay) 
	{
		if (!self::isUser()) \Epi\getRoute()->redirect('/login');
				
		
		$apurl = '/user/UID/task/' . $task . '/essay/' . $essay . '.json';
		$apiTask = \Epi\getApi()->invoke($apurl);
		$ret = $apiTask;
		

		
		
		$func = function($v) {
			$elt = $v['ngram'];
			return "".join(" ",$elt);
		};
		
		self::debug('$apiTask => ' . print_r(array_keys($apiTask), true));

		// Extract the bigrams as joined strings
		$bigrams = array_map($func, $apiTask['bigrams']);

		// backward compatibility
		$graph = (isset($ret['graph_sub'])) ? $ret['graph_sub'] : null;
		$graph = (isset($ret['graph_adjacency'])) ? $ret['graph_adjacency'] : null;
		$graph = ($graph) ?: $ret['graph'];

		
		
		//Get node data, names and reorganise structure for display
		$nodedata = $graph['nodes'];
		$nodenames = array_map(function($v) { return  $v['id']; } , $nodedata);

		$nodes = array();
		foreach ($nodedata as $id => $node)
		{
			$elt = $node['id'];
			$inc=0;
			
			// Group node by bigrams
			foreach ($bigrams as $idx => $mot)
			{
				$tt = preg_match("/\b".preg_quote($elt)."\b/i",$mot);
				if ($tt) { $inc = count($bigrams)-$idx+1;}
			}
			$nodes[] = array(
					'nodeName' => $elt,
					'group' => $inc
			);
		}

		$adjacencydata = $graph['adjacency'];
		$links = array();
		foreach ($adjacencydata as $key => $element)
		{
			$source = $key;
			foreach($element as $id => $node)
			{
				$dest = $node['id'];
				$dest = array_search($dest,$nodenames);
				
				if ($dest) $links[] = array(
						"source" => $source,
						"target" => $dest,
						"value" => 1
						);
			}
			
		}

		$nodes = json_encode(($nodes));
		$links = json_encode(($links));
		
		//var_dump(array_keys($ret['graph']));
		//var_dump($nodes);
		//var_dump($links);
		//var_dump($ret['graph']['adjacency']);
		
		/** Generate the template **/
		$ff = \Epi\getTemplate()->get('breadcrumb.widget.php', array(
				'task' => $task,
				'essay' => $essay,
				'graph'=> 'adjacency'));
		
		$params = array();
		$params['heading'] = 'openEssayist';
		$params['breadcrumb'] = $ff;
		
		
		$params['content'] = <<<EOF
<div id="resizer" style="min-width: 350px; min-height: 200px">
    <div id="inner-resizer">
				<div id="container" style="min-width: 400px; height: 800px; margin: 0 auto"></div>
	</div></div>
EOF;
		//$params['content'] .= "<div>" . print_r($ret['graph'],true) . "</div>";
		
		$params['injectJS'] = <<<EOF
<script src="/bootstrap/protovis.js"></script>
<script src="/bootstrap/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" src="/bootstrap/miserables.js"></script>				

<script type="text/javascript+protovis">



var w = document.body.clientWidth,
    h = document.body.clientHeight,
    colors = pv.Colors.category20();

       
var vis = new pv.Panel()
	.canvas('container')
    .fillStyle("white")
    .event("mousedown", pv.Behavior.pan())
    .event("mousewheel", pv.Behavior.zoom());

var force = vis.add(pv.Layout.Force)
    .nodes($nodes)
    .links($links)
	.springLength(15);

force.link.add(pv.Line);

force.node.add(pv.Dot)
    .size(function(d) (d.group*10 +10 ) * Math.pow(this.scale, -0.5))
    .fillStyle(function(d) d.fix ? "brown" : (d.group ? colors(d.group).alpha(1) : colors(d.group).alpha(0.5)))
    .strokeStyle(function() this.fillStyle().darker())
    .lineWidth(function(d) 0)
    .title(function(d) d.nodeName + " (" + d.group + ")")
    .event("mousedown", pv.Behavior.drag())
    .event("drag", force);
				
vis.render();

    </script>					
EOF;
		IController::showTemplate('openEssayist-template.php', $params);
	}

}

?>

