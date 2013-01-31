<?php namespace openEssayist;

include_once 'controller.php';


function odd($var)
{
	// returns whether the input integer is odd
	return($var != '' && $var != " ");
}

/**
 *
 * @author Nicolas Van Labeke (https://github.com/vanch3d)
 *
 */
class APIController extends IController
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
			//self::debug(sprintf('Call %s : %s : %s : %s', $httpMethod, $route, json_encode($def['callback']), json_encode($arguments)));
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
	static public function Version()
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
	
	function Truncate($string, $length, $stopanywhere=false) {
		//truncates a string to a certain char length, stopping on a word if not specified otherwise.
		if (strlen($string) > $length) {
			//limit hit!
			$string = substr($string,0,($length -3));
			if ($stopanywhere) {
				//stop anywhere
				$string .= '...';
			} else{
				//stop on a word.
				$string = substr($string,0,strrpos($string,' ')).'...';
			}
		}
		return $string;
	}

	/**
	 * Return the user profile
	 * @param string $user
	 * @return string
	 */
	static public function UserID($user)
	{		
		$temp_oa_dir = self::getTempDir();
		//self::debug('$temp_oa_dir => ' .  print_r($temp_oa_dir,true));
		
		
		$dirs = array_filter(glob($temp_oa_dir . DIRECTORY_SEPARATOR . '*'), 'is_dir');
		if (empty($dirs))
		{
			$res = mkdir($temp_oa_dir . '/h810_tma01');
		}
		
		$json = (array)json_decode(file_get_contents("data/schema-user.json"),true);
		$json['userid'] = $user;
		
		
		$tasks = array();
		foreach ($dirs as $dir)
		{
			//var_dump($dir);
			$glob = $dir . DIRECTORY_SEPARATOR . '*.txt';
			//var_dump($glob);
			$files = array_filter(glob($dir . DIRECTORY_SEPARATOR . '*.txt'), 'is_file');
			//var_dump($files);
			//self::debug('$files => ' .  print_r($files,true));
				
				
			$id = basename($dir);
			$name = explode("_", $id);
			$name = strtoupper("".join(" - ",$name));
			$tasks[] = array(
					'id'=> $id,
					'task' => $name,
					'deadline' => '2012-10-08',
					'drafts' => count($files),
					'title' => 'no title',
					'desc' => 'no description'
			);
		}
		$json['tasks'] = $tasks;
		
		//$ret = file_put_contents("data/schema-user.json", json_encode($json));
		//var_dump($json);
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
		$json22 = (array)json_decode(file_get_contents("data/schema-alltasks.json"),true);

		$json = $json22[$task];
		$temp_oa_dir = self::getTempDir($task);
		//$dir = $temp_oa_dir . "/" . $task;
		
		//var_dump($temp_oa_dir);
		$glob = $temp_oa_dir . DIRECTORY_SEPARATOR . '*.txt';
		//var_dump($glob);
		$files = array_filter(glob($glob), 'is_file');
				
		$json['userid'] = $user;
		$json['taskid'] = $task;

		
		$drafts = array();
		$inc = 0;
		foreach ($files as $file)
		{
			$id = basename($file,'.txt');
			//var_dump($id);
			
			$drafts[] = array(
					'id'=> 'v' . (++$inc),
					'ref' => $id,
					'desc' => ''
			);
		}
		$json['essays'] = $drafts;
		//var_dump($json);
		
		return $json;
	}

	static public function Essays($user,$task)
	{
		$json = self::TaskID($user,$task);
		//return array("essays" => $json['essays']);
		return $json;
	}
	
	

	static public function EssayID($user,$task,$essay)
	{
		if (isset($GLOBALS['schema']) || isset($_GET['schema']))
		{
			$json = (array)json_decode(file_get_contents("data/schema-essay.json"),true);
			return $json;
			die();
		}
		
		$temp_oa_dir = self::getTempDir($task);
		$file = $temp_oa_dir . '/' . $essay . '.txt';
		
		
		$json = (array)json_decode(file_get_contents($file),true);
		
		
		$json['userid'] = $user;
		$json['taskid'] = $task;
		
		return $json;
		
		die();

		/*$file = file_get_contents("data/TMA01_H810_Submit.txt");
		$order   = array("  ");
		$replace = ' ';


		$newstr = str_replace($order, $replace, $file);
		$arr = explode("\n",$newstr);

		//var_dump($arr);
		



		$emptyRemoved = array_filter($arr);


		$myarray = array();
		$inc = 1; $par = 0;
		foreach ($emptyRemoved as $item)
		{
			$sentences22 = APIController::split_text($item);
			$sentences = array_filter($sentences22,"openEssayist\odd");
			//var_dump($sentences22);
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

		//var_dump($myarray);
		$json = (array)json_decode(file_get_contents("data/essay.json"),true);

		$json['user'] = $user;
		$json['task'] = $task;
		$json['version'] = $essay;
		$json['stats'] = array('wordcount' => str_word_count($file));
		$json['text'] = $myarray;
		return $json;*/
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
