<?php 
require('config.php');
if (!isset($_GET['userid'])) {$_GET['userid'] = '';}
if (!isset($_GET['code'])) {$_GET['code'] = '';}

if (isset($_GET['userid']) && isset($_GET['userid'])) {
	if (preg_match('/^[0-9]{1,20}+$/',$_GET['userid']) && preg_match('/^[0-9A-Za-z]{1,20}$/',$_GET['code'])) {
		$dbresult = db_execute('select userid from users where userid=' . $_GET['userid'] . ' and emailverifycode="' . $_GET['code'] . '" and pwresetdate > NOW()-INTERVAL 1 DAY');
		if ($dbresult) {
			list($userid) = mysqli_fetch_row($dbresult);
		} else {
			showErrorPage('There was an error processing your request.  Please try again later.');
			exit;
		}
	} else {
		showErrorPage('An invalid password reset link was followed.  Check the link, or <a href="reset.php">Request a New Password Reset Link</a>');
		exit;
	}
} else {
	showErrorPage('An invalid password reset link was followed.  Check the link, or <a href="reset.php">Request a New Password Reset Link</a>');
	exit;
}



if ($_POST['a'] == 'setpassword') {
	$errors = array();
	if (!isset($_POST['pwresetcode']) || !$_POST['pwresetcode']) {$errors[] = 'An invalid password reset link was followed.  Check the link, or <a href="reset.php">Request a New Password Reset Link</a>';}
	if (!isset($_POST['userid']) || !$_POST['userid'] || !preg_match('/^[0-9]{1,20}$/',$_POST['userid'])) {$errors[] = 'An invalid password reset link was followed.  Check the link, or <a href="reset.php">Request a New Password Reset Link</a>';}
	if (!isset($_POST['password1']) || !isset($_POST['password2']) || !$_POST['password1'] || !$_POST['password2'] || !$_POST['password1'] === $_POST['password2']) {$errors[] = 'Password is required.';}
	if (count($errors)) {
		echo implode("<br>",$errors);
	} else {
		$dbresult = db_execute('select userid,active,pwresetcode from users where userid=' . $_POST['userid']);
		if ($dbresult) {
			if (mysqli_num_rows($dbresult)) {
				list($userid,$active,$pwresetcode) = mysqli_fetch_row($dbresult);
				if (!$active) {
					?>Your account has been de-activated.  Please contact support for assistance.<?php
				} else {
					if ($pwresetcode !== $_POST['pwresetcode']) {
						?>You have followed an invalid password reset link.  Please check the link or have a new <a href="reset.php">Password Reset Email</a> sent to you.<?php
					} else {
						db_execute('update users set pwresetcode=NULL,password="' . hash_hmac('sha256',strtolower($_POST['password1']),'a20' . $GLOBALS['salt']) . '" where userid=' . $userid);
						echo 1;
					}
				}
			} else {
				?>An invalid password reset link was followed.  Check the link, or <a href="reset.php">Request a New Password Reset Link</a><?php
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
			<?php if ($_GET['a'] == 'reset') { ?>
				Password Change
			<?php } else if ($_GET['a'] == 'set') { ?>
				Set Password
			<?php } ?>
		</h3>
		<p>
			What would you like your new password to be?
		</p>
		<form name="formSignUp" class="custom-validate" data-success="Password updated, redirecting..." data-error="Please fill in all fields correctly." data-success-redirect="signin.php?2">
			<input type="hidden" name="a" value="setpassword">
			<input type="hidden" name="userid" value="<?=$_REQUEST['userid'];?>">
			<input type="hidden" name="pwresetcode" value="<?=$_REQUEST['code'];?>">
			<div class="row">
				<div class="col-sm-6">
					<input name="password1" id="password1" type="password" placeholder="Password" class="validate-required" />
				</div>
				<div class="col-sm-6">
					<input name="password2" id="password2" type="password" placeholder="Confirm Password" class="validate-required" />
				</div>
				<div class="col-sm-12">
					<button class="btn btn--primary type--uppercase" type="submit">Save Password</button>
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
