	<header class="subhead">
		<h3>
			<?php echo $title; ?>
		</h3>
		<div><blockquote>
			<?php echo implode(" ", (array)$definition) ; ?>
		</blockquote></div>
		</header>

<div class="myessay">		
			<?php
			
			$curblock = Null;
			
			$inc =0;
			$num = Null;
			
			foreach ($text as $item2)
			{
				$item = (array)$item2;
				
				if (isset($item['block']))
				{
					if (isset($curblock))
					{
						if ($curblock != $item['block'])
						{
							echo "</div>";
							$curblock = $item['block'];
							echo "<div  id=$curblock class='oe-par' style='margin-botton: 10px;'>";
						}
					}
					else
					{
						$curblock = $item['block'];
						echo "<div id=$curblock class='oe-par' style='margin-botton: 10px;'>";
					}
				}
				//echo "<div id=".$item['id'].">";
				if ($item['type'] == "heading")
					echo "<h3>" . $item['text'] . "</h3>";
				else if ($item['type'] == "feedback")
				{
					$args= Null;
					if (isset($item['params'])) 
					{
						$num = $item['params'];
						
						$oo = 0;
						$args= '<br>';
						foreach ($item['params'] as $itemX)
						{
							$oo++;
							$args .= "<span class='badge badge-info'>$oo</span><span class='badge'>$itemX</span> ";
						}
					}
					//print_r($item['description']);
					$sfstr = vsprintf ($item['description'],$item['params']);
					//print_r($sfstr);
					$str = <<<EOD
					    <div class="alert alert-{$item['status']} alert-nvl" id="feed{$item['itemid']}">
					    <button type="button" class="close" data-dismiss="alert">X</button>
					    <h4> {$item['title']}</h4>
					   {$sfstr}{$args}
					    </div>
EOD;
					
					echo $str;
				}
				else
				{
					//print_r ($item['sentence']);
					$curstn = $item['id'];
					
					//var_dump($num);
						
					$ret = array_search($curstn,$num,true);
					if ($ret !== false)
					{
						$ret++;
						echo " <span class='badge badge-info'>$ret</span><span id=$curstn class='label oe-snt'>" . $item['sentence'] . "</span>";
					}
					else
						echo " <span id=$curstn class='hidden-phone oe-snt'>" . $item['sentence'] . "</span>";
				}

				//echo "</div>";
			}
			if (isset($curblock))
				{
					echo "</div>";
				}
				
			?></div>

		<div class="form-actions">
			<button class="btn btn-primary" type="reset">Check feedback</button>
			<button class="btn" type="reset">Cancel</button>
		</div>
