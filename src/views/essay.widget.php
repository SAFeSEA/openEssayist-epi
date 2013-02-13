<div class="tab-content">


<div class="tab-pane" id="review">	


<div class="span5">
	<div class="widget">
		<div class="widget-header">
			<i class="icon-question-sign"></i>
				<h3>Statistics</h3>
			</div> <!-- /widget-header -->
			<div class="widget-content">
				<?php arrayTotable("Content", $stats); ?>
				<?php arrayTotable("Structure", $struc_feedback); ?>
				<?php arrayTotable("Spell & Grammar",$metric); ?>								
				</div> <!-- /widget-content -->
				
			</div>
	</div>

	<div class="span7">
		<div class="widget">
					<div class="widget-header">
						<i class="icon-question-sign"></i>
						<h3>Feedback</h3>
					</div> <!-- /widget-header -->
					
					<div class="widget-content">
						
							    <div class="alert alert-info alert-block">
						   			 <button type="button" class="close" data-dismiss="alert">&times;</button>
							        <h4>Warning!</h4>
    Feedbacks, reflective activities and recommendations for action will be presented here.<br>
    Come back later, when the system has been improved.
    </div>					
					</div> <!-- /widget-content -->
				
				</div>
	</div>

</div>


<div class="tab-pane active row-fluid" id="preview">
			<div class="span3 bs-docs-sidebar">
				<div class="nav nav-list bs-docs-sidenav">
				
				<!--<button data-target="#oe-collapse" data-toggle="collapse" class="btn btn-navbar collapsed" type="button">
					<span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> 
				</button>-->
				
					<form id="oe-collapse" action="#">

						<fieldset>
							<legend> Essay </legend>

							<div class="controls controls-row">
								<div class="controls">
									<label class="checkbox inline"> <input type="checkbox"
										value="1" id="cHighlight" onchange="checkBlur(this)"
										> Highlight feedback
									</label>

								</div>
							
							</div>
						</fieldset>

						<fieldset>
							<legend> Structure </legend>

							<div class="controls controls-row">
								<div class="controls">
									<label class="checkbox"> 
										<input  type="checkbox" value="#-s:h#;#-s:t#" id="cStructure1" onchange="checkStructure(this)" checked="checked"> 
										Headings
									</label>
									<label class="checkbox"> 
										<input  type="checkbox" value="#+s:i#" id="cStructure1" onchange="checkStructure(this)" checked="checked"> 
										Introduction
									</label>
									<label class="checkbox"> 
										<input  type="checkbox" value="#+s#" id="cStructure2" onchange="checkStructure(this)" checked="checked"> 
										Body
									</label>
									<label class="checkbox"> 
										<input type="checkbox" value="#+s:c#" id="cStructure3" onchange="checkStructure(this)" checked="checked"> 
										Conclusion
									</label>

								</div>
							
							</div>
						</fieldset>

						<fieldset>
							<legend> Sentences </legend>

							<div class="controls controls-row">
								<div class="control-group">
									<label class="">Show range: <span id="amount" class="input unedidtable-input">0 - 0</span> </label>
									<div id="slider-range"></div>
									<span class="help-block">Set the priority of keywords to
									display.</span> 
								</div>
								
							</div>
						</fieldset>
						
						<fieldset>
							<legend> Keywords </legend>

							<div class="controls controls-row">
								<div class="controls">
									<label class="checkbox inline"> <input type="checkbox"
										value="1" id="cKeyword1" onchange="checkAddress(this)"
										checked="checked"> 1
									</label><label class="checkbox inline"> <input type="checkbox"
										value="2" id="cKeyword2" onchange="checkAddress(this)"
										checked="checked"> 2
									</label><label class="checkbox inline"> <input type="checkbox"
										value="3" id="cKeyword3" onchange="checkAddress(this)"
										checked="checked"> 3
									</label>

								</div>
								<span class="help-block">Set the priority of keywords to
									display.</span> 
							</div>

						</fieldset>
					</form>
				</div>
			</div>

			
			<div class="span9 bs-docs-mainbar"> 
	<div class=" myessay" >	
<?php
$inc = 0;
$inc1 = 0;

//var_dump($parasenttok);

$topsentences = array();

foreach ($parasenttok as $paragraph) {
	$tttt = str_pad((int) $inc, 4, "0", STR_PAD_LEFT);
	echo "<p  id='p$tttt' class='oe-par'>";

	foreach ($paragraph as $sentence) {
		$tttt = str_pad((int) $inc1, 4, "0", STR_PAD_LEFT);

		$ret = array_search($inc1, $ranking, true);

		//var_dump($ranking);
		$str_struct = Null;
		$str_rank = Null;
		if (isset($struct[$inc1])) {
			$str_struct = "data-struct='$struct[$inc1]'";

		}

		$str_id = "id='s$tttt'";
		$str_class = "class='oe-snt'";

		if ($ret !== false) {
			$ret++;
			if ($ret <=15)
				$topsentences[$ret] = $sentence;
			$str_class = "class='oe-snt oe-snt-rank'";
			$str_rank = "data-snt-rank='$ret'";
			//echo " <span $str_id $str_class $str_struct data-snt-rank='$ret'>" .$sentence . "</span>";
		}
		//else
		//	echo " <span $str_id $str_class>" .$sentence . "</span>";
		echo " <span $str_id $str_class $str_struct $str_rank>" . $sentence . "</span>";

		$inc1++;
	}

	$inc++;
	echo "</p>";

}

?>

</div>		
			
			</div>
</div>




<div class="tab-pane" id="keywords">

<div class="row-fluid">
<div class="span3">

	<div class="widget">
		<div class="widget-header">
			<i class="icon-list-alt"></i>
				<h3>Frequency</h3>
		</div> <!-- /widget-header -->
					
		<div class="widget-content">
		<?php NVLprint($freqdist); ?>
		</div> <!-- /widget-content -->
				
	</div>

</div>

<div class="span3">

	<div class="widget">
		<div class="widget-header">
			<i class="icon-list-alt"></i>
				<h3>Keywords</h3>
		</div> <!-- /widget-header -->
					
		<div class="widget-content">
		<?php NVLprint($keywords,1); ?>
		</div> <!-- /widget-content -->
				
	</div>

</div>

<div class="span3">

	<div class="widget">
		<div class="widget-header">
			<i class="icon-list-alt"></i>
				<h3>Phrases (2)</h3>
		</div> <!-- /widget-header -->
					
		<div class="widget-content">
		<?php NVLprint($bigrams,2); ?>
		</div> <!-- /widget-content -->
				
	</div>

</div>

<div class="span3">

	<div class="widget">
		<div class="widget-header">
			<i class="icon-list-alt"></i>
				<h3>Phrases (3)</h3>
		</div> <!-- /widget-header -->
					
		<div class="widget-content">
		<?php NVLprint($trigrams,3); ?>
		</div> <!-- /widget-content -->
				
	</div>

</div>

</div>
<div class="row-fluid">
<div class="span12">

	<div class="widget">
		<div class="widget-header">
			<i class="icon-list-alt"></i>
				<h3>Sentences (top 15)</h3>
		</div> <!-- /widget-header -->
					
		<div class="widget-content">
		
		<table id='sentence' class='table table-striped table-bordered'>
		<tbody>
		<?php 
			ksort($topsentences); 
			foreach ($topsentences as $key => $sentence)
			{ 
				echo "<tr><td>$sentence</td></tr>";
			}
		
		?>
		</tbody>
		</table>
		
		</div> <!-- /widget-content -->
				
	</div>


</div>
</div>

</div>
<div class="tab-pane" id="stats">


<div class="span12">

	<div class="widget">
		<div class="widget-header">
			<i class="icon-list-alt"></i>
				<h3>Keywords</h3>
		</div> <!-- /widget-header -->
					
		<div class="widget-content">
		
		
		<div class="row-fluid">
		
		<div class="span3 " style="">
			<div class="widget-header"><h3>All</h3></div>
			
			<div class="widget-content">
			<ul id="sortable0" class="droptrue connectedSortable">
		
			<?php 
			$tt = array_merge($trigrams,$bigrams);
			foreach (array_slice($tt,0,30) as $key => $item)
			{
				$ft = "".join(" ",$item['ngram']);
				$count = $item['count'];
					
				$count = "<span class=\"label label-info hidden-phone\">$count</span>";
					
				
				echo "<li id='$key' class=\"ui-state-default btn\">$count $ft</li>";
			}
			?>
		
			</ul></div>
		</div>
		
		<div class="span9" style="">
	
		<div class="row-fluid">
		
		<div class="span4"   style="">
			<div class="widget-header"><h3 contenteditable="true">Category 1</h3>
				<div class="box-icon">
					<a class="btn-setting" href="#"><i class="icon-wrench"></i></a>
					<a class="btn-close" href="#"><i class="icon-remove"></i></a>
				</div>
			</div>
			<div class="widget-content"><ul id="sortable1" class="droptrue connectedSortable">
			</ul></div>
		</div>

		
		<div class="span4"   style="">
			<div class="widget-header"><h3>Category 2</h3>
				<div class="box-icon">
					<a class="btn-setting" href="#"><i class="icon-wrench"></i></a>
					<a class="btn-close" href="#"><i class="icon-remove"></i></a>
				</div>
			</div>
			<div class="widget-content"><ul id="sortable2" class="droptrue connectedSortable">
			</ul></div>
		</div>
		
		<div class="span4"   style="">
			<div class="widget-header"><h3>Category 3</h3>
				<div class="box-icon">
					<a class="btn-setting" href="#"><i class="icon-wrench"></i></a>
					<a class="btn-close" href="#"><i class="icon-remove"></i></a>
				</div>
			</div>
			<div class="widget-content"><ul id="sortable3" class="droptrue connectedSortable">
			</ul></div>
		</div>


		
		</div>
		

<div class="row-fluid">

	<div class="span4" style="">
		<div class="widget-header">
			<h3 contenteditable="true">Category 4</h3>
			<div class="box-icon">
				<a class="btn-setting" href="#"><i class="icon-wrench"></i></a> <a
					class="btn-close" href="#"><i class="icon-remove"></i></a>
			</div>
		</div>
		<div class="widget-content">
			<ul id="sortable1" class="droptrue connectedSortable">
			</ul>
		</div>
	</div>


	<div class="span4" style="">
		<div class="widget-header">
			<h3>Category 5</h3>
			<div class="box-icon">
				<a class="btn-setting" href="#"><i class="icon-wrench"></i></a> <a
					class="btn-close" href="#"><i class="icon-remove"></i></a>
			</div>
		</div>
		<div class="widget-content">
			<ul id="sortable2" class="droptrue connectedSortable">
			</ul>
		</div>
	</div>

	<div class="span4" style="">
		<div class="widget-header">
			<h3>Category 6</h3>
			<div class="box-icon">
				<a class="btn-setting" href="#"><i class="icon-wrench"></i></a> <a
					class="btn-close" href="#"><i class="icon-remove"></i></a>
			</div>
		</div>
		<div class="widget-content">
			<ul id="sortable3" class="droptrue connectedSortable">
			</ul>
		</div>
	</div>



</div>		
		
		</div>
		
		</div>
		

		</div> <!-- /widget-content -->
				
	</div>

</div>



</div>

</div>

<div class="modal hide fade" id="myModal">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">x</button>
				<h3>Settings</h3>
			</div>
			<div class="modal-body">
				<p>Here settings can be configured...</p>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Close</a>
				<a href="#" class="btn btn-primary">Save changes</a>
			</div>
</div>

<?php


function arrayTotable($title,$data)
{
	$i18n = array();
	$i18n['comment refs present'] = "references present";
	$i18n['comment intro concl'] = "key sentences (intro. / concl.)";
	
	
	if (!isset($data) || !is_array($data)) return;
	
	echo "<table class=\"table table-striped table-bordered table-condensed\">";
	echo "<caption>$title</caption>";
	echo "<thead>";
	echo "</thead>";
	echo "<tbody>";
	
	foreach ($data as $key => $item)
	{
		if (is_int($key) && is_array($item))
		{
			$first = array_shift($item);
			echo "<tr>";
			echo "	<th>$first</th>";
			foreach ($item as $key2 => $item2)
			{
				echo "	<td>$item2</td>";
			}
			echo "</tr>";
		}
		else {
			$i18nkey = isset($i18n[$key]) ? $i18n[$key] : $key;
			$i18niten = is_array($item) ? "".join(" / ",$item) : $item;
			echo "<tr>";
			echo "	<th>$i18nkey</th>";
			echo "	<td>$i18niten</td>";
			echo "</tr>";
			}
	}
	echo "</tbody>";
	echo "</thead></table>";
	
}

function NVLprint($list,$type=0)
{
	if (!isset($list)) return;
	
	//var_dump($list[0]);
	foreach (array_slice($list,0,50) as $item)
	{
		unset($ft);
		switch ($type) {
			case 0: 
				// simple keyword 
				$ft = $item;
				break;
			case 1:
			case 2:
			case 3:
				// bigrams
				$count = $item['count'];
				$ngram = $item['ngram'];
				$score = $item['score'];
				//foreach ($score as &$value) 
			//		$value = round($value*100);
				$score2 = $score;
				$score = "".join(",",$score)."";
					
				
				$bullet1 = $score2[0] . ",1,1,.75,.5";
				$bullet2 = $score2[1] . ",1,1,.75,.5";
				$ft = "".join(" ",$item['ngram']);
				$ft = "<span class=\"label label-info hidden-phone\">$count</span> " . $ft;
				$ft .= "<span class=\"inlinebar\">$score</span>"; 
				//$ft .= "<span class=\"sparkbullet \">$bullet1</span>"; 
				//$ft .= "<span class=\"sparkbullet \">$bullet2</span>"; 
				break;
			default:
				;
			break;
		}
		
		echo "<p>$ft</p>";
	}
}

?>