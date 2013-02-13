<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>openEssayist - <?php echo $heading; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="/bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
<link href="/bootstrap/css/docs.css" rel="stylesheet">

<link href="/bootstrap/jquery-ui-1.9.2.custom/css/bootstrap/jquery-ui-1.9.2.custom.css" rel="stylesheet">
	
<?php if (isset($injectCSS)) echo $injectCSS; ?>
	

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="/bootstrap/ico/favicon.ico">
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar">

	<!-- Navbar
    ================================================== -->
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container-fluid">
				<button type="button" class="btn btn-navbar" data-toggle="collapse"
					data-target=".nav-collapse">
					<span class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>

				<a class="brand" href="/"><img class="brand-ico"
					src="/bootstrap/img/openEssayist-icon.png">openEssayist</a>

				<?php if ($username) : ?>
				<div class="btn-group pull-right">
					<a class="btn dropdown-toggle" data-toggle="dropdown"
						title="dfsdf sdfs df sadf sdf sad" href="#"> <i class="icon-user"></i>
						<?php echo $username ?> <span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<li><a href="#">Profile</a></li>
						<li class="divider"></li>
						<li><a href="/logout">Sign Out</a></li>
					</ul>
				</div>



				<div class="nav-collapse collapse">
					<ul class="nav">
						<li class=""><a href="/me" rel="tooltip" data-placement="bottom"
							title="Overview of your essays and feedback"><i
								class="icon-tasks"></i> Dashboard</a>
						</li>
						
						<?php if($admin) : ?>
						<li class="dropdown"><a href="#" class="dropdown-toggle"
							data-placement="bottom" rel="tooltip"
							title="Restricted access to administrative tools"
							data-toggle="dropdown"><i class="icon-cog"></i> Admin<b class="caret"></b>
						</a>
							<ul class="dropdown-menu">
								<li class="nav-header">Configuration</li>
								<li><a href="/admin">Administation</a></li>
								<li class="divider"></li>
								<li class="nav-header">Documentation</li>
								<li><a href="/admin/api">RESTful APIs</a></li>
								<li><a href="/admin/service">Web Services</a></li>
							</ul>
						</li>
						<?php endif;?>
					</ul>
				</div>
				<?php endif; ?>

			</div>
		</div>
	</div>

	<!-- Subhead
================================================== -->
	<header class="subdhead" id="overview"> </header>


	<div class="container">
	
	<?php if (isset($breadcrumb)) echo $breadcrumb; ?>

		<!-- Docs nav
    ================================================== -->
		<div class="row-fluid">


			<?php
			if (isset($twocolumn)) {

				if (isset($subcontent))
				{
					//echo print_r($subcontent);
					echo "<div class=\"span3 bs-docs-sidebar\">";

					if (is_array($subcontent))
					{
						echo "<ul class=\"nav nav-list bs-docs-sidenav\">";
						foreach ($subcontent as $item)
						{
							$str = ($item['status']=='error')?important:$item['status'];
							echo "<li><a href=\"#feed{$item['itemid']}\"><i class=\"icon-chevron-right\"></i>
							<span class=\"badge badge-{$str}\">&nbsp;</span> {$item['title']}</a></li>";
						}
						echo "</ul>";
					}
					else
						echo $subcontent;
					echo "</div>";
				}
			?>


			<div class="span9">

				<?php
				echo $content;
			}
			else
			{
				echo "<div class=\"span12\">";
				echo $content;

			};?>
			</div>
		</div>
		<?php
		if(\Epi\Epi::getSetting('debug'))
		{
			$ff = \Epi\getDebug()->renderAscii();
			if (!empty($ff))
			{
				echo "<div class=\"row-fluid\">";
				echo "<div class=\"span12\">";
				echo "<div class=\"panel bs-docs-debug\"><pre>";
				echo $ff;
				echo "</pre></div>";
				echo "</div>";
				echo "</div>";
			}
		}
		?>
	</div>





	<!-- Footer
    ================================================== -->
	<footer class="footer">
		<div class="container">
			<p class="pull-right">
				<a href="#">Back to top</a>
			</p>
			<p>
				Designed and built with all the love in the world by <a
					href="https://github.com/vanch3d" target="_blank"><img
					src="/bootstrap/img/github.jpg">vanch3d</a>.
			</p>
			<p>
				Code licensed under <a
					href="http://www.apache.org/licenses/LICENSE-2.0" target="_blank">Apache
					License v2.0</a>, documentation under <a
					href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a>.
			</p>
			<ul class="footer-links">
				<li><a href="http://blog.getbootstrap.com">Blog</a></li>
				<li class="muted">&middot;</li>
				<li><a href="https://github.com/SAFeSEA/openEssayist-epi/wiki">Documentation</a>
				</li>
			</ul>
		</div>
	</footer>



	<!-- Le javascript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script
		src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="/bootstrap/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js"></script>
	<script src="/bootstrap/js/bootstrap.js"></script>
	<script src="/bootstrap/openEssayist.js"></script>
	<?php echo $injectJS; ?>


</body>
</html>
