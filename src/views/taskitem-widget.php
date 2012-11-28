<?php 
$today = date("Y-m-d");

$todayt = strtotime($today); 
$expt = strtotime($deadline);

$valid = $expt >= $todayt;
$validtext = ($valid) ? "task open" : "task closed";

?>


<li class="<?php echo $validtext; ?> span3">
<a href="/me/task/<?php echo $id; ?>" title="<?php echo $validtext; ?>">
	<h2><?php echo $task; ?></h2>
	<h3><?php echo $deadline . " " . $valid;?></h3>
	<div><p><?php echo $desc; ?></p></div>
	<div class="pagination pagination-centered">
		<ul>
		<?php
		$limit = $drafts>6; 
		$cart = array();
		$cart[] = "";
		if ($limit)
		{
			$limit = 6;
			for ($i = 1; $i <= $limit-2; $i++)
				$cart[] = "v" + $i;
			$cart[] = "...";
			$cart[] = "v" + $drafts;
		}
		else
		{
			$limit = $drafts;
			for ($i = 1; $i <= $limit; $i++)
				$cart[] = "v" + $i;
		}
		
		for ($i = 1; $i <= $limit; $i++) {
			if ($i == $limit && $valid)
				echo "<li class=\"inactive\"><span>$cart[$i]</span></li>";
			else
				echo "<li class=\"active\"><span>$cart[$i]</span></li>";
		}
		?>
		</ul>
	</div>
</a>
</li>
