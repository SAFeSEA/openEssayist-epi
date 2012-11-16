<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo $heading; ?> - openEssayist</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="/bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
<link href="/bootstrap/css/docs.css" rel="stylesheet">
<link href="/bootstrap/google-code-prettify/prettify.css"
	rel="stylesheet">


	

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
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<button type="button" class="btn btn-navbar" data-toggle="collapse"
					data-target=".nav-collapse">
					<span class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				
				<a class="brand" href="/"><img class="brand-ico" src="/bootstrap/img/openEssayist-icon.png">openEssayist</a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li class=""><a href="/">Home</a>
						</li>
						<li class=""><a href="/me">Dashboard</a>
						</li>
						              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li class="nav-header">Configuration</li>
                <li><a href="#">Administation</a></li>
                  <li class="divider"></li>
                  <li class="nav-header">Documentation</li>
                  <li><a href="/admin/api">RESTful APIs</a></li>
                  <li><a href="/admin/service">Web Services</a></li>
                </ul>
              </li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<!-- Subhead
================================================== -->
	<header class="subdhead" id="overview">
		<div class="container"></div>
	</header>


	<div class="container">

		<!-- Docs nav
    ================================================== -->
		<div class="row-fluid">


			<?php
			if (isset($twocolumn)) {

				if (isset($subcontent))
				{
					//echo print_r($subcontent);
					echo "<div class=\"span3 bs-docs-sidebar\">";
					echo "<ul class=\"nav nav-list bs-docs-sidenav\">";
					
					foreach ($subcontent as $item)
					{
						$str = ($item['status']=='error')?important:$item['status'];
						echo "<li><a href=\"#feed{$item['itemid']}\"><i class=\"icon-chevron-right\"></i>
							<span class=\"badge badge-{$str}\">&nbsp;</span> {$item['title']}</a></li>";
					}
					
					echo "</ul></div>";
				}
			?>
			<!-- <div class="span3 bs-docs-sidebar">
				<ul class="nav nav-list bs-docs-sidenav">
					<li><a href="#typography"><i class="icon-chevron-right"></i>
							Typography</a></li>
					<li><a href="#code"><i class="icon-chevron-right"></i> Code</a></li>
					<li><a href="#tables"><i class="icon-chevron-right"></i> Tables</a>
					</li>
					<li><a href="#forms"><i class="icon-chevron-right"></i> Forms</a></li>
					<li><a href="#buttons"><i class="icon-chevron-right"></i> Buttons</a>
					</li>
					<li><a href="#images"><i class="icon-chevron-right"></i> Images</a>
					</li>
					<li><a href="#icons"><i class="icon-chevron-right"></i> Icons by
							Glyphicons</a></li>
				</ul>
			</div> -->

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
					href="https://github.com/vanch3d" target="_blank"><img src="/bootstrap/img/github.jpg">vanch3d</a>.
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
				<li><a href="https://github.com/SAFeSEA/openEssayist-epi/wiki">Documentation</a></li>
			</ul>
		</div>
	</footer>



	<!-- Le javascript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="/bootstrap/js/bootstrap.js"></script>
	<script src="/bootstrap/google-code-prettify/prettify.js"></script>
		<script src="/bootstrap/openEssayist.js"></script>
	

</body>
</html>
