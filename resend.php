<?php
require('config.php');
if ($_POST['a'] == 'resend') {
	if (!isset($_POST['email']) || !$_POST['email']) {echo 'Email is required.'; exit;}

	$dbresult = db_execute('select userid,emailverifycode,active from users where email="' . escape($_POST['email']) . '"');
	if ($dbresult && mysqli_num_rows($dbresult)) {
		list($userid,$emailcode,$active) = mysqli_fetch_row($dbresult);
		if (!$active) {
			?>Your account has been de-activated.  Please contact support for assistance.<?php
		} else {
			if (!$emailcode) {
				// already activated
				?>Your account is already activated, <a href="signin.php">Click here</a> to sign in<?php
			} else {
				// send activation email
				ob_start();
				require('email_templates/signup_email.php');
				$html = ob_get_clean();

				sendEmail($_POST['email'],'Bell Ripper User Portal Registration',$html);
			}
		}
	}
	echo 1;
	exit;
}


require('header.php');

?>
<div class="row">
	<div class="col-sm-4"></div>
	<div class="col-lg-5 col-md-6 boxed boxed--border">
		<h2>Activate Account</h2>
		<p>
			To have an activation link sent to you, enter the email address
			you registered with below.
		</p>
		<form name="formResend" class="custom-validate" data-success="An activation link has been resent." data-error="Please fill in all fields correctly.">
			<input type="hidden" name="a" value="resend">
			<input name="email" type="text" placeholder="Email Address" class="validate-required validate-email" />
			<button class="btn btn--primary type--uppercase" type="submit">Send Activation Email</button>
		</form>
		<span class="type--fine-print block">Don't have an account yet?
			<a href="signup.php">Create an account</a>
		</span>
		<span class="type--fine-print block">Forgot your username or password?
			<a href="reset.php">Reset your password</a>
		</span>
	</div>
</div>
<?php require('footer.php');?>
