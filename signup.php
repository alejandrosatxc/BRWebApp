<?php
require('config.php');
if ($_POST['a'] == 'signup') {
	$errors = array();
	if (!isset($_POST['first_name']) || !$_POST['first_name']) {$errors[] = 'First Name is required.';}
	if (!isset($_POST['last_name']) || !$_POST['last_name']) {$errors[] = 'Last Name is required.';}
	if (!isset($_POST['email']) || !$_POST['email']) {$errors[] = 'Email is required.';}
	if (!isset($_POST['password1']) || !isset($_POST['password2']) || !$_POST['password1'] || !$_POST['password2'] || !$_POST['password1'] === $_POST['password2']) {$errors[] = 'Password is required.';}
	if (count($errors)) {
		echo implode("<br>",$errors);
	} else {
		// can't create 2 accounts with the same email address
		$dbresult = db_execute('select active from users where email="' . escape($_POST['email']) . '"');
		if ($dbresult) {
			if (mysqli_num_rows($dbresult)) {
				list($active) = mysqli_fetch_row($dbresult);
				if ($active) {
					?>An account already exists with this email address.  <a href="signin.php">Click here</a> to sign in.<?php
				} else {
					?>An account already exists with this email address, but the account needs to be activated.
					<a href="resend.php">Click here</a> to have an activation email sent to you
					<?php
				}
			} else {
				$emailcode = pwgen(20,1);
				$dbresult = db_execute('insert into users(first_name,last_name,email,emailverifycode,password,createdate,active,accesslevel)'
						. ' values("' . escape($_POST['first_name']) . '","' . escape($_POST['last_name']) . '","' . escape($_POST['email']) . '","' . $emailcode . '","' . hash_hmac('sha256',strtolower($_POST['password1']),'a20' . $GLOBALS['salt']) . '",NOW(),1,0)');
				$userid = db_insert_id();

				ob_start();
				require('email_templates/signup_email.php');
				$html = ob_get_clean();

				sendEmail($_POST['email'],'Bell Ripper Portal Registration',$html);

				echo 1;
			}
		} else {
			?>There was an error processing your request.  Please try again later.<?php
		}
	}
	exit;
}

require('header.php');
?>

<div class="row">
	<div class="col-sm-4"></div>
	<div class="col-lg-6 col-md-7 boxed boxed--border">
		<h3>
			<span class="fa fa-stack fa-sm">
				<i class="fa fa-file-o fa-stack-2x"></i>
				<i class="fa fa-balance-scale fa-stack-1x"></i>
			</span>
			Create Account
		</h3>
		<p>
			The user portal allows you to fill out and generate documents that you have
			purchased or been assigned, online and at your leisure.
		</p>
		<form name="formSignUp" class="custom-validate" data-success="Account successfully created, redirecting..." data-error="Please fill in all fields correctly." data-success-redirect="signin.php?1">
			<input type="hidden" name="a" value="signup">
			<div class="row">
				<div class="col-sm-6">
					<input name="first_name" type="text" placeholder="First Name" class="validate-required" />
				</div>
				<div class="col-sm-6">
					<input name="last_name" type="text" placeholder="Last Name" class="validate-required" />
				</div>
				<div class="col-sm-12">
					<input name="email" type="text" placeholder="Email Address" class="validate-required validate-email" />
				</div>
				<div class="col-sm-6">
					<input name="password1" id="password1" type="password" placeholder="Password" class="validate-required" />
				</div>
				<div class="col-sm-6">
					<input name="password2" id="password2" type="password" placeholder="Confirm Password" class="validate-required" />
				</div>
				<div class="col-sm-12">
					<button class="btn btn--primary type--uppercase" type="submit">Sign Up</button>
				</div>
			</div>
		</form>

		<span class="type--fine-print block">Already have an account?
			<a href="signin.php">Sign In</a>
		</span>
		<span class="type--fine-print block">Forgot your username or password?
			<a href="reset.php">Reset your password</a>
		</span>
	</div>
</div>
<?php require('footer.php');?>
