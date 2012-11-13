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
	 *
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
	 * 
	 */
	static public function Login()
	{
		if (\Epi\getSession()->get(Constants::LOGGED_IN) == true)
		{
			\Epi\getRoute()->redirect('/user');
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

		\Epi\getRoute()->redirect('/user');
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


	static public function Dashboard()
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

	static public function Access()
	{
		// Check if we're already logged in, although we SHOULD never get here
		//if (\Epi\getSession()->get(Constants::LOGGED_IN) == false)
		//{
		//	\Epi\getRoute()->redirect('/login');
		//}
		$apiParams =  \Epi\getApi()->invoke('/user/UID.json');
		$apiTask =  \Epi\getApi()->invoke('/user/UID/task.json');
		$apiEssay =  \Epi\getApi()->invoke('/user/UID/task/UID/essay/UID/feedback.json');
		
		$ff = '<ul class="thumbnails">';
		foreach ($apiTask['tasks'] as $type => $id)
		{
			//var_dump($id);
			$url = "/user/UID/task/$id.json";
			$apiParams =  \Epi\getApi()->invoke($url);
			//var_dump($apiParams);
			$tuis= $apiParams['taskid'];
			$ttitle= $apiParams['title'];
			$tdesc= $apiParams['description'];
			$tdead= $apiParams['deadline'];
			$type = <<<EOL
			<li class="task span3">
                <a href="/me">
                    <h2>$tuis - $ttitle</h2>
                    <h3>$tdead</h3>
                    <p>$tdesc</p>
<div class="pagination pagination-centered">
              <ul>
                <li class="disabled"><span>1</span></li>
                <li class="disabled"><span>1</span></li>
				<li class="inactive"><span>1</span></li>
                <li class="active"><span>1</span></li>
                <li class="active"><span>1</span></li>
             </ul>
            </div>                    		
                </a>	
                    		
             </li>
EOL;
			$ff .= $type;
		}
		$ff .= "</ul>";
		
		
		$template = new \Epi\EpiTemplate();
		$params = array();
		$params['heading'] = 'openEssayist';
		$params['content'] = $ff;
	
		$template->display('openEssayist-template.php', $params);
	}
	
}

?>

