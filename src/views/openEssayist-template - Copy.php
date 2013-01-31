<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo $heading; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
<style>

body {
	padding-top: 60px;
	/* 60px to make the container go all the way to the bottom of the topbar */
}

img.logo-img {
	height: 34px;
	margin: -18px 10px -15px 0;
}

.navbar-text {
	margin-right: 10px;
}

.sidebar-nav-fixed {
	position: fixed;
	left: 20px;
	/*top: 60px;*/
}

.sidebar2-nav-fixed {
	position: fixed;
	left: 20px;
	top: 466px;
}
.row-fluid>.span-fixed-sidebar {
	margin-left: 290px;
}

.bs-docs-api {
	background-color: #FFFFFF;
	border: 1px solid #DDDDDD;
	border-radius: 4px 4px 4px 4px;
	margin: 15px 0;
	padding: 39px 19px 14px;
	position: relative;
}

.bs-docs-api:after {
	background-color: #F5F5F5;
	border: 1px solid #DDDDDD;
	border-radius: 4px 0 4px 0;
	color: #9DA0A4;
	content: "API";
	font-size: 12px;
	font-weight: bold;
	left: -1px;
	padding: 3px 7px;
	position: absolute;
	top: -1px;
}

</style>
<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<!-- Fav and touch icons -->
<link rel="shortcut icon" href="bootstrap/img/favicon.ico">
</head>

<body data-spy="scroll" data-target="#navbarExample" data-offset="0">

	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">

				<a class="btn btn-navbar" data-toggle="collapse"
					data-target=".nav-collapse"> <span class="icon-bar"></span> <span
					class="icon-bar"></span> <span class="icon-bar"></span>
				</a>
				<div class="">
					<a class="brand" href="/"><img class="logo-img"
						src="bootstrap/img/openEssayist.png">openEssayist</a>
				</div>

				<div class="nav-collapse collapse">
					<ul class="nav">
						<li class="active"><a href="/user">Home</a></li>
						<li><a href="#about">About</a></li>
						<li><a href="#contact">Contact</a></li>
					</ul>



					<?php
					if (\Epi\getSession()->get(\openEssayist\Constants::LOGGED_IN) == true) {
?>
					<div class="pull-right">
						<span class="navbar-text">Welcome, ##### </span>
						<form class="navbar-form pull-right" action='/logout' method="GET">
							<button type="submit" class="btn">Sign out</button>
						</form>
					</div>
					<?php
  } else {
?>
					<form class="navbar-form pull-right" action='/login' method='POST'>
						<input class="span2" type="text" placeholder="Email"> <input
							class="span2" type="password" placeholder="Password">
						<button type="submit" class="btn">Sign in</button>
					</form>
					<?php
  }
  ?>


				</div>
				<!--/.nav-collapse -->
			</div>
		</div>
	</div>

	<div class="container-fluid">
		<div class="row-fluid">
			<?php
			if (!isset($twocolumn)) {
				echo $content;
			}
			else
			{?>
			<div class="span2">
				<!--Sidebar content-->
				<div class="well sidebar-nav hidden-phone">
					<ul class="nav nav-list">

						<li class="nav-header">Sidebar</li>
						<li class="active"><a href="#">Link</a></li>
						<li><a href="#">Link</a></li>
						<li><a href="#">Link</a></li>
						<li><a href="#">Link</a></li>
					</ul>
				</div>

				<div id="navbarExample"
					class="well sidebar-nav subnav sidebar-nav-fixed hidden-phone">
					<ul class="nav nav-list">

						<li class="nav-header">Structure</li>
						<li class="active"><a href="#par_0000">Introduction</a></li>
						<li class=""><a href="#par_0001">Argument 1</a></li>
						<li class=""><a href="#par_0007">Argument 2</a></li>
						<li class=""><a href="#par_0030">Argument 3</a></li>
						<li class=""><a href="#par_0034">Conclusion</a></li>
					</ul>
				</div>

				<!--/.well -->
				<?php if (isset($twocolumn) && isset($subcontent)) 
				{
					echo $subcontent;
				} ?>
			</div>
			<div class="span10">
				<!--Body content-->

				<?php echo $content; ?>
			</div>
			<?php };?>
		</div>


		<footer class="">


			<div class="navbar  navbar-fixed-bottom visible-desktop">
				<div class="navbar-inner">
					<div class="container">

						<div class="pull-right">
							<ul class="nav">
								<li><a href="/admin">Admin</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>


		</footer>

	</div>
	<!-- /container -->




	<!-- Le javascript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script
		src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="bootstrap/js/bootstrap.js"></script>

</body>
</html>
