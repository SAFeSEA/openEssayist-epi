<div class="row-fluid">
	<div class="span6">
		<div class="widget">
					
					<div class="widget-header">
						<i class="icon-question-sign"></i>
						<h3>Task</h3>
						
						
					</div> <!-- /widget-header -->
					
					<div class="widget-content">
						
						<?php if (!empty($description)) foreach ($description as $desc) echo "<p>$desc</p>"; ?>
						
					</div> <!-- /widget-content -->
				
				</div>
	</div>
	
	
	<div class="span3">
		<div class="widget">
			<div class="widget-header">
				<i class="icon-calendar"></i>
				<h3>Deadline</h3>
			</div> <!-- /widget-header -->
					

			<div class="widget-content"> <?php if (!empty($deadline)) echo $deadline; ?>
			</div> <!-- /widget-content -->
				
		</div>
	</div>
		
	<div class="span3">
		<div class="widget">
			<div class="widget-header">
				<i class="icon-check"></i>
				<h3>Word limit</h3>
			</div> <!-- /widget-header -->
					

			<div class="widget-content"> <?php if (!empty($metadata['wordcount'])) echo $metadata['wordcount']; ?>
			</div> <!-- /widget-content -->
				
		</div>
	</div>	
	
		
</div>
				
<div class="widget widget-table action-table">
						
<div class="widget-header">
	<i class="icon-th-list"></i>
	<h3>History of drafts (<?php echo count($essays); ?>)</h3>
</div> <!-- /widget-header -->
					
<div class="widget-content">

						
<table class="table table-striped table-bordered">
<thead>
	<tr>
		<th width="5%">Version</th>
		<th>Description</th>
		<th>Word count</th>
		<th>Keywords</th>
				<th>Review</th>
				<th class="td-actions">Actions</th>
	</tr>
</thead>

<tbody>
<?php

foreach ($essays as $index => $item) {
	$id = $item['id'];
	$ref = $item['ref'];
	$desc = empty($item['desc']) ? '&nbsp;' : $item['desc'];
	$stats = $item['stats']['words'];
	$metrics = $item['metrics'];
	$dd = array();
	//var_dump($item['kwords']);
	foreach ($item['kwords'] as $item)
	{
		//$temp = "" . join(" ", $item);
		$count = $item['count'];
		$temp ="<span class=\"label label-info hidden-phone\">$count</span> ";
		
		$text = "".join(" ",$item['ngram']);
		$temp .= " ".$text;
		
		$url = '/me/task/' . $taskid . '/keyword?' . http_build_query(array('word' => $text));
		$temp = "<a class=\"oe-keyword btn\" href=\"$url\">$temp</a>";
		$dd[] = $temp;
	}
	$kword = "" . join(" ", $dd);

	$url = '/me/task/' . $taskid . '/essay/' . $ref;
	
	$success =  rand(4, 10);
	$warning =  rand(0, 5);
	$error =  rand(0, 3);
	
	$islast = ((count($essays) - $index) == 1);

	if ($islast)
		echo "<tr class=\"warning\">";
	else
		echo "<tr>";
		
	echo "<td><code>$id</code></td>";
	echo "<td>$desc</td>";
	echo "<td>$stats</td>";
	echo "<td>$kword</td>";
	echo "<td >";
	
		//if ($error != 0) echo "<span class=\"label label-important\"> $error</span>";
		//if ($warning != 0) echo "<span class=\"label label-warning\"> $warning</span>";
		//if ($success != 0) echo "<span class=\"label label-success\"> $success</span>";
		if ($islast)
			echo "<br><span class=\"label label-important\">Pending</span>";
		else 
			echo "<br><span class=\"label label-success\">Done</span>";
	echo "</td>";
	
	echo <<<EGO
	<td class="td-actions">
		<a href="$url" class="btn btn-small btn-success ">
			<i class="btn-icon-only icon-zoom-in"></i>										
		</a>
		<!--<a href="javascript:;" class="btn btn-small btn-warning">
			<i class="btn-icon-only icon-trash"></i>										
		</a>-->
EGO;

	echo "</tr>";
}
?>

</tbody>
</table>
						
					</div> <!-- /widget-content -->
				
				</div> <!-- /widget -->
									
<p>
<a href="<?php echo $_REQUEST['__route__'] ?>/submit" class="btn btn-large btn-primary">Submit a new draft...</a>
</p>
