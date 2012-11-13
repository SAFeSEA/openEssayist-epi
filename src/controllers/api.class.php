<?php namespace openEssayist;

include_once 'controller.php';

/**
 *
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class APIController implements IController
{
	/**
	 * 
	 * @param unknown $request
	 * @return unknown
	 */
	static private function getNLTK($request)
	{
		if(($callback = @file_get_contents(Config::NLTK_SERVER())))
		{
			//if(\Epi\Epi::getSetting('debug'))
			//	\Epi\getDebug()->addMessage(__CLASS__, sprintf('Call %s : %s : %s : %s', $httpMethod, $route, json_encode($def['callback']), json_encode($arguments)));
			var_dump(json_decode($callback,true)	);
				
		}
		else {
			// show error
		}
		
		
		return $json;
	}
	/**
	 *
	 * @param string $file
	 * @return array
	 */
	static function split_text($file)
	{
		// list of common abbreviations
		$skip_array = array (

				'Jr', 'Mr', 'Mrs', 'Ms', 'Dr', 'Prof', 'Sr' ,
				'jr', 'mr', 'mrs', 'ms', 'dr', 'prof', 'sr' ,

				'col','gen', 'lt', 'cmdr',

				'dept', 'univ',

				'inc', 'ltd',

				'pg',

				'arc', 'al', 'ave', 'cl', 'ct', 'cres', 'dr',
				'la', 'pl', 'plz', 'rd', 'tce',
				'Ala' , 'Ariz', 'Ark', 'Cal', 'Calif', 'Col', 'Colo', 'Conn',
				'Del', 'Fed' , 'Fla', 'Ga', 'Ida', 'Id', 'Ill', 'Ind', 'Ia',
				'Kan', 'Kans', 'Ken', 'Ky' , 'La', 'Me', 'Md', 'Is', 'Mass',
				'Mich', 'Minn', 'Miss', 'Mo', 'Mont', 'Neb', 'Nebr' , 'Nev',
				'Mex', 'Okla', 'Ok', 'Ore', 'Oreg', 'Penna', 'Penn', 'Pa'  , 'Dak',
				'Tenn', 'Tex', 'Ut', 'Vt', 'Va', 'Wash', 'Wis', 'Wisc', 'Wy',
				'Wyo', 'USAFA', 'Alta' , 'Man', 'Ont', 'Que', 'Sask', 'Yuk'.

				'jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec', 'sept',

				'vs', 'etc', 'no', 'e.g' );

		// build string of shortenings
		$skip = '';
		foreach($skip_array as $abbr) {
			$skip = $skip . (empty($skip) ? '' : '|') . '\s{1}' . $abbr . '[.!?]';
		}

		// get text to split from text area
		$txt = $file;

		// split text into sentences
		$lines = preg_split ("/(?<!$skip)(?<=[.?!])\s+(?=[^a-z])/",
				$txt,
				-1,
				PREG_SPLIT_NO_EMPTY);

		return $lines;

	}

	/**
	 *
	 * @return array
	 */
	static public function Debug()
	{
		return array(
				'version' => '0.1',
				'args' => func_get_args(),
				'params' => $_GET,
				'debug' => \Epi\getDebug()->renderJSON()
		);
	}

	/**
	 * Return the list of registered users
	 * @return string
	 */
	static public function Users()
	{
		$json =  (array)json_decode(file_get_contents("data/schema-users.json"),true);
		return $json;
	}


	/*	static public function UserID($user)
	 {
	$json = (array)json_decode(file_get_contents("data/essay.json"));
	$json['user'] = $user;
	//$json['task'] = $task;
	//$json['version'] = $essay;
	return $json;
	}*/

	/**
	 * Return the user profile
	 * @param string $user
	 * @return string
	 */
	static public function UserID($user)
	{
		$json = (array)json_decode(file_get_contents("data/schema-user.json"),true);
		$json['userid'] = $user;
		return $json;
	}

	/**
	 *
	 * @param string $user
	 * @return multitype:Ambigous <>
	 */
	static public function Tasks($user)
	{
		$json = self::UserID($user);
		return $json;
	}

	/**
	 *
	 * @param string $user
	 * @param string $task
	 * @return array
	 */
	static public function TaskID($user,$task)
	{
		$json = (array)json_decode(file_get_contents("data/schema-task.json"),true);
		$json['userid'] = $user;
		$json['taskid'] = $task;
		return $json;
	}

	static public function Essays($user,$task)
	{
		$json = self::TaskID($user,$task);
		return array("essays" => $json['essays']);
	}

	static public function EssayID($user,$task,$essay)
	{
		if (isset($GLOBALS['schema']) || isset($_GET['schema']))
		{
			$json = (array)json_decode(file_get_contents("data/schema-essay.json"),true);
			return $json;
			die();
		}

		$file = file_get_contents("data/TMA01_H810_Submit.txt");

		$order   = array("\r");
		$replace = '';


		$newstr = str_replace($order, $replace, $file);
		$arr = explode("\n",$newstr);





		$emptyRemoved = array_filter($arr);


		$myarray = array();
		$inc = 1; $par = 0;
		foreach ($emptyRemoved as $item)
		{
			$sentences22 = APIController::split_text($item);
			$sentences = array_filter($sentences22);

			$myarray22 = array();

			foreach ($sentences as $item22)
			{
				$myarray[] = array(
						'id' => 'snt_' . str_pad((int) $inc,4,"0",STR_PAD_LEFT),
						'block' => 'par_' . str_pad((int) $par,4,"0",STR_PAD_LEFT),
						'type' => "paragraph",
						'sentence' => $item22);

				//$myarray22[] = array(
				//	'id' => 'par_' . str_pad((int) $inc,4,"0",STR_PAD_LEFT),
				//'text' => $item22);
				$inc++;
			}


			//$myarray[] = array(
			//		'id' => 'par_' . str_pad((int) $inc,4,"0",STR_PAD_LEFT),
			//		'type' => "paragraph",
			//		'sentence' => $myarray22);
			$par++;
		}

		$json = (array)json_decode(file_get_contents("data/essay.json"),true);

		$json['user'] = $user;
		$json['task'] = $task;
		$json['version'] = $essay;
		$json['stats'] = array('wordcount' => str_word_count($file));
		$json['text'] = $myarray;
		return $json;
	}


	static public function EssayFeedback($user,$task,$essay)
	{
			if (isset($GLOBALS['schema']) || isset($_GET['schema']))
		{
			$json = (array)json_decode(file_get_contents("data/schema-feedback.json"));
			return $json;
			die();
		}

		self::getNLTK(null);
		$json = json_decode(file_get_contents("data/feedback.json"),true);

		$json['user'] = $user;
		$json['task'] = $task;
		$json['version'] = $essay;
		return $json;
	}

}
?>
