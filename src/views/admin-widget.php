<?php 
if (!isset($api)) die();

$inc = 0;
foreach ($api as $test)
{	$inc++;
	$api = $test['api'];
	$output = $test['output'];
	$description = $test['description'];

	echo <<< TMC
<div class="bs-docs-example bs-docs-api" id="example{$inc}">
<p>$api</p>
<p>$description</p>
</div>
<pre class="bs-docs-code"><code class="json">$output</code></pre>

TMC;
}
?>
