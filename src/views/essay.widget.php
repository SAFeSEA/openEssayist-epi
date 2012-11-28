<div class="">	

<?php 
$inc =0;
$inc1 =0;

//var_dump($parasenttok);

foreach ($parasenttok as $paragraph)
{
	
	
	$tttt = str_pad((int) $inc,4,"0",STR_PAD_LEFT);
	echo "<div  id='$tttt' class='oe-par' style='margin-botton: 10px;'>";

	foreach ($paragraph as $sentence)
	{
		$tttt = str_pad((int) $inc1,4,"0",STR_PAD_LEFT);
		
		$ret = array_search($inc1,$ranking,true);
		if ($ret !== false)
		{
			$ret++;
			echo " <span class='badge badge-info'>$ret</span><span id=$tttt class='label oe-snt'>" . $sentence . "</span>";
		}
		else
			echo " <span id=$tttt class='oe-snt'>" .$sentence . "</span>";
		
		
		$inc1++;
	}
	
	$inc++;
	echo "</div>";
	
}


?>

</div>