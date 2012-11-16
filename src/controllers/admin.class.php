<?php namespace openEssayist;

include_once 'controller.php';

/**
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class AdminController implements IController
{


	static function indent($json) {

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
		$url = '/user/UID.json';
		$urlret =  \Epi\getApi()->invoke($url);
		$test[] = array(
				"description" => "Get the user profile ",
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

		// build a list of API tests
		$url = '/user/UID/task/UID.json';
		$urlret =  \Epi\getApi()->invoke($url);
		$test[] = array(
				"description" => "Get the detail of a particular assignment",
				"api" => $url,
				"output" => AdminController::indent(json_encode($urlret))
		);

		// build a list of API tests
		$url = '/user/UID/task/UID/essay.json';
		$urlret =  \Epi\getApi()->invoke($url);
		$test[] = array(
				"description" => "Get all the drafts submitted to the system",
				"api" => $url,
				"output" => AdminController::indent(json_encode($urlret))
		);

		// build a list of API tests
		$url = '/user/UID/task/UID/essay/UID.json';
		$urlret =  \Epi\getApi()->invoke($url,\Epi\EpiRoute::httpGet,array('schema'=>'true'));
		$test[] = array(
				"description" => "Get the details of a particular draft",
				"api" => $url,
				"output" => AdminController::indent(json_encode($urlret))
		);


		// build a list of API tests
		$url = '/user/UID/task/UID/essay/UID/feedback.json';
		$urlret =  \Epi\getApi()->invoke($url,\Epi\EpiRoute::httpGet,array('schema'=>'true'));
		$test[] = array(
				"description" => "Get feedback on this particular essay",
				"api" => $url,
				"output" => AdminController::indent(json_encode($urlret))
		);


		$tpllogin = new \Epi\EpiTemplate();
		$output = $tpllogin->get('admin-widget.php',array('api' => $test));


		$template = new \Epi\EpiTemplate();
		$params = array();
		$params['heading'] = 'openEssayist';
		$params['content'] = $output;

		$template->display('openEssayist-template.php', $params);
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

		$template->display('openEssayist-template.php', $params);
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

}

?>

