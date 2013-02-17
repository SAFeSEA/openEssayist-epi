<?php namespace Epi;

/**
 * 
 * @author  Jaisen Mathai <jaisen@jmathai.com>
 *
 */
class EpiDebug
{
  private static $modules = array('EpiRoute');
  private $messages = array();

  public function addMessage($module, $message)
  {
    $this->messages[$module][] = $message;
  }

  public function renderAscii()
  {
    $rowWidth = 100;
    $col1Width = $rowWidth - 4;
    //$out = "\n" . str_repeat('*', $rowWidth) . "\n";
    $groups = array();
    foreach($this->messages as $module => $messages)
    {
      $out .= str_repeat('~', ($rowWidth/2)-((strlen($module)+2)/2)) . " {$module} " . str_repeat('~', ($rowWidth/2)-((strlen($module)+2)/2)) . "\n";
      foreach($messages as $message)
      {
      	$colsize = max(0,$col1Width-strlen($message));
        //$out .= '| ' . $message . str_repeat(' ', $colsize) . " |\n";
        $out .= '' . $message . str_repeat(' ', $colsize) . "\n";
      }
    }
    return $out;
  }
  
  /**
   * 
   * @author Nicolas Van Labeke (https://github.com/vanch3d)
   * @return string
   */
  public function renderJSON()
  {
  	$groups = array();
  	foreach($this->messages as $module => $messages)
  	{
  		foreach($messages as $message)
  		{
  			$groups[] = array(
  					"module" => ($module),
  					"message" => ($message)
  					);
  		}
  	}
  	return $groups;
  }
  
}

function getDebug()
{
  static $debug;
  if(!$debug)
    $debug = new EpiDebug();

  return $debug;
}
