<?php
//MySql database credentials
$GLOBALS['CONFIG']['db_host'] = '';
$GLOBALS['CONFIG']['db_user'] = '';
$GLOBALS['CONFIG']['db_pass'] = '';
$GLOBALS['CONFIG']['db_database'] = 'bellripper_export';

$GLOBALS['db_handle'] = '';
$GLOBALS['errored'] = 0;
$GLOBALS['debug'] = 1;
$GLOBALS['salt'] = 'AL3x9442y2oUYqR32n2pr5';

$GLOBALS['CONFIG']['from_email'] = ''; #email account sending automated messages
$GLOBALS['CONFIG']['from_emailname'] = 'Bell Ripper Portal';
$GLOBALS['CONFIG']['admin_email'] = 'admin@bellripper.com';
$GLOBALS['CONFIG']['web_root'] = ''; #IP address of local machine

$GLOBALS['CONFIG']['stripe_token_uri'] = 'https://connect.stripe.com/oauth/token';
$GLOBALS['CONFIG']['stripe_authorize_uri'] ='https://connect.stripe.com/oauth/authorize';

$GLOBALS['CONFIG']['stripe_publishable_key'] = 'pk_test_FhQjnJfPUcPbnPTR5OR6X8WI00lNXwP2pZ';
// also stored in custom.js
$GLOBALS['CONFIG']['stripe_secret_key'] = 'sk_test_royutnHpH0lVcJP0jhA67Rfy00qjFvAmnl';

$GLOBALS['CONFIG']['US_States'] = array(
	'Alabama' => 'AL',
	'Alaska' => 'AK',
	'Arizona' => 'AZ',
	'Arkansas' => 'AR',
	'California' => 'CA',
	'Colorado' => 'CO',
	'Connecticut' => 'CT',
	'Delaware' => 'DE',
	'District of Columbia' => 'DC',
	'Florida' => 'FL',
	'Georgia' => 'GA',
	'Hawaii' => 'HI',
	'Idaho' => 'ID',
	'Illinois' => 'IL',
	'Indiana' => 'IN',
	'Iowa' => 'IA',
	'Kansas' => 'KS',
	'Kentucky' => 'KY',
	'Louisiana' => 'LA',
	'Maine' => 'ME',
	'Montana' => 'MT',
	'Nebraska' => 'NE',
	'Nevada' => 'NV',
	'New Hampshire' => 'NH',
	'New Jersey' => 'NJ',
	'New Mexico' => 'NM',
	'New York' => 'NY',
	'North Carolina' => 'NC',
	'North Dakota' => 'ND',
	'Ohio' => 'OH',
	'Oklahoma' => 'OK',
	'Oregon' => 'OR',
	'Maryland' => 'MD',
	'Massachusetts' => 'MA',
	'Michigan' => 'MI',
	'Minnesota' => 'MN',
	'Mississippi' => 'MS',
	'Missouri' => 'MO',
	'Pennsylvania' => 'PA',
	'Rhode Island' => 'RI',
	'South Carolina' => 'SC',
	'South Dakota' => 'SD',
	'Tennessee' => 'TN',
	'Texas' => 'TX',
	'Utah' => 'UT',
	'Vermont' => 'VT',
	'Virginia' => 'VA',
	'Washington' => 'WA',
	'West Virginia' => 'WV',
	'Wisconsin' => 'WI',
	'Wyoming' => 'WY'
);


$GLOBALS['USER'] = array();

if (!isset($_POST['a'])) {$_POST['a'] = '';}
if (!isset($_GET['a'])) {$_GET['a'] = '';}
if (!isset($_REQUEST['a'])) {$_REQUEST['a'] = '';}
db_connect();

if ($_GET['a'] == 'activate') {
	if (preg_match('/^[0-9]{1,20}+$/',$_GET['userid']) && preg_match('/^[0-9A-Za-z]{1,20}$/',$_GET['code'])) {
		$dbresult = db_execute('select userid from users where userid=' . $_GET['userid'] . ' and emailverifycode="' . $_GET['code'] . '"');
		if ($dbresult) {
			list($userid) = mysqli_fetch_row($dbresult);
			db_execute('update users set emailverifycode=NULL where userid=' . $_GET['userid']);
			$activation_successful = 1;
		} else {
			showErrorPage('There was an error processing your request.  Please try again later.');
			exit;
		}
	} else {
		showErrorPage('You have followed an invalid account activation link.  Please check the link or have a new <a href="resend.php">Activation Email</a> sent to you.');
		exit;
	}
}

function loaduser() {
	$GLOBALS['USER'] = array();
	$GLOBALS['USER']['userid'] = 0;
	$GLOBALS['USER']['first_name'] = '';
	$GLOBALS['USER']['last_name'] = '';
	$GLOBALS['USER']['email'] = '';
	$GLOBALS['USER']['createdate'] = '';
	$GLOBALS['USER']['active'] = 0;
	$GLOBALS['USER']['clio_contactid'] = 0;
	$GLOBALS['USER']['accesslevel'] = 0;
	$GLOBALS['USER']['phone'] = '';
	$GLOBALS['USER']['address'] = '';
	$GLOBALS['USER']['city'] = '';
	$GLOBALS['USER']['state'] = '';
	$GLOBALS['USER']['zip'] = '';
	$GLOBALS['USER']['finalize_usurveyid'] = 0;
	$GLOBALS['USER']['attorney_firstname'] = '';
	$GLOBALS['USER']['attorney_lastname'] = '';
	$GLOBALS['USER']['attorney_company'] = '';
	$GLOBALS['USER']['attorney_phone'] = '';
	$GLOBALS['USER']['attorney_email'] = '';
	if (isset($_COOKIE['bruser']) && isset($_COOKIE['brsession']) && preg_match('/^[0-9]{1,20}$/',$_COOKIE['bruser']) && preg_match('/^[0-9A-Za-z]{1,20}$/',$_COOKIE['brsession'])) {
		$sql = 'select userid,first_name,last_name,email,createdate,active,clio_contactid,accesslevel,phone,address,city,state,zip,finalize_usurveyid,attorney_firstname,attorney_lastname,attorney_company,attorney_phone,email from users where userid=' . $_COOKIE['bruser'] . ' and sessioncode="' . $_COOKIE['brsession'] . '" and active=1';
		$dbresult = db_execute($sql);
		if ($dbresult && mysqli_num_rows($dbresult)) {
			$GLOBALS['USER'] = mysqli_fetch_assoc($dbresult);
			return 1;
		}
	}
	return false;
}


/* UTILITY FUNCTIONS */
function format_status($status,$printtext = 0) {
	if ($status == 1) {
		return '<label class="label label-success">ACTIVE</label>';
	} else if ($status == 0) {
		return '<span class="label label-danger">DISABLED</span>';
	}
}

function escape($string) {
	return @mysqli_real_escape_string($GLOBALS['db_handle'], $string);
}

function db_connect() {
	if (!$GLOBALS['db_handle'] || !mysqli_thread_id($GLOBALS['db_handle'])) {
		$GLOBALS['db_handle'] = @mysqli_connect($GLOBALS['CONFIG']['db_host'], $GLOBALS['CONFIG']['db_user'], $GLOBALS['CONFIG']['db_pass']);
		if (!$GLOBALS['db_handle']) {
			logerror(mysqli_connect_error());
			echo 'Database Connect Error: ' . mysqli_connect_error();
			exit;
	} else {
			if (!@mysqli_select_db($GLOBALS['db_handle'], $GLOBALS['CONFIG']['db_database'])) {
				logerror('Error selecting database: ' . $GLOBALS['CONFIG']['db_database']);
				echo 'Database Selection Error: ' . mysqli_error($GLOBALS['db_handle']);
				exit;
			}
		}
	}
	return $GLOBALS['db_handle'];
}

function db_affected_rows() {
	return mysqli_affected_rows($GLOBALS['db_handle']);
}

function db_execute($query) {
	if (!db_connect()) {
		return false;
	}
	$logerror = 0;
	if (!$dbresult = mysqli_query($GLOBALS['db_handle'], $query)) {
		if (mysqli_error($GLOBALS['db_handle']) == 'MySQL server has gone away') {
			$GLOBALS['db_handle'] = '';
			if (db_connect()) {
				if (!$dbresult = mysqli_query($GLOBALS['db_handle'], $query)) {
					$logerror = 1;
				}
			} else {
				$logerror = 1;
			}
		} else {
			$logerror = 1;
			$query = str_replace('/\t/', '', $query);
			logerror("SQL Error for query($query): " . mysqli_error($GLOBALS['db_handle']));
			return false;
		}
	}

	if ($logerror) {
		$query = str_replace('/\t/', '', $query);
		logerror("SQL Error for query($query): " . mysqli_error($GLOBALS['db_handle']));
		return false;
	} else {
		return $dbresult;
	}
}

function db_insert_id() {
	return mysqli_insert_id($GLOBALS['db_handle']);
}

function db_error() {
	return mysqli_error($GLOBALS['db_handle']);
}

function logerror($error, $source = '') {
	if ($GLOBALS['errored']) {
		return;
	}
	$GLOBALS['errored'] = 1;
	$trace = debug_backtrace();

	if (!$source) {
		foreach ($trace as $idx => $traceinfo) {
			if (!$source) {
				$source = "{$traceinfo['file']} {$traceinfo['line']}";
			} else {
				$source .= "; " . basename($traceinfo['file']) . " {$traceinfo['line']}";
			}
		}
	}
	$source = escape(substr($source, 0, 450));
	if ($GLOBALS['db_handle']) {
		db_execute('insert into errors(logdate,`source`,`error`) values(NOW(),"' . $source . '","' . escape(substr($error, 0, 2800)) . '")');
	} else {
		echo 'Database Selection Error: ' . mysqli_error($GLOBALS['db_handle']);
	}
	$GLOBALS['errored'] = 0;
}

function getTimestamp() {
	$mstamp = microtime(true);
	$dtstamp = floor($mstamp);
	$ms = round(($mstamp - $dtstamp) * 1000000);
	$strms = str_pad($ms, 6, '0', STR_PAD_LEFT);
	return date(preg_replace('`(?<!\\\\)u`', $strms, 'Y-m-d H:i:s.u'), $dtstamp);
	//return date("Y-m-d H:i:s");
}

function logdebug($message) {
	if ($GLOBALS['debug']) {
		echo getTimestamp() . " $message\n";
	}
}

function logtodb($msg) {
	db_execute('insert into errors(logdate,`error`) values(NOW(),"' . escape(substr($msg, 0, 1180)) . '")');
}

function pwgen($pwlen = 8,$useupper = 0) {
	$pw = '';
	$allchars = "23456789abcdefghjkmnprstvwxyz";
	if ($useupper == 1) {$allchars .= 'ABCDEFGHJKLMNPQRSTUVWXYZ';}
	$len = strlen($allchars);
	srand((double)microtime()*100000);
	for ($x = 0; $x < $pwlen; $x++) {$pw .= substr($allchars,rand() % $len,1);}
	return $pw;
}

function sendEmail($to,$subject,$html) {
	require_once 'utils/Mandrill.php'; //Not required with Composer
	$mandrill = new Mandrill('m2UV7Ioa1LJrX0nuts0CiA');

	$message = array(
		"html" => $html,
		"text" => null,
		"from_email" => $GLOBALS['CONFIG']['from_email'],
		"from_name" => $GLOBALS['CONFIG']['from_emailname'],
		"subject" => $subject,
		"to" => array(array("email" => $to)),
		//"track_opens" => true,
		//"track_clicks" => true,
		//"auto_text" => true
	);
	$response = $mandrill->messages->send($message, true);
}


function showErrorPage($error) {
	require('header.php'); ?>
	<div class="row">
		<div class="col-sm-4"></div>
		<div class="col-lg-5 col-md-6 boxed boxed--border">
			<h2>Error</h2>
			<p>
				<?php echo $error; ?>
			</p>
		</div>
	</div>
	<?php require('footer.php');
}
?>
