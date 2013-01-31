<?php namespace openEssayist;

include_once 'controller.php';


class SwaggerController extends IController
{
	static public function APIs()
	{
		$json = json_decode(file_get_contents("apis/api-openessayist.json"),true);
		//var_dump($json);
		return $json;
		
	}
}