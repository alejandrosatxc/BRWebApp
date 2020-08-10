<?php 
require('config.php');

// must have a NULL emailverifycode (indicating they have validated their email and their account is valid
// must have active = 1 (or they have had their account de-activated)

if ($_POST['a'] == 'signin') {
	$errors = array();
	if (!isset($_POST['email']) || !$_POST['email']) {$errors[] = 'We are unable to locate an account with that email address and password.';}
	if (!isset($_POST['password']) || !$_POST['password']) {$errors[] = 'We are unable to locate an account with that email address and password';}
	if (count($errors)) {
		echo implode("<br>",$errors);
	} else {
		$dbresult = db_execute('select userid,active,emailverifycode from users where email="' . escape($_POST['email']) . '" and password="' . hash_hmac('sha256',strtolower($_POST['password']),'a20' . $GLOBALS['salt']) . '"');
		if ($dbresult) {
			if (mysqli_num_rows($dbresult)) {
				list($userid,$active,$emailverifycode) = mysqli_fetch_row($dbresult);
				if (!$active) {
					 ?>Your account has been de-activated.  Please contact support for assistance.<?php 
				} elseif ($emailverifycode) { ?>
					Your account has not been activated.  To activate your account, you will need to verify your
					email address.  <a href="resend.php">Click here</a> to have an activation email resent to you.
				<?php } else {
					$sessioncode = pwgen(20,1);
					setcookie('bruser',$userid,(time()+60*60*24*30));
					setcookie('brsession',$sessioncode,(time()+60*60*24*30));
					db_execute('update users set sessioncode="' . $sessioncode . '",lastaccess=NOW() where userid=' . $userid);
					echo 1;
				}
			} else {
				 ?>We are unable to locate an account with that email address and password.<?php 
			}
		} else {
			 ?>There was an error processing your request.  Please try again later.<?php 
		}
	}
	exit;
}

if ($_GET['a'] == 'signout') {
	if (isset($_COOKIE['bruser']) && isset($_COOKIE['brsession'])) {
		db_execute('update users set sessioncode=NULL where userid=' . $_COOKIE['bruser']);
		$_COOKIE['bruser'] = '';
		$_COOKIE['brsession'] = '';
		setcookie('bruser','',time() - 3600,'/','');
		setcookie('brsession','',time() - 3600,'/','');
	}
}


require('header.php');

 ?>
	<div class="container">
		<div class="row">
			<?php if (isset($activation_successful)) { ?>
			<div class="col-sm-12 boxed boxed--border">
				<h3>Activation Successful</h3>
				You may now sign in to your account.
			</div>
			<?php } ?>
			<?php if ($_SERVER['QUERY_STRING'] == '1') { ?>
			<div class="col-sm-12 boxed boxed--border">
				<h3>Registration Successful</h3>
				Please check your email to validate your account.
			</div>
			<?php } ?>
			<?php if ($_SERVER['QUERY_STRING'] == '2') { ?>
			<div class="col-sm-12 boxed boxed--border">
				<h3>Password Updated</h3>
				You may now sign in to your account.
			</div>
			<?php } ?>
			<div class="col-sm-5 boxed boxed--border">
				<h3>
					TESTSETSETSETSET
				</h3>
				The User Portal allows you to fill out forms so that your documents may be generated
				for review by an attorney. The forms will be available to fill out at your leisure and will
				provide you a preview of your documents as you fill out the forms.
				The final documents, that were reviewed by an attorney, will then be uploaded to the
				User Portal for your review.

				Sign In or Create an account to begin.

				<div style="margin-top: 20px;">
					<a href="signup.php"><button class="btn btn--primary type--uppercase btn-block" type="button">Create An Account</button></a>
				</div>
			</div>
			<div class="col-sm-1"></div>
			<div class="col-sm-6 boxed boxed--border">
				<div class="row switchable__text">
					<h3>Already have an account?</h3>
					<p>
						Sign in to fill out forms and manage your account.
					</p>
					<form name="formSignIn" class="custom-validate" data-success="Sign in successful, redirecting..." data-error="Please fill in all fields correctly." data-success-redirect="portal/">
						<input type="hidden" name="a" value="signin">
						<input name="email" type="text" placeholder="Email Address" class="validate-required validate-email" />
						<input name="password" type="password" placeholder="Password" class="validate-required" />
						<button class="btn btn--primary type--uppercase" type="submit">Sign In</button>
					</form>
					<span class="type--fine-print block">Forgot your username or password?
						<a href="reset.php">Reset your password</a>
					</span>
				</div>
			</div>
		</div>
	</div>
<?php require('footer.php'); ?>
