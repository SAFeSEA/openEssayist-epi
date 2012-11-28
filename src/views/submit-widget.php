<form name="post_essay" method="post" class="form">
	<fieldset>
		<legend>Information:</legend>
		User: <input type="text" size="30" name="username"
			placeholder="your name" required="required" value="this is my name" disabled="disabled"><br>
	</fieldset>
	<fieldset>
		<legend>Essay</legend>
		<label for="module" class="control-label">Assignment:</label>
		<input type="text" size="30" name="module"
			placeholder="TMA or EMA code" required="required" value="<?php echo $task; ?>" disabled="disabled"><br>
		<label for="text" class="control-label">Text:</label>
			<textarea style="max-width: 90%; width: 90%" rows="10" cols="60"
			name="text" placeholder="paste the text of your essay"
			required="required"></textarea>
			
	</fieldset>
	<fieldset>
		<legend>Actions</legend>
		<input type="submit" name="operation"
			onclick="document.pressed=this.value" value="Analysis" /> <input
			type="submit" name="operation" onclick="document.pressed=this.value"
			value="Dispersion"  />
	</fieldset>
</form>
