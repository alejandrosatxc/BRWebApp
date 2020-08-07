<?php
if (isset($GLOBALS['portal']) && $GLOBALS['portal']) {$path = '../';} else {$path = '';}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Bell Ripper, PLLC Attorneys and Mediators - User Portal</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Bell Ripper, PLLC Attorneys and Mediators">
		<link href="<?=$path;?>css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
		<link href="<?=$path;?>css/stack-interface.css" rel="stylesheet" type="text/css" media="all" />
		<link href="<?=$path;?>css/theme.css" rel="stylesheet" type="text/css" media="all" />
		<link href="<?=$path;?>css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all" />
		<link href="<?=$path;?>css/jquery.fileupload.css" rel="stylesheet" type="text/css" media="all">
		<link href="<?=$path;?>css/custom.css" rel="stylesheet" type="text/css" media="all" />
		<script src="<?=$path;?>js/jquery-3.1.1.min.js"></script>
    </head>
	<body class="lawbg">
		<a id="start"></a>
		<div class="nav-container hidden-print">
			<div class="bar bar--xs visible-xs">
				<div class="container">
					<div class="row">
						<div class="col-xs-3 col-sm-2">
							<a href="index.php">
								<img class="logo" alt="logo" src="<?=$path;?>img/brlogo_sm.png" />
							</a>
						</div>
					<div class="col-xs-9 col-sm-10 text-right">
						<a href="#" class="hamburger-toggle" data-toggle-class="#menu1;hidden-xs">
							<i class="icon icon--sm stack-interface stack-menu"></i>
						</a>
					</div>
				</div>
			</div>
		</div>
		<nav id="menu1" class="lawnavbg bar bar--xs bar-1 hidden-xs bar--absolute pos-fixed">
			<div class="container">
				<div class="row">
					<div class="col-md-1 col-sm-2 hidden-xs">
						<div class="bar__module">
							<a href="index.php">
								<img class="logo" alt="logo" src="<?=$path;?>img/brlogo_sm.png" />
								</a>
							</div>
						</div>
						<div class="col-md-11 col-sm-12 text-right text-left-xs text-left-sm">
							<div class="bar__module">
								<ul class="menu-horizontal text-left">
									<li><a href="http://www.bellripper.com/">Home</a></li>
									<li><a href="http://www.bellripper.com/aboutus.html">About Us</a></li>
                                    <li class="dropdown">
										<span class="dropdown__trigger">Services</span>
										<div class="dropdown__container">
											<div class="container">
												<div class="row">
													<div class="dropdown__content col-md-3 col-sm-6">
														<ul class="menu-vertical">
															<li><a href="http://www.bellripper.com/lifeandestateplanning.html">Life and Estate Planning</a></li>
															<li><a href="http://www.bellripper.com/mediation.html">Mediation</a></li>
															<li><a href="http://www.bellripper.com/familylaw.html">Family Law</a></li>
														</ul>
													</div>
												</div>
											</div>
										</div>
									</li>
									<li><a href="http://www.bellripper.com/new-clients.html">New Clients</a></li>
									<li><a href="http://www.bellripper.com/resources.html">Resources</a></li>
									<li><a href="http://www.bellripper.com/contact-us.html">Contact Us</a></li>
									<li>
										<?php if (isset($GLOBALS['portal']) && $GLOBALS['portal']) { ?>
										<a class="btn btn--sm btn--primary type--uppercase" href="../signin.php?a=signout">
											<span class="btn__text">
												Sign Out
											</span>
										</a>
										<?php } else { ?>
										<a class="btn btn--sm btn--primary type--uppercase" href="signin.php">
											<span class="btn__text">
												User Portal
											</span>
										</a>
										<?php } ?>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</nav>
		</div>
		<div class="main-container">
			<div class="container-fluid">
