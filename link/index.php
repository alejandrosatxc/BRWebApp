<?php
require('../config.php');

if ($_SERVER['QUERY_STRING']) {
	if (substr_count($_SERVER['QUERY_STRING'],'-')) {
		$arrParams = explode('-',$_SERVER['QUERY_STRING']);
		if ($arrParams[0] == 'R') {
			// password reset link
			header('Location: ../setpassword.php?a=reset&userid=' . $arrParams[1] . '&code=' . $arrParams[2]);
			exit;
		} else if ($arrParams[0] == 'A') {
			// activate link
			header('Location: ../signin.php?a=activate&userid=' . $arrParams[1] . '&code=' . $arrParams[2]);
			exit;
		} else if ($arrParams[0] == 'N') {
			// new account, set password
			header('Location: ../setpassword.php?a=set&userid=' . $arrParams[1] . '&code=' . $arrParams[2]);
			exit;
		}
	}
}
header('Location: ../index.php');

