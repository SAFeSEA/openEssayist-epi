
<div class="row-fluid">
	<div class="widget">
		<div class="widget-content">
			<button id="b1" type="button" class="btn">GET</button>
			<button id="b2" type="button" class="btn">POST</button>
			<button id="b3" type="button" class="btn">PUT</button>
			<button id="b4" type="button" class="btn">DELETE</button>
		</div>
		<!-- /widget-content -->
	</div>
</div>

<div class="row-fluid">

	<div class="span8">
		<div class="widget">
			<div class="widget-header">
				<i class="icon-question-sign"></i>
				<h3>Services</h3>
			</div>
			<!-- /widget-header -->
			<div class="widget-content">
				<table class="table table-striped table-bordered table-condensed">

					<thead>
						<tr>
							<th>Service</th>
							<th>Server</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>

						<?php foreach ($config->services as $ff)
						{
							$rr = filter_var($config->{$ff}->server,FILTER_VALIDATE_URL);
							echo "<tr><td>$ff</td>";
							echo "<td>$rr</td>";
							echo "<td><div class=\"btn btn-mini\" data-loading-text=\"Testing ...\" data-service=\"http://localhost:8062/\" type=\"button\" >...</div></td>";
							echo "</tr>";
					} ?>


					</tbody>
				</table>
			</div>
			<!-- /widget-content -->

		</div>
	</div>


	<div class="span4">
		<div class="widget">
			<div class="widget-header">
				<i class="icon-question-sign"></i>
				<h3>System Administration</h3>
			</div>
			<!-- /widget-header -->
			<div class="widget-content">
				<table class="table table-bordered table-condensed">
					<caption>Administrator</caption>
					<thead>
						<tr>
							<th>username</th>
							<th>password</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th><code>admin</code></th>
							<td><code>
									<?php echo \Epi\getConfig()->get('admin'); ?>
								</code></td>
						</tr>
					</tbody>
				</table>

				<table class="table  table-bordered table-condensed">
					<caption>Temporary Directory</caption>
					<thead>
						<tr>
							<th>Path</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><code>
									<?php echo $tempdir; ?>
								</code></td>
							<td><btn class="btn btn-mini">Clear</btn></td>
						</tr>
					</tbody>
				</table>

			</div>
			<!-- /widget-content -->

		</div>
	</div>

</div>
