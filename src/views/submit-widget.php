<?php if (!empty($error))
{ 
	echo <<<EOF
	<div class="row-fluid">
		<div class="span9">
EOF;
	
	foreach ($error as $item)
	{
		$code = $item['code'];
		$msg = $item['msg'];
		echo <<<EOF
			<div class="alert alert-block alert-error">
  				<button type="button" class="close" data-dismiss="alert">&times;</button>
  				<h4>Error ($code)</h4>
				$msg
			</div>
EOF;
		
	}
	echo <<<EOF
		</div>
	</div>
EOF;
} 
?>

<div class="row-fluid">

<div class="span9">

	<div class="widget">
		<div class="widget-header">
			<i class="icon-pencil"></i>
			<h3>Submit a new draft</h3>
		</div> <!-- /widget-header -->
					
	<div class="widget-content">

<form name="post_essay" method="post" class="form-horizontal">

<fieldset><div class="control-group">											
	<label for="username" class="control-label">Username</label>
	<div class="controls">
		<input type="text" value="Username" id="username" name="username" readOnly  class="uneditable-input">
	</div> <!-- /controls -->				
</div>
</fieldset>									

<fieldset><div class="control-group">											
	<label for="module" class="control-label">Assignment</label>
	<div class="controls">
		<input type="text" name="module" id="module" value="<?php echo $task; ?>" readOnly  class="uneditable-input">
	</div> <!-- /controls -->				
</div>
</fieldset>									

<fieldset><div class="control-group">											
	<label for="version" class="control-label">Version</label>
	<div class="controls">
		<input type="text" name="version" id="version" value="<?php echo $version; ?>" readOnly  class="uneditable-input">
	</div> <!-- /controls -->				
</div>
</fieldset>		

<fieldset><div class="control-group">
	<label for="text" class="control-label">Text</label>
	<div class="controls">
		<textarea style="max-width: 90%; width: 90%; resize: vertical;" rows="15" 
			name="text" id="text" placeholder="paste the text of your essay"
			required="required"><?php if (!empty($text)) echo $text; ?></textarea>
			<p class="help-block">Copy and paste the complete text of your essay (it will be cleaned)</p>
		</div>
</div></fieldset>

<div class="form-actions">
	<button class="btn btn-primary" type="submit" name="action" value="Save">Save</button> 
	<button class="btn" type="submit" name="action" value="Cancel">Cancel</button> 
</div>
								
</form>
	
	</div> <!-- /widget-content -->
</div><!-- /widget -->
	
</div>
<div class="span3">

	<div class="widget">
					<div class="widget-header">
						<i class="icon-question-sign"></i>
						<h3>Help</h3>
					</div> <!-- /widget-header -->
					
					<div class="widget-content">
						
					</div> <!-- /widget-content -->
				
				</div>
</div>

</div>
