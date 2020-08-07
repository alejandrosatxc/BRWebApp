<?php
require('config.php');
if ($_POST['a'] == 'resend') {
	if (!isset($_POST['email']) || !$_POST['email']) {echo 'Email is required.'; exit;}

	$dbresult = db_execute('select userid,active from users where email="' . escape($_POST['email']) . '"');
	if ($dbresult && mysqli_num_rows($dbresult)) {
		list($userid,$active) = mysqli_fetch_row($dbresult);
		if (!$active) {
			?>Your account has been de-activated.  Please contact support for assistance.<?php
		} else {
			$resetcode = pwgen(20,1);
			db_execute('update users set pwresetcode="' . $resetcode . '",pwresetdate=NOW() where userid=' . $userid);
			// send password reset email
			ob_start();
			require('email_templates/reset_email.php');
			$html = ob_get_clean();

			sendEmail($_POST['email'],'Password Reset for Bell Ripper User Portal',$html);
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
		<h2>Reset your password</h2>
		<p>
			Please enter your email address and a password reset
			link will be sent to you.
		</p>
		<form name="formReset" class="custom-validate" data-success="A password reset email has been sent to you." data-error="Please fill in all fields correctly.">
			<input type="hidden" name="a" value="resend">
			<input name="email" type="text" placeholder="Email Address" class="validate-required validate-email" />
			<button class="btn btn--primary type--uppercase" type="submit">Reset Password</button>
		</form>
		<span class="type--fine-print block">
			Ready to sign in?  <a href="signin.php">Return to the Sign In page</a>
		</span>

		<span class="type--fine-print block">Don't have an account yet?
			<a href="signup.php">Create an account</a>
		</span>
	</div>
</div>
<?php require('footer.php');?>
