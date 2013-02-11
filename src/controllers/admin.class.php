<?php namespace openEssayist;

include_once 'controller.php';

/**
 * Handlers for the administrator routes
 *
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class AdminController extends IController
{
	/**
	 * Administrator's route for testing external services
	 * @param string $service	The service ID to test
	 * @return A JSON object containing the result of the service's test function
	 */
	static public function TestServices($service)
	{
		// Call the various services
		$client = new APIEssayAnalyser();
		$response = $client->getStats("test connection");
		return $response;
	}

	/**
	 *
	 */
	static public function AdminPanel()
	{
		$config = \Epi\getConfig()->get();
		$temp_oa_dir = self::getTempDir($task);

		// Call the various services
		$client = new APIEssayAnalyser();
		$ret1 = $client->getStats("test server");
		$config->pyAnalyser->server = $client->getCalledURL();
		$config->pyAnalyser->message = $ret1;


		
		$client2 = new APISpellCheck();
		$ret2 = $client2->getStats("test server");
		$config->afterthedeadline->message = $ret2;
		$config->afterthedeadline->server = $client2->getCalledURL();
		//$ret = self::TestServices();
		//var_dump($ret);

		$task = "versions";
		$essay = "0001";
		$apurl = '/user/UID/task/' . $task . '/essay/' . $essay . '.json';
		$params = array('param1' => "test", 'param2' => "test2");

		$apiGET = \Epi\getApi()->invoke($apurl,\Epi\EpiRoute::httpGet,array('_GET' => $params));
		var_dump($apiGET);
		$apiGET = \Epi\getApi()->invoke($apurl,\Epi\EpiRoute::httpPost,array('_GET' => $params));
		var_dump($apiGET);
		$output = \Epi\getTemplate()->get('admin-panel.widget.php', array(
				'config' => $config,
				'tempdir' => 	$temp_oa_dir
		));
		
		$params = array();
		$params['heading'] = 'Administration';
		$params['content'] = $output;

		$params['injectJS'] = <<<EOF
<script src="/bootstrap/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="/bootstrap/jquery.blockUI.js"></script>
<script>
$(document).ready(function() {
	$.blockUI.defaults.message = '<h1>Data collected...</h1>';
	
	function testAPI(vUrl,vMethod,vData)
	{
		return $.ajax({
			url: vUrl,
    		cache: false,
    		type: vMethod,
   			data: vData,
    		beforeSend: function () {
		        // doing something in UI
    		},
    		complete: function () {
		        // doing something in UI
    		},
    		success: function (data) {
		        // doing something in UI
    		},
    		error: function () {
		        // doing something in UI
    		}
		});
	}
	
	var params =  { param1: "test", param2 : "test2" };
	
	$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
	$("#b1").click(function() {
		testAPI("$apurl","GET",params);
	});
			
	$("#b2").click(function() {
		testAPI("$apurl","POST",params);
	});

	$("#b3").click(function() {
		testAPI("$apurl","PUT",params);
	});
				
	$("#b4").click(function() {
		testAPI("$apurl","DELETE",params);
	});
				
});

</script>
EOF;

		IController::showTemplate('openEssayist-template.php', $params);

	}


	/**
	 *
	 * @return multitype:string unknown multitype:
	 */
	static public function APIs()
	{
		// Check if we're already logged in, although we SHOULD never get here
		//if (\Epi\getSession()->get(Constants::LOGGED_IN) == false)
		//{
		//	\Epi\getRoute()->redirect('/login');
		//}

		// build a list of API tests
		$test = array();

		// build a list of API tests
		$url = '/user.json';
		$urlret =  \Epi\getApi()->invoke($url);
		$test[] = array(
				"description" => "Get the list of all users",
				"api" => $url,
				"output" => AdminController::indent(json_encode($urlret))
		);

		// build a list of API tests
		$url = '/user/UID/task.json';
		$urlret =  \Epi\getApi()->invoke($url);
		$test[] = array(
				"description" => "Get all assignments of this user",
				"api" => $url,
				"output" => AdminController::indent(json_encode($urlret))
		);

		$ggggggg = $urlret['tasks'][0]['id'];
		//var_dump($ggggggg);

		// build a list of API tests
		$url = "/user/UID/task/$ggggggg.json";
		$urlret =  \Epi\getApi()->invoke($url);
		$test[] = array(
				"description" => "Get the detail of a particular assignment",
				"api" => $url,
				"output" => AdminController::indent(json_encode($urlret))
		);

		// build a list of API tests
		$url = "/user/UID/task/$ggggggg/essay.json";
		$urlret =  \Epi\getApi()->invoke($url);
		$test[] = array(
				"description" => "Get all the drafts submitted to the system",
				"api" => $url,
				"output" => AdminController::indent(json_encode($urlret))
		);
		$hhhhh = $urlret['essays'][0]['ref'];
		//var_dump($hhhhh);

		// build a list of API tests
		$url = "/user/UID/task/$ggggggg/essay/$hhhhh.json";
		$urlret =  \Epi\getApi()->invoke($url,\Epi\EpiRoute::httpGet);
		$test[] = array(
				"description" => "Get the details of a particular draft",
				"api" => $url,
				"output" => AdminController::indent(json_encode($urlret))
		);


		// build a list of API tests
		$url = "/user/UID/task/$ggggggg/essay/$hhhhh/feedback.json";
		$urlret =  \Epi\getApi()->invoke($url,\Epi\EpiRoute::httpGet,array('schema'=>'false'));
		$test[] = array(
				"description" => "Get feedback on this particular essay",
				"api" => $url,
				"output" => AdminController::indent(json_encode($urlret))
		);

		//$test2 = array();
		//$test2 = array_slice($test,0,5);

		$tpllogin = new \Epi\EpiTemplate();
		$output = $tpllogin->get('api-list.widget.php',array('api' => $test));


		$template = new \Epi\EpiTemplate();
		$params = array();
		$params['heading'] = 'APIs';
		$params['content'] = $output;

		$params['injectCSS'] = <<<EOF
		<link rel="stylesheet" title="GitHub" href="/bootstrap/css/highlight.github.css">
EOF;
		$params['injectJS'] = <<<EOF
		<script src="/bootstrap/highlight.pack.js"></script>
		<script src="/bootstrap/highlight.languages.js"></script>
		<script>
			$(document).ready(function() {
					hljs.initHighlightingOnLoad()
				});
		</script>
EOF;

		IController::showTemplate('openEssayist-template.php', $params);
	}

	static public function Services()
	{
		$url = '/user/UID/task/UID/essay/UID.json';
		$urlret =  \Epi\getApi()->invoke($url);

		$text = $urlret['text'];

		$allt = array();

		foreach ($text as $value) {
			$allt[] = $value['sentence'];
		}
		$hhh = implode (" ", $allt);


		// build a list of API tests
		$test = array();

		// build a list of API tests
		$client = new APISpellCheck();
		$ret = $client->getStats($hhh);
		$test[] = array(
				"description" => "Get the list of all users",
				"api" => $client->getCalledURL(),
				"output" =>AdminController::indent(($ret))
		);
			
		// build a list of API tests
		$client = new APIEssayAnalyser();
		$ret = $client->getStats($hhh);
		$test[] = array(
				"description" => "Get the list of all users",
				"api" => $client->getCalledURL(),
				"output" =>AdminController::indent(json_encode($ret))
		);
			
		$tpllogin = new \Epi\EpiTemplate();
		$output = $tpllogin->get('admin-widget.php',array('api' => $test));

			
		$template = new \Epi\EpiTemplate();
		$params = array();
		$params['heading'] = 'openEssayist';
		$params['content'] = $output;

		IController::showTemplate('openEssayist-template.php', $params);
	}

	static public function AdminTest()
	{
		// Check if we're already logged in, although we SHOULD never get here
		//if (\Epi\getSession()->get(Constants::LOGGED_IN) == false)
		//{
		//	\Epi\getRoute()->redirect('/login');
		//}

		// build a list of API tests
		$url = '/user/UID/task/UID/essay/UID/feedback.json';
		$urlret =  \Epi\getApi()->invoke($url);


		$testctrl = <<<TMQ
<div class="alert alert-block alert-error fade in">
    <button type="button" class="close" data-dismiss="alert">
        X
    </button>
    <h4 class="alert-heading">%1\$s</h4>
    <div>
        <p><button type="button" class="btn btn-danger" data-toggle="collapse" data-target="#ground">
            Explain
        </button></p>
    </div>
    <div id="ground" class="collapse">
        <p>
            %2\$s
        </p>
        <p>
            <button type="button" class="btn btn-danger" data-toggle="collapse" data-target="#qualifier">
                Anything Else?
            </button>
        </p>

        <div id="qualifier" class="collapse">
            <p>
                %3\$s
            </p>
            <p>
                <button type="button" class="btn btn-danger" data-toggle="collapse" data-target="#rebuttal">
                    What should I do?
                </button>
            </p>

            <div id="rebuttal" class="collapse">
                <p>
                    %4\$s
                </p>
                <p>
                    <button type="button" class="btn btn-danger" data-toggle="collapse" data-target="#rebuttal">
                        Explain
                    </button>
                </p>
            </div>

        </div>

    </div>        <button type="button" class="btn">
            Close
        </button>
</div>

TMQ;

		$hhh = $urlret['text'][0]['argument'];
		if (isset($hhh))
		{
			$example = array(
					0 => 'first',
					'second' => 'second',
					'third',
					4.2 => 'fourth',
					'fifth',
					-6.7 => 'sixth',
					'seventh',
					'eighth',
					'9' => 'ninth',
					'tenth' => 'tenth',
					'-11.3' => 'eleventh',
					'twelfth'
			);

			$test1 = vsprintf( '%1$s %2$s %3$s %4$s %5$s %6$s %7$s %8$s %9$s %10$s %11$s %12$s<br />', $example); // acts like vsprintf

			$hhh2 = $urlret['text'][0]['params'];

			$resstr = vsprintf ($testctrl,$hhh);
			$resstr2 = vsprintf ($resstr,$hhh2);
			//var_dump($resstr2);
			//var_dump($hhh);
			$params['content'] = $ff . "" . $resstr2;
		}


		$template = new \Epi\EpiTemplate();

		$params = array();
		flog("Hello from PHP!");
		$params['heading'] = 'openEssayist';
		$template->display('test.php',$params);


	}


	static private function indent($json) {

		$result      = '';
		$pos         = 0;
		$strLen      = strlen($json);
		$indentStr   = '  ';
		$newLine     = "\n";
		$prevChar    = '';
		$outOfQuotes = true;

		for ($i=0; $i<=$strLen; $i++) {

			// Grab the next character in the string.
			$char = substr($json, $i, 1);

			// Are we inside a quoted string?
			if ($char == '"' && $prevChar != '\\') {
				$outOfQuotes = !$outOfQuotes;

				// If this character is the end of an element,
				// output a new line and indent the next line.
			} else if(($char == '}' || $char == ']') && $outOfQuotes) {
				$result .= $newLine;
				$pos --;
				for ($j=0; $j<$pos; $j++) {
					$result .= $indentStr;
				}
			}

			// Add the character to the result string.
			$result .= $char;

			// If the last character was the beginning of an element,
			// output a new line and indent the next line.
			if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
				$result .= $newLine;
				if ($char == '{' || $char == '[') {
					$pos ++;
				}

				for ($j = 0; $j < $pos; $j++) {
					$result .= $indentStr;
				}
			}

			$prevChar = $char;
		}

		return $result;
	}

}

?>

