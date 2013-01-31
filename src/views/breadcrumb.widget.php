<?php 
$alltask = '/user/UID/task/' . $task . '/essay.json';

$apiTask22 =  \Epi\getApi()->invoke($alltask);
//var_dump($apiTask22['essays']);
$mapdraft = array();
foreach ($apiTask22['essays'] as $id => $item)
{
	$mapdraft[$item['ref']] = $item['id'];
}
//var_dump($mapdraft);

?>

<ul class="breadcrumb">
	    <li><div class="btn-group">
    		<a class="btn" href="/me"><i class="icon-home"></i></a>
    	</div></li>
    	
    	<li><div class="btn-group">
    		<a class="btn" href="/me/task"><i class="icon-book"></i><span class="hidden-phone hidden-tablet">  Assignments</span></a>
    	</div></li>
	    <li><div class="btn-group">
    		<a class="btn" href="/me/task/<?php echo $task?>"><i class="icon-pencil"></i><span class="hidden-phone hidden-tablet">  Essay</span></a>
 	    	<?php if (isset($task)) { ?>
   			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><?php if (isset($essay)) { ?><code><?php echo $mapdraft[$essay]?></code> <?php }?><span class="caret"></span></a>
    		<ul class="dropdown-menu">
    		<li class="nav-header">Versions</li>
    		
    		<?php foreach ($mapdraft as $id => $item) {
    			if ($id==$essay) echo "<li class=\"active\">";
    			else echo "<li class=\"\">";
    		?>
    			<a tabindex="-1" href="/me/task/<?php echo $task?>/essay/<?php echo $id?>"><code><?php echo $item ?></code></a></li>
    		<?php }?>
    		
		        <li class="divider"></li>
		        <li><a tabindex="-1" href="/me/task/<?php echo $task?>/submit">Add a new version...</a></li>
  			 </ul>
  			 <?php } ?>
    	</div></li>

    	<?php if (isset($essay) && !isset($graph)) { ?>
    	<li class="">
    		<div class="btn-group " data-toggle="buttons-radio"  id="mytabs">
    			<a class="btn active" href="#review">
    				<i class="icon-warning-sign"></i>
    				<span class="hidden-phone"> Review</span>
    				<!-- <span class="label label-important hidden-phone"> 2</span>
					<span class="label label-success hidden-phone">11</span> -->
				</a>
    			<a class="btn" href="#preview"><i class="icon-fullscreen"></i><span class="hidden-phone"> Mash-up</span></a>
    			<a class="btn" href="#keywords"><i class="icon-list-alt"></i><span class="hidden-phone"> Extraction</span></a>
    			<a class="btn" href="#stats"><i class="icon-tasks"></i><span class="hidden-phone"> Reflection</span></a>
    	</div></li>

	    <li><div class="btn-group">
    		<a class="btn" href="/me/task/<?php echo $task?>/essay/<?php echo $essay?>/dispersion"><i class="icon-picture"></i><span class="hidden-phone hidden-tablet">  Graphs</span></a>
   			<a class="btn dropdown-toggle" data-toggle="dropdown"  href="#"><span class="caret"></span></a>
    		<ul class="dropdown-menu">
		        <li><a tabindex="-1" href="/me/task/<?php echo $task?>/essay/<?php echo $essay?>/dispersion">Dispersion</a></li>
    		  	<li><a tabindex="-1" href="/me/task/<?php echo $task?>/essay/<?php echo $essay?>/adjacency">Adjacency</a></li>
    		  			 </ul>
   			    	</div></li>
    	
    	<?php } ?>
    	
      	<?php if (isset($essay) && isset($graph)) { ?>
    	<li class="">
    		<div class="btn-group " data-toggle="buttons-radio"  id="mytabs">
    			<a class="btn" href="/me/task/<?php echo $task?>/essay/<?php echo $essay?>">
    				<i class="icon-warning-sign"></i>
    				<span class="hidden-phone"> Review</span>
    				<!-- <span class="label label-important hidden-phone"> 2</span>
					<span class="label label-success hidden-phone">11</span> -->
				</a>
    	</div></li>
	    <li><div class="btn-group">
    		<a class="btn active" href="/me/task/<?php echo $task?>/essay/<?php echo $essay?>/dispersion"><i class="icon-picture"></i><span class="hidden-phone hidden-tablet">  Graphs</span></a>
   			<a class="btn dropdown-toggle" data-toggle="dropdown"  href="#"><span class="caret"></span></a>
    		<ul class="dropdown-menu">
		        <li><a tabindex="-1" href="/me/task/<?php echo $task?>/essay/<?php echo $essay?>/dispersion">Dispersion</a></li>
    		  	<li><a tabindex="-1" href="/me/task/<?php echo $task?>/essay/<?php echo $essay?>/adjacency">Adjacency</a></li>
    		  			 </ul>
    	</div></li>
    	<?php } ?>
    	
    	
	</ul>
