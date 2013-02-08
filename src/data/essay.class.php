<?php namespace openEssayist;

class Essay extends \ArrayObject
{   
    public function __set($name, $val) {
        $this[$name] = $val;
    }

    public function __get($name) {
        return $this[$name];
    }
    
    public function keys() {
    	return array_keys((array)$this);
    }
    
    /**
     * 
     * @return string
     */
    public function getFullText()
    {
    	$parasenttok = $this->parasenttok;
		$tt = array();
		foreach ($parasenttok as $idx1 => $par)
		{	
			$tt[] = "".implode(" ",$par);
		}
		return "".implode(PHP_EOL,$tt);
    }
}
