<?php
if (isset($GLOBALS['portal']) && $GLOBALS['portal']) {$path = '../';} else {$path = '';}
?>
			</div>
		</div>

		<div class="container-fluid">
			<div class="col-xs-10 col-sm-8 col-md-6 col-xs-offset-1 col-sm-offset-2 col-md-offset-3" style="font-size: .6em; padding-bottom: 30px;">
				Disclaimers:<br>
				Bell Ripper PLLC is responsible for the content of this website.  The principal place of business for Bell Ripper PLLC is 1002 N. Flores St., San Antonio, Texas 78212.  Unless otherwise indicated, attorneys are not Board Certified.
				<br><br>
				​The materials on this website are made available by Bell Ripper PLLC for informational purposes only and do not constitute legal advice.  The receipt of information contained on this website does not create an attorney-client relationship.<br>
				<div style="text-align: center;">
					&copy; <?=date('Y');?> Copyright Bell Ripper PLLC
				</div>
			</div>
		</div>

		<a class="back-to-top inner-link" href="#start" data-scroll-class="100vh:active">
			<i class="stack-interface stack-up-open-big"></i>
		</a>
		<script src="<?=$path;?>js/scripts.js"></script>
		<script src="<?=$path;?>js/custom.js"></script>

		<script src="<?=$path;?>js/smooth-scroll.min.js"></script>
		<script src="<?=$path;?>js/easypiechart.min.js"></script>
		<script src="<?=$path;?>js/datepicker.js"></script>
		<script src="<?=$path;?>js/vendor/jquery.ui.widget.js"></script>
		<script src="<?=$path;?>js/jquery.iframe-transport.js"></script>
		<script src="<?=$path;?>js/jquery.fileupload.js"></script>
		<script src="https://checkout.stripe.com/checkout.js"></script>

		<script src="<?=$path;?>js/survey.jquery.js"></script>
	</body>
</html>
