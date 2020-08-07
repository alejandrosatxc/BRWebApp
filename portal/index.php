<?php
$GLOBALS['portal'] = 1;
require('../config.php');
if (!loaduser()) {
    header('Location: ../index.php');
    exit;
}

// zapier webhook URL: https://hooks.zapier.com/hooks/catch/1898631/z46vst/
//https://hooks.zapier.com/hooks/catch/1898631/z46vst/?description=Description of Matter&userid=#&attorney=Hilary Bell&practice=Family Law&referredby=ref&firstname=first&lastname=last&email=hi@here.com&phone=123-456-9387&address=123 E. Main&city=New York&state=NY&zip=20000&dob=12/13/19&citizen=1&military=0&emergency_contact=Someone&emergency_phone=123-438-2948&emergency_relationship=Mom
//returns status of 'success' with id and request_id

if ($_POST['a'] == 'account_update') {
    account_update();
    exit;
} elseif ($_POST['a'] == 'shop') {
    $json = printshop(1);
    echo $json;
    exit;
} elseif ($_POST['a'] == 'list_myforms') {
    $json = list_myforms();
    echo $json;
    exit;
} elseif ($_POST['a'] == 'list_mydocuments') {
    $json = list_mydocuments();
    echo $json;
    exit;
} elseif ($_POST['a'] == 'list_admins') {
    $json = list_admins();
    echo $json;
    exit;
} elseif ($_POST['a'] == 'list_clients') {
    $json = list_clients();
    echo $json;
    exit;
} elseif ($_POST['a'] == 'list_forms') {
    $json = list_forms();
    echo $json;
    exit;
} elseif ($_POST['a'] == 'list_form_templates') {
    $json = list_form_templates();
    echo $json;
    exit;
} elseif ($_POST['a'] == 'list_client_forms') {
    $json = list_client_forms();
    echo $json;
    exit;
} elseif ($_POST['a'] == 'list_client_documents') {
    $json = list_client_documents();
    echo $json;
    exit;
} elseif ($_POST['a'] == 'usurvey_edit') {
    $json = usurvey_edit();
    echo $json;
    exit;
} elseif ($_POST['a'] == 'form_edit') {
    $json = form_edit();
    echo $json;
    exit;
} elseif ($_POST['a'] == 'form_save') {
    form_save();
    exit;
} elseif ($_POST['a'] == 'document_save') {
    document_save();
    exit;
} elseif ($_POST['a'] == 'admin_edit') {
    $json = admin_edit();
    echo $json;
    exit;
} elseif ($_POST['a'] == 'admin_save') {
    admin_save();
    exit;
} elseif ($_POST['a'] == 'client_save') {
    client_save();
    exit;
} elseif ($_POST['a'] == 'admin_add') {
    admin_add();
    exit;
} elseif ($_POST['a'] == 'client_edit') {
    $json = client_edit();
    echo $json;
    exit;
} elseif ($_POST['a'] == 'document_edit') {
    $json = document_edit();
    echo $json;
    exit;
} elseif ($_GET['a'] == 'template_upload') {
    require('../UploadHandler.php');
    $upload_handler = new UploadHandler();
    $response = $upload_handler->response;
    $files = $upload_handler->response['files'];
    if (!isset($_GET['surveyid'])) {
        $_GET['surveyid'] = 0;
    }
    $templateid = 0;
    if (count($files) && isset($_GET['surveyid']) && preg_match('/^[0-9]{1,18}$/', $_GET['surveyid']) && !isset($files[0]->error)) {
        $filename = $files[0]->name;
        $name = $files[0]->name;
        $name = str_replace('_', ' ', $name);
        $name = str_replace('.docx', '', $name);
        $name = rtrim($name, '1');
        $dbresult = db_execute('insert into templates(name,file,surveyid) values("' . escape($name) . '","' . escape($filename) . '",' . $_GET['surveyid'] . ')');
        $templateid = db_insert_id();
    }
    mkdir('files/template_' . $templateid);
    $str = 'unzip ' . escapeshellarg('files/' . $filename) . ' -d files/template_' . $templateid;
    logerror($str);
    exec($str, $output);
    logerror(implode(',', $output));
    exit;
} elseif ($_GET['a'] == 'document_upload') {
    if (!$_GET['userid'] || !preg_match('/^[0-9]{1,18}$/', $_GET['userid'])) {
        echo 'Invalid client selected.';
        exit;
    }
    $visible = 0;
    if (isset($_GET['visible']) && $_GET['visible']) {
        $visible = 1;
    }
    $name = '';
    if (isset($_GET['name'])) {
        $name = $_GET['name'];
    }

    require('../UploadHandler.php');
    $upload_handler = new UploadHandler();
    $response = $upload_handler->response;
    $files = $upload_handler->response['files'];
    $udocumentid = 0;
    if (count($files) && !isset($files[0]->error)) {
        $filename = $files[0]->name;
        $ext = substr($filename, strpos($filename, '.')+1);
        $dbresult = db_execute('insert into userdocuments(name,filename,userid,createdate,status,visible) values("' . escape($name) . '","' . escape($filename) . '",' . $_GET['userid'] . ',NOW(),1,' . $visible . ')');

        $udocumentid = db_insert_id();
        $newfilename = 'document_' . $udocumentid . '.' . $ext;
        db_execute('update userdocuments set filename="' . escape($newfilename) . '" where udocumentid=' . $udocumentid);
        rename('files/' . $filename, 'clientfiles/' . $newfilename);
    }
    exit;
} elseif ($_POST['a'] == 'delete_template') {
    $json = template_delete();
    echo $json;
    exit;
} elseif ($_POST['a'] == 'delete_usurvey') {
    $json = usurvey_delete();
    echo $json;
    exit;
} elseif ($_POST['a'] == 'undelete_usurvey') {
    $json = usurvey_undelete();
    echo $json;
    exit;
} elseif ($_POST['a'] == 'assign_form') {
    assign_form();
    exit;
} elseif ($_POST['a'] == 'getSurvey') {
    $json = getSurvey();
    echo $json;
    exit;
} elseif ($_POST['a'] == 'usurvey_save') {
    $json = usurvey_save();
    echo $json;
    exit;
} elseif ($_POST['a'] == 'usurvey_finalize') {
    $json = usurvey_finalize();
    echo $json;
    exit;
} elseif ($_GET['a'] == 'p') {
    $json = process_purchase();
    echo $json;
    exit;
} elseif ($_GET['a'] == 'dl') {
    file_download(0);
    exit;
} elseif ($_GET['a'] == 'preview') {
    file_download(1);
    exit;
}


require('../header.php');
?>
<div class="row">
	<div class="col-lg-1"></div>
	<div class="col-md-4 col-lg-3 hidden-print">
		<div class="boxed boxed--lg boxed--border">
			<div style="text-align: center;">
				<i class="fa fa-user fa-4x"></i>
			</div>
			<div style="text-align: center;">
				<?=htmlspecialchars($GLOBALS['USER']['first_name']);?> <?=htmlspecialchars($GLOBALS['USER']['last_name']);?>
			</div>
			<hr>
			<ul class="menu-vertical">
				<li><a href="#" data-scrollto-id="content" data-toggle-class=".portal-tab:not(.hidden);hidden|#tab-shop;hidden" data-loadonclick="shop">Purchase Forms</a></li>
				<li><a href="#" data-scrollto-id="content" data-toggle-class=".portal-tab:not(.hidden);hidden|#tab-myforms;hidden" data-loadlist="myforms">My Forms</a></li>
				<li><a href="#" data-scrollto-id="content" data-toggle-class=".portal-tab:not(.hidden);hidden|#tab-mydocuments;hidden" data-loadlist="mydocuments">My Documents</a></li>
				<li><a href="#" data-scrollto-id="content" data-toggle-class=".portal-tab:not(.hidden);hidden|#tab-settings;hidden">My Account</a></li>
			</ul>
			<?php if ($GLOBALS['USER']['accesslevel'] == 5) { ?>
				<hr>
				<ul class="menu-vertical" style="border">
					<li><a href="#" data-scrollto-id="content" data-toggle-class=".portal-tab:not(.hidden);hidden|#tab-manage_forms;hidden" data-loadlist="forms">Manage Forms</a></li>
					<li><a href="#" data-scrollto-id="content" data-toggle-class=".portal-tab:not(.hidden);hidden|#tab-manage_clients;hidden" data-loadlist="clients">Manage Clients</a></li>
					<li><a href="#" data-scrollto-id="content" data-toggle-class=".portal-tab:not(.hidden);hidden|#tab-manage_admins;hidden" data-loadlist="admins">Manage Admins</a></li>
				</ul>
			<?php } ?>
		</div>
	</div>

	<div class="col-md-8 col-lg-7">
		<div id="content" class="boxed boxed--lg boxed--border">
			<div id="tab-purchase" class="portal-tab hidden">
				<div class="row">
					<div class="col-xs-12">
						<h3>Purchase Success</h3>
						Your form can now be accessed from <b>My Forms</b>.  Once documents are created, you will be able
						to download them from <b>My Documents</b>.
					</div>
				</div>
			</div>

			<div id="tab-shop" class="portal-tab">
				<div id="data_shop">
					<?php printshop(0);?>
				</div>
			</div>
		
			<div id="tab-survey" class="portal-tab hidden">
				<div id="surveyNeedIntake" style="border-bottom: 1px solid rgba(0,0,0,0.2); margin-bottom: 10px; padding-bottom: 10px;">
					Please fill out the Intake Questionnaire so that we have all of the information
					needed to generate your documents.
				</div>
				<div id="surveyWarning" style="border-bottom: 1px solid rgba(0,0,0,0.2); margin-bottom: 10px; padding-bottom: 10px;">
					Please make sure you accurately fill out all the information asked for in this form. The
					information you provide will be used to generate your documents. The way you enter
					the information is important, because it will be how it appears in the document, so pay
					close attention to spelling, capitalization, and punctuation.
				</div>
				<div id="surveyIntakeComplete" style="border-bottom: 1px solid rgba(0,0,0,0.2); margin-bottom: 10px; padding-bottom: 10px;">
					Thank you for completing the intake survey.
				</div>
				<div id="surveyReview" style="border-bottom: 1px solid rgba(0,0,0,0.2); margin-bottom: 10px; padding-bottom: 10px;">
					Thank you for completing the intake survey.  You can now review and finalize your form.
				</div>
				<div id="surveyFinalize" style="border-bottom: 1px solid rgba(0,0,0,0.2); margin-bottom: 10px; padding-bottom: 10px;">
					<p>
						Your form is now complete.  Click Finalize below to have your answers reviewed by one of
						our lawyers.  You will receive an email notification once your
						documents are ready.
					</p>

					<button id="btnFinalize" class="btn btn--primary type--uppercase" type="submit">Finalize</button>
					<input type="hidden" id="usurveyid" value="0">
					<input type="hidden" id="clientview" value="0">
				</div>
				<div id="surveyElement" class="divSurvey"></div>
				<div id="surveyResult"></div>
			</div>
			<div id="tab-myforms" class="portal-tab hidden">
				<div class="row">
					<div class="col-xs-6">
						<h3>My Forms</h3>
					</div>
					<div class="col-xs-6" style="text-align: right;">
						<ul class="menu-vertical">
<!--							<li><a href="#" data-scrollto-id="content" data-toggle-class=".portal-tab:not(.hidden);hidden|#tab-admins_add;hidden" class="btn btn--xs btn--primary"><span class="btn__text"><i class="fa fa-plus"></i> Add Admin</a></span></li>-->
						</ul>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-5">
						<div class="input-checkbox input-checkbox--switch">
							<input id="viewincomplete_myforms" type="checkbox" name="view_incomplete" data-loadlist="myforms" />
							<label for="view_incomplete"></label>
						</div>
						<span class="text-xs">Incomplete Forms Only</span>
					</div>
					<div class="col-sm-3"></div>
					<div class="col-sm-4" style="text-align: right;">
						<span class="text-xs">SHOW</span>
						<ul class="menu-horizontal text-left">
							<li class="dropdown btn--xs btn--secondary">
								<span class="dropdown__trigger">10</span>
								<div class="dropdown__container">
									<div class="container">
										<div class="row">
											<div class="dropdown__content col-sm-2">
												<ul class="menu-vertical" data-datatype="myforms">
													<li><a href="#" class="link_perpage">10</a></li>
													<li><a href="#" class="link_perpage">25</a></li>
													<li><a href="#" class="link_perpage">50</a></li>
													<li><a href="#" class="link_perpage">100</a></li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</li>
						</ul>
						<span class="text-xs">ENTRIES</span>
					</div>
					<div class="col-sm-4 text-right">

					</div>
				</div>

				<div id="list_myforms" style="min-height: 200px; width: 100%;"></div>
			</div>

			<div id="tab-mydocuments" class="portal-tab hidden">
				<div class="row">
					<div class="col-xs-6">
						<h3>My Documents</h3>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-5">
						<!--
						<div class="input-checkbox input-checkbox--switch">
							<input id="viewincomplete_myforms" type="checkbox" name="view_incomplete" data-loadlist="myforms" />
							<label for="view_incomplete"></label>
						</div>
						<span class="text-xs">Incomplete Forms Only</span>
						-->
					</div>
					<div class="col-sm-3"></div>
					<div class="col-sm-4" style="text-align: right;">
						<span class="text-xs">SHOW</span>
						<ul class="menu-horizontal text-left">
							<li class="dropdown btn--xs btn--secondary">
								<span class="dropdown__trigger">10</span>
								<div class="dropdown__container">
									<div class="container">
										<div class="row">
											<div class="dropdown__content col-sm-2">
												<ul class="menu-vertical" data-datatype="mydocuments">
													<li><a href="#" class="link_perpage">10</a></li>
													<li><a href="#" class="link_perpage">25</a></li>
													<li><a href="#" class="link_perpage">50</a></li>
													<li><a href="#" class="link_perpage">100</a></li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</li>
						</ul>
						<span class="text-xs">ENTRIES</span>
					</div>
					<div class="col-sm-4 text-right">

					</div>
				</div>

				<div id="list_mydocuments" style="min-height: 200px; width: 100%;"></div>
			</div>

			<div id="tab-settings" class="portal-tab hidden">
				<h3>My Account</h3>

				<form name="formProfile" class="custom-validate" data-success="Account successfully updated." data-error="Please fill in all fields correctly.">
					<input type="hidden" name="a" value="account_update">
					<div class="row" style="margin-top: 25px;">
						<div class="col-sm-12">
							<p>
								Please make sure you accurately fill out all the information asked for in this form. The
								information you provide will be used to generate your documents. The way you enter
								the information is important, because it will be how it appears in the document, so pay
								close attention to spelling, capitalization, and punctuation.
							</p>
						</div>
						<div class="col-sm-6">
							<input name="first_name" type="text" placeholder="First Name" class="validate-required" value="<?=htmlspecialchars($GLOBALS['USER']['first_name']);?>" />
						</div>
						<div class="col-sm-6">
							<input name="last_name" type="text" placeholder="Last Name" class="validate-required" value="<?=htmlspecialchars($GLOBALS['USER']['last_name']);?>" />
						</div>
						<div class="col-sm-12">
							<input name="email" type="text" placeholder="Email Address" class="validate-required validate-email" value="<?=htmlspecialchars($GLOBALS['USER']['email']);?>" />
						</div>

						<div class="col-sm-12">
							<input name="address" type="text" placeholder="Address" class="validate-required" value="<?=htmlspecialchars($GLOBALS['USER']['address']);?>" />
						</div>
						<div class="col-sm-6">
							<input name="city" type="text" placeholder="City" class="validate-required" value="<?=htmlspecialchars($GLOBALS['USER']['city']);?>" />
						</div>
						<div class="col-sm-6">
							<div class="input-select">
								<select name="state">
									<option value="">State</option>
									<?php foreach ($GLOBALS['CONFIG']['US_States'] as $state => $val) {
    echo '<option value="' . $val . '"';
    if ($GLOBALS['USER']['state'] == $val) {
        echo ' selected';
    }
    echo '">' . $state . '</option>';
} ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<input name="zip" type="text" placeholder="Zip" class="validate-required" value="<?=htmlspecialchars($GLOBALS['USER']['zip']);?>" />
						</div>
						<div class="col-sm-6">
							<input name="phone" type="text" placeholder="Phone Number" class="validate-required" value="<?=htmlspecialchars($GLOBALS['USER']['phone']);?>" />
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<h5>Change Password</h5>
							<p>Leave blank to keep your existing password.</p>
						</div>
						<div class="col-sm-6">
							<input name="password" id="password" type="password" placeholder="Current Password" />
						</div>
						<div class="col-sm-6">
							<input name="password1" id="password1" type="password" placeholder="New Password" />
						</div>
						<div class="col-sm-6">
						</div>
						<div class="col-sm-6">
							<input name="password2" id="password2" type="password" placeholder="Confirm New Password" />
						</div>
					</div>
					<?php if ($GLOBALS['USER']['accesslevel'] > 3) { ?>
						<div class="row">
							<div class="col-sm-12">
								<h5>Attorney Information</h5>
								<p>This information is used in the generation of documents when you are listed as the responsible attorney.</p>
							</div>
							<div class="col-sm-6">
								<input name="attorney_firstname" type="text" placeholder="First Name" class="validate-required" value="<?=htmlspecialchars($GLOBALS['USER']['attorney_firstname']);?>" />
							</div>
							<div class="col-sm-6">
								<input name="attorney_lastname" type="text" placeholder="Last Name" class="validate-required" value="<?=htmlspecialchars($GLOBALS['USER']['attorney_lastname']);?>" />
							</div>
							<div class="col-sm-12">
								<input name="attorney_email" type="text" placeholder="Email Address" class="validate-required validate-email" value="<?=htmlspecialchars($GLOBALS['USER']['attorney_email']);?>" />
							</div>
							<div class="col-sm-6">
								<input name="attorney_company" type="text" placeholder="Company" class="validate-required" value="<?=htmlspecialchars($GLOBALS['USER']['attorney_company']);?>" />
							</div>
							<div class="col-sm-6">
								<input name="attorney_phone" type="text" placeholder="Phone Number" class="validate-required" value="<?=htmlspecialchars($GLOBALS['USER']['attorney_phone']);?>" />
							</div>
						</div>
					<?php } ?>
					<div class="row">
						<div class="col-sm-12">
							<button class="btn btn--primary type--uppercase" type="submit">Save Changes</button>
						</div>
					</div>
				</form>
			</div>

			<div id="tab-client" class="portal-tab hidden"></div>

			<?php if ($GLOBALS['USER']['accesslevel'] == 5) { ?>
				<div id="tab-manage_forms" class="portal-tab hidden">
					<div class="row">
						<div class="col-xs-6">
							<h3>Manage Forms</h3>
						</div>
						<div class="col-xs-6" style="text-align: right;">
							<ul class="menu-vertical">
								<!--<li><a href="#" data-scrollto-id="content" data-toggle-class=".portal-tab:not(.hidden);hidden|#tab-clients_add;hidden" class="btn btn--xs btn--primary"><span class="btn__text"><i class="fa fa-plus"></i> Add Client</a></span></li>-->
							</ul>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="input-checkbox input-checkbox--switch">
								<input id="viewactive_forms" type="checkbox" name="viewactive" checked data-loadlist="forms" />
								<label for="viewactive"></label>
							</div>
							<span class="text-xs">Active Only</span>
						</div>
						<div class="col-sm-4">
						</div>
						<div class="col-sm-4" style="text-align: right;">
							<span class="text-xs">SHOW</span>
							<ul class="menu-horizontal text-left">
								<li class="dropdown btn--xs btn--secondary">
									<span class="dropdown__trigger">10</span>
									<div class="dropdown__container">
										<div class="container">
											<div class="row">
												<div class="dropdown__content col-sm-2">
													<ul class="menu-vertical" data-datatype="forms">
														<li><a href="#" class="link_perpage">10</a></li>
														<li><a href="#" class="link_perpage">25</a></li>
														<li><a href="#" class="link_perpage">50</a></li>
														<li><a href="#" class="link_perpage">100</a></li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</li>
							</ul>
							<span class="text-xs">ENTRIES</span>
						</div>
						<div class="col-sm-4 text-right">
							
						</div>
					</div>

					<div id="list_forms" style="min-height: 200px; width: 100%;"></div>
				</div>
				<div id="tab-manage_clients" class="portal-tab hidden">
					<div class="row">
						<div class="col-xs-6">
							<h3>Manage Clients</h3>
						</div>
						<div class="col-xs-6" style="text-align: right;">
							<ul class="menu-vertical">
								<!--<li><a href="#" data-scrollto-id="content" data-toggle-class=".portal-tab:not(.hidden);hidden|#tab-clients_add;hidden" class="btn btn--xs btn--primary"><span class="btn__text"><i class="fa fa-plus"></i> Add Client</a></span></li>-->
							</ul>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="input-checkbox input-checkbox--switch">
								<input id="viewactive_clients" type="checkbox" name="viewactive" checked data-loadlist="clients" />
								<label for="viewactive"></label>
							</div>
							<span class="text-xs">Active Clients</span>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-5">
							<div class="input-checkbox input-checkbox--switch">
								<input id="viewincomplete_clients" type="checkbox" name="viewincomplete" checked data-loadlist="clients" />
								<label for="viewincomplete"></label>
							</div>
							<span class="text-xs">Incomplete Forms</span>
						</div>
						<div class="col-sm-3">
						</div>
						<div class="col-sm-4" style="text-align: right;">
							<span class="text-xs">SHOW</span>
							<ul class="menu-horizontal text-left">
								<li class="dropdown btn--xs btn--secondary">
									<span class="dropdown__trigger">10</span>
									<div class="dropdown__container">
										<div class="container">
											<div class="row">
												<div class="dropdown__content col-sm-2">
													<ul class="menu-vertical" data-datatype="clients">
														<li><a href="#" class="link_perpage">10</a></li>
														<li><a href="#" class="link_perpage">25</a></li>
														<li><a href="#" class="link_perpage">50</a></li>
														<li><a href="#" class="link_perpage">100</a></li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</li>
							</ul>
							<span class="text-xs">ENTRIES</span>
						</div>
						<div class="col-sm-4 text-right">
							
						</div>
					</div>

					<div id="list_clients" style="min-height: 200px; width: 100%;"></div>
				</div>
				<div id="tab-admins_add" class="portal-tab hidden">
					<h3>Add Admin</h3>

					<form name="formAdminAdd" class="custom-validate" data-success="Admin account successfully added." data-error="Please fill in all fields correctly.">
						<input type="hidden" name="a" value="admin_add">
						<div class="row">
							<div class="col-sm-6">
								<input name="first_name" type="text" placeholder="First Name" class="validate-required" value="" />
							</div>
							<div class="col-sm-6">
								<input name="last_name" type="text" placeholder="Last Name" class="validate-required" value="" />
							</div>
							<div class="col-sm-12">
								<input name="email" type="text" placeholder="Email Address" class="validate-required validate-email" value="" />
							</div>

							<div class="col-sm-12">
								<button class="btn btn--primary type--uppercase" type="submit">Add Administrator</button>
							</div>
						</div>
					</form>
				</div>
				<div id="tab-admins_edit" class="portal-tab hidden"></div>
				<div id="tab-form_edit" class="portal-tab hidden"></div>
				<div id="tab-manage_admins" class="portal-tab hidden">
					<div class="row">
						<div class="col-xs-6">
							<h3>Manage Administrators</h3>
						</div>
						<div class="col-xs-6" style="text-align: right;">
							<ul class="menu-vertical">
								<li><a href="#" data-scrollto-id="content" data-toggle-class=".portal-tab:not(.hidden);hidden|#tab-admins_add;hidden" class="btn btn--xs btn--primary"><span class="btn__text"><i class="fa fa-plus"></i> Add Admin</a></span></li>
							</ul>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<div class="input-checkbox input-checkbox--switch">
								<input id="viewactive_admins" type="checkbox" name="viewactive" checked data-loadlist="admins" />
								<label for="viewactive_admins"></label>
							</div>
							<span class="text-xs">Active Only</span>
						</div>
						<div class="col-sm-4"></div>
						<div class="col-sm-4" style="text-align: right;">
							<span class="text-xs">SHOW</span>
							<ul class="menu-horizontal text-left">
								<li class="dropdown btn--xs btn--secondary">
									<span class="dropdown__trigger">10</span>
									<div class="dropdown__container">
										<div class="container">
											<div class="row">
												<div class="dropdown__content col-sm-2" data-datatype="admins">
													<ul class="menu-vertical">
														<li><a href="#" class="link_perpage">10</a></li>
														<li><a href="#" class="link_perpage">25</a></li>
														<li><a href="#" class="link_perpage">50</a></li>
														<li><a href="#" class="link_perpage">100</a></li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</li>
							</ul>
							<span class="text-xs">ENTRIES</span>
						</div>
						<div class="col-sm-4 text-right">
							
						</div>
					</div>

					<div id="list_admins" style="min-height: 200px; width: 100%;"></div>

				</div>
			<?php } ?>

		</div>
	</div>
	<div class="col-lg-1"></div>
</div>

<?php require('../footer.php');




function list_admins()
{
    $json = array();
    $json['error'] = '';

    if ($GLOBALS['USER']['accesslevel'] < 5) {
        $json['error'] = 'Invalid Permissions.';
        return json_encode($json);
    }
    ob_start();

    $filter = ' where accesslevel = 5';

    /*
    if (isset($_POST['filter']) && $_POST['filter']) {
        $_POST['filter'] = substr($_POST['filter'],0,100);
        $_POST['filter'] = preg_replace('/[\-\(\)]/','',$_POST['customer']);
        $_POST['filter'] = escape(strtolower($_POST['filter']));
        $filter .= ' and (lower(first_name) like "%' . $_POST['filter'] . '%" or lower(last_name) like "%' . $_POST['filter'] . '%" or lower(email) like "%' . $_POST['filter'] . '%")';
    }
    */

    if (isset($_POST['viewactive']) && $_POST['viewactive']) {
        $filter .= ' and active = 1';
    }

    $page = 1;
    $sortcol = 'lastactive';
    $sort = 'desc';
    $perpage = 1;
    $rawsortcol = 'lastactive';

    if (isset($_POST['perpage']) && preg_match('/^[0-9]{2,3}$/', $_POST['perpage'])) {
        $perpage = $_POST['perpage'];
    }
    if (isset($_POST['page']) && preg_match('/^[0-9]{1,10}$/', $_POST['page'])) {
        $page = $_POST['page'];
    }
    if (isset($_POST['sort']) && ($_POST['sort'] === 'desc' || $_POST['sort'] === 'asc')) {
        $sort = $_POST['sort'];
    }
    $arrSorts = array('first_name' => 'first_name','last_name' => 'last_name','email' => 'email','lastactive' => 'lastactive','active' => 'active');
    if (isset($_POST['sortcol']) && isset($arrSorts[$_POST['sortcol']])) {
        $sortcol = $arrSorts[$_POST['sortcol']];
        $rawsortcol = $_POST['sortcol'];
    } else {
        $sortcol = $arrSorts[$sortcol];
    }

    $sql = 'select count(userid) from users ' . $filter;

    $total = 0;
    $totalpages = 0;
    $dbresult = db_execute($sql);
    if ($dbresult) {
        if (mysqli_num_rows($dbresult)) {
            list($total) = mysqli_fetch_row($dbresult);
        }

        $totalpages = ceil($total / $perpage);
        if ($page < 0 || $page > $totalpages) {
            $page = 1;
        }

        $limit = 'limit ' . ($page*$perpage-$perpage) . ',' . $perpage;

        $sql = 'select userid,first_name,last_name,email,date_format(lastaccess,\'%c/%e/%Y %l:%i %p\') as lastactive,active';
        $sql .= ' from users' . $filter;
        $sql .= ' order by ' . $sortcol . ' ' . $sort . ',first_name desc,last_name desc ' . $limit;
        $dbresult = db_execute($sql);
    } ?><div class="table-responsive">
		<table class="table table-striped table-hover dataTable" data-type="admins"><thead><tr>
	<?php

    $arrTHs = array();
    // sortcol,content
    $arrTHs[] = array('first_name','First Name');
    $arrTHs[] = array('last_name','Last Name');
    $arrTHs[] = array('email','E-Mail');
    $arrTHs[] = array('lastactive','Last Active');
    $arrTHs[] = array('active','Status');
    foreach ($arrTHs as $idx => $arrTH) {
        $class = 'sorting';
        if ($rawsortcol == $arrTH[0]) {
            if ($sort == 'asc') {
                $class = 'sorting_asc';
            } else {
                $class = 'sorting_desc';
            }
        } elseif (!$arrTH[0]) {
            $class = '';
        } ?><th class="<?=$class; ?>" data-sort="<?=$arrTH[0]; ?>"><?=$arrTH[1]; ?></th><?php
    } ?></tr></thead><tbody><?php

    if (!$total) {
        ?><tr><td colspan="5" style="text-align: center;" class="muted">[ No Administrators Matched ]</td></tr><?php
    }

    if ($dbresult && mysqli_num_rows($dbresult)) {
        while ($row = mysqli_fetch_assoc($dbresult)) {
            if (!$row['first_name']) {
                $row['first_name'] = '[ None ]';
            } ?><tr class="link_editadmin" data-userid="<?=$row['userid']; ?>">
			<td><?=$row['first_name']; ?></td><td><?=$row['last_name']; ?></td>
			<td><span class="text-xs"><?=$row['email']; ?></span></td>
			<td><span class="text-xs"><?=$row['lastactive']; ?></span></td>
			<td><span class="text-xs"><?=format_status($row['active']); ?></span></td><?php
        }
    } ?></tr></tbody></table></div><?php

    $shownstart = ($page*$perpage-$perpage);
    if ($total) {
        $shownstart++;
    }
    $shown = ($page*$perpage);
    if ($shown > $total) {
        $shown = $total;
    } ?><div class="col-md-6 paging text-xs">Showing <?=$shownstart; ?> to <?=$shown; ?> of <?=$total; ?> entries</div><?php

    if ($totalpages > 0) {
        $prevpages = 2;

        if (($totalpages - $page) < 2) {
            $prevpages += 2 - ($totalpages - $page);
        }

        $arrPages = array();
        for ($x = $prevpages; $x > 0; $x--) {
            if (($page - $x) > 0) {
                $arrPages[] = ($page - $x);
            }
        }
        $arrPages[] = $page;
        for ($x = 1; $x <= 4; $x++) {
            if (count($arrPages) < 5) {
                if (($page + $x) <= $totalpages) {
                    $arrPages[] = ($page + $x);
                }
            }
        } ?><div class="col-md-6 paging" style="text-align: right;"><?php

        $class = '';
        if ($page < 2) {
            $class = ' disabled';
        } ?><a class="btn btn--xs btn--primary <?=$class; ?>" data-page="<?=($page-1); ?>" data-type="admins"><span class="btn__text">PREVIOUS</span></a><?php
        foreach ($arrPages as $curpage) {
            $class = 'btn--secondary';
            if ($page == $curpage) {
                $class = 'btn--primary-2';
            } ?><a class="btn btn--xs <?=$class; ?>" data-page="<?=$curpage; ?>" data-type="admins"><span class="btn__text"><?=$curpage; ?></span></a><?php
        }
        $class = '';
        if ($page == $totalpages) {
            $class = ' disabled';
        } ?><a class="btn btn--xs btn--primary <?=$class; ?>" data-page="<?=($page+1); ?>" data-type="admins"><span class="btn__text">NEXT</span></a><?php
    }

    $json['html'] = ob_get_clean();
    return json_encode($json);
}

function list_clients()
{
    $json = array();
    $json['error'] = '';

    if ($GLOBALS['USER']['accesslevel'] < 5) {
        $json['error'] = 'Invalid Permissions.';
        return json_encode($json);
    }
    ob_start();

    $filter = ' where u.accesslevel = 0';

    /*
    if (isset($_POST['filter']) && $_POST['filter']) {
        $_POST['filter'] = substr($_POST['filter'],0,100);
        $_POST['filter'] = preg_replace('/[\-\(\)]/','',$_POST['customer']);
        $_POST['filter'] = escape(strtolower($_POST['filter']));
        $filter .= ' and (lower(u.first_name) like "%' . $_POST['filter'] . '%" or lower(u.last_name) like "%' . $_POST['filter'] . '%" or lower(u.email) like "%' . $_POST['filter'] . '%")';
    }
    */

    if (isset($_POST['viewactive']) && $_POST['viewactive']) {
        $filter .= ' and u.active = 1';
    }
    if (isset($_POST['viewincomplete']) && $_POST['viewincomplete']) {
        $filter .= ' and icount > 0';
    }

    $page = 1;
    $sortcol = 'last_name';
    $sort = 'asc';
    $perpage = 1;
    $rawsortcol = 'last_name';

    if (isset($_POST['perpage']) && preg_match('/^[0-9]{2,3}$/', $_POST['perpage'])) {
        $perpage = $_POST['perpage'];
    }
    if (isset($_POST['page']) && preg_match('/^[0-9]{1,10}$/', $_POST['page'])) {
        $page = $_POST['page'];
    }
    if (isset($_POST['sort']) && ($_POST['sort'] === 'desc' || $_POST['sort'] === 'asc')) {
        $sort = $_POST['sort'];
    }
    $arrSorts = array('first_name' => 'u.first_name','last_name' => 'u.last_name','email' => 'u.email','lastaccess' => 'u.lastaccess','icount' => 'icount');
    if (isset($_POST['sortcol']) && isset($arrSorts[$_POST['sortcol']])) {
        $sortcol = $arrSorts[$_POST['sortcol']];
        $rawsortcol = $_POST['sortcol'];
    } else {
        $sortcol = $arrSorts[$sortcol];
    }

    $sql = 'select count(userid) from (';
    $sql .= '  select ui.userid,ui.active,ui.accesslevel,ifnull(sum(us.incomplete),0) as icount from users ui left join (';
    $sql .= '   select userid,ifnull(finaldate,1) as incomplete from usersurveys';
    $sql .= ' ) us on ui.userid = us.userid group by ui.userid,ui.active,ui.accesslevel';
    $sql .= ') u ' . $filter;

    $total = 0;
    $totalpages = 0;
    $dbresult = db_execute($sql);
    if ($dbresult) {
        if (mysqli_num_rows($dbresult)) {
            list($total) = mysqli_fetch_row($dbresult);
        }

        $totalpages = ceil($total / $perpage);
        if ($page < 0 || $page > $totalpages) {
            $page = 1;
        }

        $limit = 'limit ' . ($page*$perpage-$perpage) . ',' . $perpage;

        $sql = 'select u.userid,u.first_name,u.last_name,u.email,date_format(u.lastaccess,\'%c/%e/%Y %l:%i %p\') as lastaccess,u.active,u.accesslevel,u.icount from (';
        $sql .= '  select ui.userid,ui.first_name,ui.last_name,ui.email,ui.lastaccess,ui.active,ui.accesslevel,icount from users ui left join (';
        $sql .= '   select userid,sum(incomplete) as icount from (select userid,if(finaldate is null,1,0) as incomplete from usersurveys) ici group by userid';
        $sql .= ' ) us on ui.userid = us.userid';
        $sql .= ') u ' . $filter;
        $sql .= ' order by ' . $sortcol . ' ' . $sort . ',u.first_name desc,u.last_name desc ' . $limit;
        $dbresult = db_execute($sql);
    } ?><div class="table-responsive">
		<table class="table table-striped table-hover dataTable" data-type="clients"><thead><tr>
	<?php

    $arrTHs = array();
    // sortcol,content
    $arrTHs[] = array('first_name','First Name');
    $arrTHs[] = array('last_name','Last Name');
    $arrTHs[] = array('email','E-Mail');
    $arrTHs[] = array('lastaccess','Last Active');
    $arrTHs[] = array('icount','Incomplete Forms');
    foreach ($arrTHs as $idx => $arrTH) {
        $class = 'sorting';
        if ($rawsortcol == $arrTH[0]) {
            if ($sort == 'asc') {
                $class = 'sorting_asc';
            } else {
                $class = 'sorting_desc';
            }
        } elseif (!$arrTH[0]) {
            $class = '';
        } ?><th class="<?=$class; ?>" data-sort="<?=$arrTH[0]; ?>"><?=$arrTH[1]; ?></th><?php
    } ?></tr></thead><tbody><?php

    if (!$total) {
        ?><tr><td colspan="5" style="text-align: center;" class="muted">[ No Clients Matched ]</td></tr><?php
    }

    if ($dbresult && mysqli_num_rows($dbresult)) {
        while ($row = mysqli_fetch_assoc($dbresult)) {
            if (!$row['first_name']) {
                $row['first_name'] = '[ None ]';
            } ?><tr class="link_editclient" data-userid="<?=$row['userid']; ?>">
			<td><?=$row['first_name']; ?></td><td><?=$row['last_name']; ?></td>
			<td><span class="text-xs"><?=$row['email']; ?></span></td>
			<td><span class="text-xs"><?=$row['lastaccess']; ?></span></td>
			<td><label class="label label-primary"><?=$row['icount']; ?></label></td><?php
        }
    } ?></tr></tbody></table></div><?php

    $shownstart = ($page*$perpage-$perpage);
    if ($total) {
        $shownstart++;
    }
    $shown = ($page*$perpage);
    if ($shown > $total) {
        $shown = $total;
    } ?><div class="col-md-6 paging text-xs">Showing <?=$shownstart; ?> to <?=$shown; ?> of <?=$total; ?> clients</div><?php

    if ($totalpages > 0) {
        $prevpages = 2;

        if (($totalpages - $page) < 2) {
            $prevpages += 2 - ($totalpages - $page);
        }

        $arrPages = array();
        for ($x = $prevpages; $x > 0; $x--) {
            if (($page - $x) > 0) {
                $arrPages[] = ($page - $x);
            }
        }
        $arrPages[] = $page;
        for ($x = 1; $x <= 4; $x++) {
            if (count($arrPages) < 5) {
                if (($page + $x) <= $totalpages) {
                    $arrPages[] = ($page + $x);
                }
            }
        } ?><div class="col-md-6 paging" style="text-align: right;"><?php

        $class = '';
        if ($page < 2) {
            $class = ' disabled';
        } ?><a class="btn btn--xs btn--primary <?=$class; ?>" data-page="<?=($page-1); ?>" data-type="clients"><span class="btn__text">PREVIOUS</span></a><?php
        foreach ($arrPages as $curpage) {
            $class = 'btn--secondary';
            if ($page == $curpage) {
                $class = 'btn--primary-2';
            } ?><a class="btn btn--xs <?=$class; ?>" data-page="<?=$curpage; ?>" data-type="clients"><span class="btn__text"><?=$curpage; ?></span></a><?php
        }
        $class = '';
        if ($page == $totalpages) {
            $class = ' disabled';
        } ?><a class="btn btn--xs btn--primary <?=$class; ?>" data-page="<?=($page+1); ?>" data-type="clients"><span class="btn__text">NEXT</span></a><?php
    }

    $json['html'] = ob_get_clean();
    return json_encode($json);
}

function list_client_documents()
{
    $json = array();
    $json['error'] = '';
    $json['html'] = '';

    if ($GLOBALS['USER']['accesslevel'] < 5) {
        $json['error'] = 'Invalid Permissions.';
        return json_encode($json);
    }
    ob_start();

    $filter = ' where 1=1 ';
    if (isset($_POST['filter']) && $_POST['filter'] && preg_match('/^[0-9]{1,18}$/', $_POST['filter'])) {
        $filter .= ' and userid=' . $_POST['filter'];
    }

    $page = 1;
    $sortcol = 'createdate';
    $sort = 'desc';
    $perpage = 1;
    $rawsortcol = 'createdate';

    if (isset($_POST['perpage']) && preg_match('/^[0-9]{2,3}$/', $_POST['perpage'])) {
        $perpage = $_POST['perpage'];
    }
    if (isset($_POST['page']) && preg_match('/^[0-9]{1,10}$/', $_POST['page'])) {
        $page = $_POST['page'];
    }
    if (isset($_POST['sort']) && ($_POST['sort'] === 'desc' || $_POST['sort'] === 'asc')) {
        $sort = $_POST['sort'];
    }
    $arrSorts = array('name' => 'name','createdate' => 'createdate','status' => 'status');
    if (isset($_POST['sortcol']) && isset($arrSorts[$_POST['sortcol']])) {
        $sortcol = $arrSorts[$_POST['sortcol']];
        $rawsortcol = $_POST['sortcol'];
    } else {
        $sortcol = $arrSorts[$sortcol];
    }

    $sql = 'select count(udocumentid) from userdocuments ' . $filter;

    $total = 0;
    $totalpages = 0;
    $dbresult = db_execute($sql);
    if ($dbresult) {
        if (mysqli_num_rows($dbresult)) {
            list($total) = mysqli_fetch_row($dbresult);
        }

        $totalpages = ceil($total / $perpage);
        if ($page < 0 || $page > $totalpages) {
            $page = 1;
        }

        $limit = 'limit ' . ($page*$perpage-$perpage) . ',' . $perpage;

        $sql = 'select udocumentid,visible,name,status,date_format(createdate,\'%c/%e/%Y %l:%i %p\') as createdate from userdocuments ' . $filter;
        $sql .= ' order by ' . $sortcol . ' ' . $sort . ',createdate desc,name desc ' . $limit;
        $dbresult = db_execute($sql);
    } ?>
	<div class="row">
		<div class="col-md-4">
			<input name="document_name" id="document_name" type="text" placeholder="Document Name">
				<div class="input-checkbox input-checkbox--switch">
					<input id="client_documents_visible" type="checkbox" name="client_documents_visible" />
					<label for="client_documents_visible"></label>
				</div>
				<span class="text-xs">Visible to Client</span>
				<span class="btn btn--primary fileinput-button" style="display: block;">
				<i class="fa fa-upload"></i>
				<span>Upload Document</span>
				<input id="document_upload" type="file" name="files[]" accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document|application/pdf">
			</span>
		</div>

		<div class="col-md-8">
			<div class="table-responsive">
				<table class="table table-striped table-hover dataTable" data-type="client_documents"><thead><tr>
	<?php

    $arrTHs = array();
    // sortcol,content
    $arrTHs[] = array('name','Name');
    $arrTHs[] = array('createdate','Createdate');
    $arrTHs[] = array('status','Status');
    $arrTHs[] = array('','&nbsp;');
    foreach ($arrTHs as $idx => $arrTH) {
        $class = 'sorting';
        if ($rawsortcol == $arrTH[0]) {
            if ($sort == 'asc') {
                $class = 'sorting_asc';
            } else {
                $class = 'sorting_desc';
            }
        } elseif (!$arrTH[0]) {
            $class = '';
        } ?><th class="<?=$class; ?>" data-sort="<?=$arrTH[0]; ?>"><?=$arrTH[1]; ?></th><?php
    } ?></tr></thead><tbody><?php

    if (!$total) {
        ?><tr><td colspan="4" style="text-align: center;" class="muted">[ No Documents Matched ]</td></tr><?php
    }

    if ($dbresult && mysqli_num_rows($dbresult)) {
        while ($row = mysqli_fetch_assoc($dbresult)) {
            //			if (!$row['finaldate']) {$row['finaldate'] = '<label class="label label-danger">INCOMPLETE</label>';} ?><tr class="link_viewdocument" data-udocumentid="<?=$row['udocumentid']; ?>">
			<td><?=$row['name']; ?></td>
			<td><span class="text-xs"><?=$row['createdate']; ?></span></td>
			<td>
				<?php
                    if ($row['status'] == '0') {
                        ?><label class="label label-danger">PENDING</label><?php
                    } elseif ($row['status'] == '1') {
                        ?><label class="label label-success">APPROVED</label><?php
                    } elseif ($row['status'] == '2') {
                        ?><label class="label label-primary">FINAL</label><?php
                    } ?>
			</td><td>
				<a data-docid="<?=$row['udocumentid']; ?>" class="btn-download btn btn--xs btn--primary"><i class="fa fa-download"></i></a>
			</td></tr><?php
        }
    } ?></tr></tbody></table></div><?php

    $shownstart = ($page*$perpage-$perpage);
    if ($total) {
        $shownstart++;
    }
    $shown = ($page*$perpage);
    if ($shown > $total) {
        $shown = $total;
    } ?><div class="col-md-6 paging text-xs">Showing <?=$shownstart; ?> to <?=$shown; ?> of <?=$total; ?> documents</div><?php

    if ($totalpages > 0) {
        $prevpages = 2;

        if (($totalpages - $page) < 2) {
            $prevpages += 2 - ($totalpages - $page);
        }

        $arrPages = array();
        for ($x = $prevpages; $x > 0; $x--) {
            if (($page - $x) > 0) {
                $arrPages[] = ($page - $x);
            }
        }
        $arrPages[] = $page;
        for ($x = 1; $x <= 4; $x++) {
            if (count($arrPages) < 5) {
                if (($page + $x) <= $totalpages) {
                    $arrPages[] = ($page + $x);
                }
            }
        } ?>
		<div class="col-md-6 paging" style="text-align: right;"><?php

        $class = '';
        if ($page < 2) {
            $class = ' disabled';
        } ?><a class="btn btn--xs btn--primary <?=$class; ?>" data-page="<?=($page-1); ?>" data-type="client_documents"><span class="btn__text">PREVIOUS</span></a><?php
        foreach ($arrPages as $curpage) {
            $class = 'btn--secondary';
            if ($page == $curpage) {
                $class = 'btn--primary-2';
            } ?><a class="btn btn--xs <?=$class; ?>" data-page="<?=$curpage; ?>" data-type="client_documents"><span class="btn__text"><?=$curpage; ?></span></a><?php
        }
        $class = '';
        if ($page == $totalpages) {
            $class = ' disabled';
        } ?><a class="btn btn--xs btn--primary <?=$class; ?>" data-page="<?=($page+1); ?>" data-type="client_documents"><span class="btn__text">NEXT</span></a><?php
        ?></div><?php
    } ?></div></div><?php

    $json['html'] = ob_get_clean();
    return json_encode($json);
}

function list_client_forms()
{
    $json = array();
    $json['error'] = '';
    $json['html'] = '';

    if ($GLOBALS['USER']['accesslevel'] < 5) {
        $json['error'] = 'Invalid Permissions.';
        return json_encode($json);
    }
    ob_start();

    $filter = ' where 1=1 ';
    if (isset($_POST['filter']) && $_POST['filter'] && preg_match('/^[0-9]{1,18}$/', $_POST['filter'])) {
        $filter .= ' and us.userid=' . $_POST['filter'];
    }

    if (isset($_POST['viewactive']) && $_POST['viewactive']) {
        $filter .= ' and us.active = 1';
    }

    $page = 1;
    $sortcol = 'finaldate';
    $sort = 'desc';
    $perpage = 10;
    $rawsortcol = 'finaldate';

    if (isset($_POST['perpage']) && preg_match('/^[0-9]{2,3}$/', $_POST['perpage'])) {
        $perpage = $_POST['perpage'];
    }
    if (isset($_POST['page']) && preg_match('/^[0-9]{1,10}$/', $_POST['page'])) {
        $page = $_POST['page'];
    }
    if (isset($_POST['sort']) && ($_POST['sort'] === 'desc' || $_POST['sort'] === 'asc')) {
        $sort = $_POST['sort'];
    }
    $arrSorts = array('name' => 's.name','active' => 'us.active','startdate' => 'us.startdate','finaldate' => 'us.finaldate','status' => 'us.status');
    if (isset($_POST['sortcol']) && isset($arrSorts[$_POST['sortcol']])) {
        $sortcol = $arrSorts[$_POST['sortcol']];
        $rawsortcol = $_POST['sortcol'];
    } else {
        $sortcol = $arrSorts[$sortcol];
    }

    $sql = 'select count(usurveyid) from usersurveys us left join surveys s on us.surveyid = s.surveyid left join users u on us.usurveyid = u.userid ' . $filter;
    $total = 0;
    $totalpages = 0;
    $dbresult = db_execute($sql);
    if ($dbresult) {
        if (mysqli_num_rows($dbresult)) {
            list($total) = mysqli_fetch_row($dbresult);
        }

        $totalpages = ceil($total / $perpage);
        if ($page < 0 || $page > $totalpages) {
            $page = 1;
        }

        $limit = 'limit ' . ($page*$perpage-$perpage) . ',' . $perpage;

        $sql = 'select us.usurveyid,s.name,date_format(us.startdate,\'%c/%e/%Y %l:%i %p\') as startdate,date_format(us.finaldate,\'%c/%e/%Y %l:%i %p\') as finaldate,us.status,us.active from usersurveys us left join surveys s on us.surveyid = s.surveyid left join users u on usurveyid = u.userid ' . $filter . ' order by ' . $sortcol . ' ' . $sort . ',startdate desc ' . $limit;
        $dbresult = db_execute($sql);
    } ?><div class="table-responsive">
		<table class="table table-striped table-hover dataTable" data-type="client_forms"><thead><tr>
	<?php

    $arrTHs = array();
    // sortcol,content
    $arrTHs[] = array('active','&nbsp;');
    $arrTHs[] = array('name','Form');
    $arrTHs[] = array('startdate','Started');
    $arrTHs[] = array('status','Status');
    foreach ($arrTHs as $idx => $arrTH) {
        $class = 'sorting';
        if ($rawsortcol == $arrTH[0]) {
            if ($sort == 'asc') {
                $class = 'sorting_asc';
            } else {
                $class = 'sorting_desc';
            }
        } elseif (!$arrTH[0]) {
            $class = '';
        } ?><th class="<?=$class; ?>" data-sort="<?=$arrTH[0]; ?>"><?=$arrTH[1]; ?></th><?php
    } ?></tr></thead><tbody><?php

    if (!$total) {
        ?><tr><td colspan="5" style="text-align: center;" class="muted">[ No Forms Matched ]</td></tr><?php
    }

    if ($dbresult && mysqli_num_rows($dbresult)) {
        while ($row = mysqli_fetch_assoc($dbresult)) {
            if (!$row['finaldate']) {
                $row['finaldate'] = '<label class="label label-danger">INCOMPLETE</label>';
            } ?><tr class="link_viewclientform" data-usurveyid="<?=$row['usurveyid']; ?>">
				<td>
					<?php if ($row['active']) { ?>
						<a class="btn btn-danger delete_usurvey" data-usurveyid="<?=$row['usurveyid'];?>"><i class="fa fa-times"></i></a>
					<?php } else { ?>
						<a class="btn btn-success undelete_usurvey" data-usurveyid="<?=$row['usurveyid'];?>"><i class="fa fa-undo"></i></a>
					<?php } ?>
				</td>
				<td><?=$row['name']; ?></td>
				<td><span class="text-xs"><?=$row['startdate']; ?></span></td>
				<td>
					<?php
                        if ($row['status'] == '0') {
                            ?><label class="label label-danger">INCOMPLETE</label><?php
                        } elseif ($row['status'] == '1') {
                            ?><label class="label label-success">COMPLETE</label><?php
                        } elseif ($row['status'] == '2') {
                            ?><label class="label label-primary">FINAL</label><?php
                        } ?>
				</td><td>
			<?php
        }
    } ?></tr></tbody></table></div><?php

    $shownstart = ($page*$perpage-$perpage);
    if ($total) {
        $shownstart++;
    }
    $shown = ($page*$perpage);
    if ($shown > $total) {
        $shown = $total;
    } ?><div class="col-md-6 paging text-xs">Showing <?=$shownstart; ?> to <?=$shown; ?> of <?=$total; ?> forms</div><?php

    if ($totalpages > 0) {
        $prevpages = 2;

        if (($totalpages - $page) < 2) {
            $prevpages += 2 - ($totalpages - $page);
        }

        $arrPages = array();
        for ($x = $prevpages; $x > 0; $x--) {
            if (($page - $x) > 0) {
                $arrPages[] = ($page - $x);
            }
        }
        $arrPages[] = $page;
        for ($x = 1; $x <= 4; $x++) {
            if (count($arrPages) < 5) {
                if (($page + $x) <= $totalpages) {
                    $arrPages[] = ($page + $x);
                }
            }
        } ?><div class="col-md-6 paging" style="text-align: right;"><?php

        $class = '';
        if ($page < 2) {
            $class = ' disabled';
        } ?><a class="btn btn--xs btn--primary <?=$class; ?>" data-page="<?=($page-1); ?>" data-type="client_forms"><span class="btn__text">PREVIOUS</span></a><?php
        foreach ($arrPages as $curpage) {
            $class = 'btn--secondary';
            if ($page == $curpage) {
                $class = 'btn--primary-2';
            } ?><a class="btn btn--xs <?=$class; ?>" data-page="<?=$curpage; ?>" data-type="client_forms"><span class="btn__text"><?=$curpage; ?></span></a><?php
        }
        $class = '';
        if ($page == $totalpages) {
            $class = ' disabled';
        } ?><a class="btn btn--xs btn--primary <?=$class; ?>" data-page="<?=($page+1); ?>" data-type="client_forms"><span class="btn__text">NEXT</span></a><?php
    }

    $json['html'] = ob_get_clean();
    return json_encode($json);
}

function list_forms()
{
    $json = array();
    $json['error'] = '';

    if ($GLOBALS['USER']['accesslevel'] < 5) {
        $json['error'] = 'Invalid Permissions.';
        return json_encode($json);
    }
    ob_start();

    $filter = ' where 1=1 ';

    /*
    if (isset($_POST['filter']) && $_POST['filter']) {
        $_POST['filter'] = substr($_POST['filter'],0,100);
        $_POST['filter'] = preg_replace('/[\-\(\)]/','',$_POST['customer']);
        $_POST['filter'] = escape(strtolower($_POST['filter']));
        $filter .= ' and (lower(u.first_name) like "%' . $_POST['filter'] . '%" or lower(u.last_name) like "%' . $_POST['filter'] . '%" or lower(u.email) like "%' . $_POST['filter'] . '%")';
    }
    */

    if (isset($_POST['viewactive']) && $_POST['viewactive']) {
        $filter .= ' and s.active = 1';
    }

    $page = 1;
    $sortcol = 's.name';
    $sort = 'asc';
    $perpage = 1;
    $rawsortcol = 's.name';

    if (isset($_POST['perpage']) && preg_match('/^[0-9]{2,3}$/', $_POST['perpage'])) {
        $perpage = $_POST['perpage'];
    }
    if (isset($_POST['page']) && preg_match('/^[0-9]{1,10}$/', $_POST['page'])) {
        $page = $_POST['page'];
    }
    if (isset($_POST['sort']) && ($_POST['sort'] === 'desc' || $_POST['sort'] === 'asc')) {
        $sort = $_POST['sort'];
    }
    $arrSorts = array('name' => 's.name','cost' => 's.cost','sellonline' => 's.sellonline','active' => 's.active','createdate' => 's.createdate');
    if (isset($_POST['sortcol']) && isset($arrSorts[$_POST['sortcol']])) {
        $sortcol = $arrSorts[$_POST['sortcol']];
        $rawsortcol = $_POST['sortcol'];
    } else {
        $sortcol = $arrSorts[$sortcol];
    }

    $sql = 'select count(surveyid) from surveys s ' . $filter;

    $total = 0;
    $totalpages = 0;
    $dbresult = db_execute($sql);
    if ($dbresult) {
        if (mysqli_num_rows($dbresult)) {
            list($total) = mysqli_fetch_row($dbresult);
        }

        $totalpages = ceil($total / $perpage);
        if ($page < 0 || $page > $totalpages) {
            $page = 1;
        }

        $limit = 'limit ' . ($page*$perpage-$perpage) . ',' . $perpage;

        $sql = 'select u.first_name,u.last_name,s.surveyid,s.name,s.cost,s.sellonline,s.active,date_format(s.createdate,\'%c/%e/%Y %l:%i %p\') as createdate from surveys s left join users u on s.lawyerid = u.userid ' . $filter . ' order by ' . $sortcol . ' ' . $sort . ',s.name desc ' . $limit;
        $dbresult = db_execute($sql);
    } ?><div class="table-responsive">
		<table class="table table-striped table-hover dataTable" data-type="forms"><thead><tr>
	<?php

    $arrTHs = array();
    // sortcol,content
    $arrTHs[] = array('name','Name');
    $arrTHs[] = array('cost','Price');
    $arrTHs[] = array('last_name','Attorney');
    $arrTHs[] = array('createdate','Created');
    $arrTHs[] = array('active','&nbsp;');
    foreach ($arrTHs as $idx => $arrTH) {
        $class = 'sorting';
        if ($rawsortcol == $arrTH[0]) {
            if ($sort == 'asc') {
                $class = 'sorting_asc';
            } else {
                $class = 'sorting_desc';
            }
        } elseif (!$arrTH[0]) {
            $class = '';
        } ?><th class="<?=$class; ?>" data-sort="<?=$arrTH[0]; ?>"><?=$arrTH[1]; ?></th><?php
    } ?></tr></thead><tbody><?php

    if (!$total) {
        ?><tr><td colspan="4" style="text-align: center;" class="muted">[ No Forms Matched ]</td></tr><?php
    }

    if ($dbresult && mysqli_num_rows($dbresult)) {
        while ($row = mysqli_fetch_assoc($dbresult)) {
            ?><tr class="link_editform" data-surveyid="<?=$row['surveyid']; ?>">
			<td><?=$row['name']; ?></td>
			<td>
				<?php if ($row['sellonline']) {
                echo '$' . $row['cost'];
            } else {
                ?><span class="muted text-xs">NOT SOLD ONLINE</span><?php
            } ?>
			</td>
			<td><span class="text-xs"><?=$row['first_name']; ?> <?=$row['last_name']; ?></span></td>
			<td><span class="text-xs"><?=$row['createdate']; ?></span></td>
			<td><?=format_status($row['active']); ?></td><?php
        }
    } ?></tr></tbody></table></div><?php

    $shownstart = ($page*$perpage-$perpage);
    if ($total) {
        $shownstart++;
    }
    $shown = ($page*$perpage);
    if ($shown > $total) {
        $shown = $total;
    } ?><div class="col-md-6 paging text-xs">Showing <?=$shownstart; ?> to <?=$shown; ?> of <?=$total; ?> forms</div><?php

    if ($totalpages > 0) {
        $prevpages = 2;

        if (($totalpages - $page) < 2) {
            $prevpages += 2 - ($totalpages - $page);
        }

        $arrPages = array();
        for ($x = $prevpages; $x > 0; $x--) {
            if (($page - $x) > 0) {
                $arrPages[] = ($page - $x);
            }
        }
        $arrPages[] = $page;
        for ($x = 1; $x <= 4; $x++) {
            if (count($arrPages) < 5) {
                if (($page + $x) <= $totalpages) {
                    $arrPages[] = ($page + $x);
                }
            }
        } ?><div class="col-md-6 paging" style="text-align: right;"><?php

        $class = '';
        if ($page < 2) {
            $class = ' disabled';
        } ?><a class="btn btn--xs btn--primary <?=$class; ?>" data-page="<?=($page-1); ?>" data-type="forms"><span class="btn__text">PREVIOUS</span></a><?php
        foreach ($arrPages as $curpage) {
            $class = 'btn--secondary';
            if ($page == $curpage) {
                $class = 'btn--primary-2';
            } ?><a class="btn btn--xs <?=$class; ?>" data-page="<?=$curpage; ?>" data-type="forms"><span class="btn__text"><?=$curpage; ?></span></a><?php
        }
        $class = '';
        if ($page == $totalpages) {
            $class = ' disabled';
        } ?><a class="btn btn--xs btn--primary <?=$class; ?>" data-page="<?=($page+1); ?>" data-type="forms"><span class="btn__text">NEXT</span></a><?php
    }

    $json['html'] = ob_get_clean();
    return json_encode($json);
}

function list_form_templates()
{
    $json = array();
    $json['error'] = '';

    if ($GLOBALS['USER']['accesslevel'] < 5) {
        $json['error'] = 'Invalid Permissions.';
        return json_encode($json);
    }
    ob_start();

    $filter = ' where 1=1 ';

    if (isset($_POST['filter']) && $_POST['filter'] && preg_match('/^[0-9]{1,18}$/', $_POST['filter'])) {
        $filter .= ' and surveyid=' . $_POST['filter'];
    }

    $page = 1;
    $sortcol = 'name';
    $sort = 'asc';
    $perpage = 1;
    $rawsortcol = 'name';

    if (isset($_POST['perpage']) && preg_match('/^[0-9]{2,3}$/', $_POST['perpage'])) {
        $perpage = $_POST['perpage'];
    }
    if (isset($_POST['page']) && preg_match('/^[0-9]{1,10}$/', $_POST['page'])) {
        $page = $_POST['page'];
    }
    if (isset($_POST['sort']) && ($_POST['sort'] === 'desc' || $_POST['sort'] === 'asc')) {
        $sort = $_POST['sort'];
    }
    $arrSorts = array('name' => 'name','cost' => 'cost','sellonline' => 'sellonline','active' => 'active');
    if (isset($_POST['sortcol']) && isset($arrSorts[$_POST['sortcol']])) {
        $sortcol = $arrSorts[$_POST['sortcol']];
        $rawsortcol = $_POST['sortcol'];
    } else {
        $sortcol = $arrSorts[$sortcol];
    }

    $sql = 'select count(templateid) from templates ' . $filter;

    $total = 0;
    $totalpages = 0;
    $dbresult = db_execute($sql);
    if ($dbresult) {
        if (mysqli_num_rows($dbresult)) {
            list($total) = mysqli_fetch_row($dbresult);
        }

        $totalpages = ceil($total / $perpage);
        if ($page < 0 || $page > $totalpages) {
            $page = 1;
        }

        $limit = 'limit ' . ($page*$perpage-$perpage) . ',' . $perpage;

        $sql = 'select templateid,name,date_format(createdate,\'%c/%e/%Y %l:%i %p\') as createdate from templates ' . $filter . ' order by ' . $sortcol . ' ' . $sort . ',name desc ' . $limit;
        $dbresult = db_execute($sql);
    } ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="list-group iconlist">
	<?php

    if (!$total) {
        ?><li class="list-group-item" style="text-align: center;" class="muted">[ No Templates ]</li><?php
    } ?>
		<style type="text/css">
			.iconlist > li {padding: 0px;}
			.iconlist > li > .btn {padding-left: 20px; padding-right: 20px; margin-right: 20px;}
		</style>
<?php
    if ($dbresult && mysqli_num_rows($dbresult)) {
        while ($row = mysqli_fetch_assoc($dbresult)) {
            ?><li class="list-group-item"><a class="btn btn-danger delete_template" data-templateid="<?=$row['templateid']; ?>"><i class="fa fa-times"></i></a> <?=$row['name']; ?> <span class="text-xs"><?=$row['createdate']; ?></span></li><?php
        }
    } ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<span class="btn btn--primary fileinput-button">
				<i class="fa fa-upload"></i>
				<span>Upload Template</span>
				<input id="template_upload" type="file" name="files[]" accept="application/vnd.openxmlformats-officedocument.wordprocessingml.document">
			</span>
		</div>
	</div>
	<?php
    $json['html'] = ob_get_clean();
    return json_encode($json);
}

function list_myforms()
{
    $json = array();
    $json['error'] = '';

    ob_start();

    $filter = ' where us.userid = ' . $GLOBALS['USER']['userid'];

    /*
    if (isset($_POST['filter']) && $_POST['filter']) {
        $_POST['filter'] = substr($_POST['filter'],0,100);
        $_POST['filter'] = preg_replace('/[\-\(\)]/','',$_POST['customer']);
        $_POST['filter'] = escape(strtolower($_POST['filter']));
        $filter .= ' and (lower(first_name) like "%' . $_POST['filter'] . '%" or lower(last_name) like "%' . $_POST['filter'] . '%" or lower(email) like "%' . $_POST['filter'] . '%")';
    }
    */

    if (isset($_POST['viewincomplete']) && $_POST['viewincomplete']) {
        $filter .= ' and us.status = 0';
    }

    $page = 1;
    $sortcol = 'startdate';
    $sort = 'desc';
    $perpage = 1;
    $rawsortcol = 'startdate';

    if (isset($_POST['perpage']) && preg_match('/^[0-9]{2,3}$/', $_POST['perpage'])) {
        $perpage = $_POST['perpage'];
    }
    if (isset($_POST['page']) && preg_match('/^[0-9]{1,10}$/', $_POST['page'])) {
        $page = $_POST['page'];
    }
    if (isset($_POST['sort']) && ($_POST['sort'] === 'desc' || $_POST['sort'] === 'asc')) {
        $sort = $_POST['sort'];
    }
    $arrSorts = array('survey_name' => 's.name','startdate' => 'us.startdate','finaldate' => 'us.finaldate');
    if (isset($_POST['sortcol']) && isset($arrSorts[$_POST['sortcol']])) {
        $sortcol = $arrSorts[$_POST['sortcol']];
        $rawsortcol = $_POST['sortcol'];
    } else {
        $sortcol = $arrSorts[$sortcol];
    }

    $sql = 'select count(usurveyid) from usersurveys us ' . $filter;

    $total = 0;
    $totalpages = 0;
    $dbresult = db_execute($sql);
    if ($dbresult) {
        if (mysqli_num_rows($dbresult)) {
            list($total) = mysqli_fetch_row($dbresult);
        }

        $totalpages = ceil($total / $perpage);
        if ($page < 0 || $page > $totalpages) {
            $page = 1;
        }

        $limit = 'limit ' . ($page*$perpage-$perpage) . ',' . $perpage;

        $sql = 'select us.usurveyid,s.name,date_format(us.startdate,\'%c/%e/%Y %l:%i %p\') as startdate,date_format(us.finaldate,\'%c/%e/%Y %l:%i %p\') as finaldate';
        $sql .= ' from usersurveys us left join surveys s on us.surveyid = s.surveyid ' . $filter;
        $sql .= ' order by ' . $sortcol . ' ' . $sort . ',us.startdate desc,us.finaldate desc ' . $limit;
        $dbresult = db_execute($sql);
    } ?><div class="table-responsive">
		<table class="table table-striped table-hover dataTable" data-type="myforms"><thead><tr>
	<?php

    $arrTHs = array();
    // sortcol,content
    $arrTHs[] = array('survey_name','Name');
    $arrTHs[] = array('startdate','Started');
    $arrTHs[] = array('finaldate','Finalized');
    foreach ($arrTHs as $idx => $arrTH) {
        $class = 'sorting';
        if ($rawsortcol == $arrTH[0]) {
            if ($sort == 'asc') {
                $class = 'sorting_asc';
            } else {
                $class = 'sorting_desc';
            }
        } elseif (!$arrTH[0]) {
            $class = '';
        } ?><th class="<?=$class; ?>" data-sort="<?=$arrTH[0]; ?>"><?=$arrTH[1]; ?></th><?php
    } ?></tr></thead><tbody><?php

    if (!$total) {
        ?><tr><td colspan="5" style="text-align: center;" class="muted">[ No Forms Matched ]</td></tr><?php
    }

    if ($dbresult && mysqli_num_rows($dbresult)) {
        while ($row = mysqli_fetch_assoc($dbresult)) {
            if (!$row['finaldate']) {
                $row['finaldate'] = '<label class="label label-danger">INCOMPLETE</label>';
            } ?><tr class="link_viewform" data-usurveyid="<?=$row['usurveyid']; ?>">
				<td><?=$row['name']; ?></td><td><span class="text-xs"><?=$row['startdate']; ?></span></td>
				<td><span class="text-xs"><?=$row['finaldate']; ?></span></td><?php
        }
    } ?></tr></tbody></table></div><?php

    $shownstart = ($page*$perpage-$perpage);
    if ($total) {
        $shownstart++;
    }
    $shown = ($page*$perpage);
    if ($shown > $total) {
        $shown = $total;
    } ?><div class="col-md-6 paging text-xs">Showing <?=$shownstart; ?> to <?=$shown; ?> of <?=$total; ?> forms</div><?php

    if ($totalpages > 0) {
        $prevpages = 2;

        if (($totalpages - $page) < 2) {
            $prevpages += 2 - ($totalpages - $page);
        }

        $arrPages = array();
        for ($x = $prevpages; $x > 0; $x--) {
            if (($page - $x) > 0) {
                $arrPages[] = ($page - $x);
            }
        }
        $arrPages[] = $page;
        for ($x = 1; $x <= 4; $x++) {
            if (count($arrPages) < 5) {
                if (($page + $x) <= $totalpages) {
                    $arrPages[] = ($page + $x);
                }
            }
        } ?><div class="col-md-6 paging" style="text-align: right;"><?php

        $class = '';
        if ($page < 2) {
            $class = ' disabled';
        } ?><a class="btn btn--xs btn--primary <?=$class; ?>" data-page="<?=($page-1); ?>" data-type="myforms"><span class="btn__text">PREVIOUS</span></a><?php
        foreach ($arrPages as $curpage) {
            $class = 'btn--secondary';
            if ($page == $curpage) {
                $class = 'btn--primary-2';
            } ?><a class="btn btn--xs <?=$class; ?>" data-page="<?=$curpage; ?>" data-type="myforms"><span class="btn__text"><?=$curpage; ?></span></a><?php
        }
        $class = '';
        if ($page == $totalpages) {
            $class = ' disabled';
        } ?><a class="btn btn--xs btn--primary <?=$class; ?>" data-page="<?=($page+1); ?>" data-type="myforms"><span class="btn__text">NEXT</span></a><?php
    }

    $json['html'] = ob_get_clean();
    return json_encode($json);
}

function list_mydocuments()
{
    $json = array();
    $json['error'] = '';

    ob_start();

    $filter = ' where userid = ' . $GLOBALS['USER']['userid'];

    /*
    if (isset($_POST['filter']) && $_POST['filter']) {
        $_POST['filter'] = substr($_POST['filter'],0,100);
        $_POST['filter'] = preg_replace('/[\-\(\)]/','',$_POST['customer']);
        $_POST['filter'] = escape(strtolower($_POST['filter']));
        $filter .= ' and (lower(first_name) like "%' . $_POST['filter'] . '%" or lower(last_name) like "%' . $_POST['filter'] . '%" or lower(email) like "%' . $_POST['filter'] . '%")';
    }
    */

    $page = 1;
    $sortcol = 'createdate';
    $sort = 'desc';
    $perpage = 1;
    $rawsortcol = 'createdate';

    if (isset($_POST['perpage']) && preg_match('/^[0-9]{2,3}$/', $_POST['perpage'])) {
        $perpage = $_POST['perpage'];
    }
    if (isset($_POST['page']) && preg_match('/^[0-9]{1,10}$/', $_POST['page'])) {
        $page = $_POST['page'];
    }
    if (isset($_POST['sort']) && ($_POST['sort'] === 'desc' || $_POST['sort'] === 'asc')) {
        $sort = $_POST['sort'];
    }
    $arrSorts = array('name' => 'name','createdate' => 'createdate','status' => 'status');
    if (isset($_POST['sortcol']) && isset($arrSorts[$_POST['sortcol']])) {
        $sortcol = $arrSorts[$_POST['sortcol']];
        $rawsortcol = $_POST['sortcol'];
    } else {
        $sortcol = $arrSorts[$sortcol];
    }

    $sql = 'select count(udocumentid) from userdocuments ' . $filter;

    $total = 0;
    $totalpages = 0;
    $dbresult = db_execute($sql);
    if ($dbresult) {
        if (mysqli_num_rows($dbresult)) {
            list($total) = mysqli_fetch_row($dbresult);
        }

        $totalpages = ceil($total / $perpage);
        if ($page < 0 || $page > $totalpages) {
            $page = 1;
        }

        $limit = 'limit ' . ($page*$perpage-$perpage) . ',' . $perpage;

        $sql = 'select udocumentid,name,status,date_format(createdate,\'%c/%e/%Y %l:%i %p\') as createdate from userdocuments ' . $filter;
        $sql .= ' order by ' . $sortcol . ' ' . $sort . ',createdate desc,name desc ' . $limit;
        $dbresult = db_execute($sql);
    } ?><div class="table-responsive">
		<table class="table table-striped table-hover dataTable" data-type="mydocuments"><thead><tr>
	<?php

    $arrTHs = array();
    // sortcol,content
    $arrTHs[] = array('name','Name');
    $arrTHs[] = array('createdate','Createdate');
    $arrTHs[] = array('status','Status');
    $arrTHs[] = array('','&nbsp;');
    foreach ($arrTHs as $idx => $arrTH) {
        $class = 'sorting';
        if ($rawsortcol == $arrTH[0]) {
            if ($sort == 'asc') {
                $class = 'sorting_asc';
            } else {
                $class = 'sorting_desc';
            }
        } elseif (!$arrTH[0]) {
            $class = '';
        } ?><th class="<?=$class; ?>" data-sort="<?=$arrTH[0]; ?>"><?=$arrTH[1]; ?></th><?php
    } ?></tr></thead><tbody><?php

    if (!$total) {
        ?><tr><td colspan="4" style="text-align: center;" class="muted">[ No Documents Matched ]</td></tr><?php
    }

    if ($dbresult && mysqli_num_rows($dbresult)) {
        while ($row = mysqli_fetch_assoc($dbresult)) {
            //			if (!$row['finaldate']) {$row['finaldate'] = '<label class="label label-danger">INCOMPLETE</label>';} ?><tr class="link_viewdocument" data-udocumentid="<?=$row['udocumentid']; ?>">
			<td><?=$row['name']; ?></td><td class="text-xs"><?=$row['createdate']; ?></td>
			<td>
				<?php
                    if ($row['status'] == '0') {
                        ?><label class="label label-danger">PENDING</label><?php
                    } elseif ($row['status'] == '1') {
                        ?><label class="label label-danger">APPROVED</label><?php
                    } elseif ($row['status'] == '2') {
                        ?><label class="label label-danger">FINAL</label><?php
                    } ?>
			</td><td>
				<?php if (file_exists('clientfiles/document_' . $row['udocumentid'] . '.pdf')) { ?>
					<a data-docid="<?=$row['udocumentid'];?>" class="btn-preview btn btn--xs btn--primary"><i class="fa fa-download"></i></a>
				<?php } ?>
			</td></tr><?php
        }
    } ?></tr></tbody></table></div><?php

    $shownstart = ($page*$perpage-$perpage);
    if ($total) {
        $shownstart++;
    }
    $shown = ($page*$perpage);
    if ($shown > $total) {
        $shown = $total;
    } ?><div class="col-md-6 paging text-xs">Showing <?=$shownstart; ?> to <?=$shown; ?> of <?=$total; ?> documents</div><?php

    if ($totalpages > 0) {
        $prevpages = 2;

        if (($totalpages - $page) < 2) {
            $prevpages += 2 - ($totalpages - $page);
        }

        $arrPages = array();
        for ($x = $prevpages; $x > 0; $x--) {
            if (($page - $x) > 0) {
                $arrPages[] = ($page - $x);
            }
        }
        $arrPages[] = $page;
        for ($x = 1; $x <= 4; $x++) {
            if (count($arrPages) < 5) {
                if (($page + $x) <= $totalpages) {
                    $arrPages[] = ($page + $x);
                }
            }
        } ?><div class="col-md-6 paging" style="text-align: right;"><?php

        $class = '';
        if ($page < 2) {
            $class = ' disabled';
        } ?><a class="btn btn--xs btn--primary <?=$class; ?>" data-page="<?=($page-1); ?>" data-type="mydocuments"><span class="btn__text">PREVIOUS</span></a><?php
        foreach ($arrPages as $curpage) {
            $class = 'btn--secondary';
            if ($page == $curpage) {
                $class = 'btn--primary-2';
            } ?><a class="btn btn--xs <?=$class; ?>" data-page="<?=$curpage; ?>" data-type="mydocuments"><span class="btn__text"><?=$curpage; ?></span></a><?php
        }
        $class = '';
        if ($page == $totalpages) {
            $class = ' disabled';
        } ?><a class="btn btn--xs btn--primary <?=$class; ?>" data-page="<?=($page+1); ?>" data-type="mydocuments"><span class="btn__text">NEXT</span></a><?php
    }

    $json['html'] = ob_get_clean();
    return json_encode($json);
}

function account_update()
{
    $errors = array();
    $updatepw = 0;
    if (!isset($_POST['first_name']) || !$_POST['first_name']) {
        $errors[] = 'First Name is required.';
    }
    if (!isset($_POST['last_name']) || !$_POST['last_name']) {
        $errors[] = 'Last Name is required.';
    }
    if (!isset($_POST['email']) || !$_POST['email']) {
        $errors[] = 'Email is required.';
    }
    if (!isset($_POST['address']) || !$_POST['address']) {
        $errors[] = 'Address is required.';
    }
    if (!isset($_POST['city']) || !$_POST['city']) {
        $errors[] = 'City is required.';
    }
    if (!isset($_POST['state']) || !$_POST['state']) {
        $errors[] = 'State is required.';
    }
    if (!isset($_POST['zip']) || !$_POST['zip']) {
        $errors[] = 'Zip is required.';
    }
    if (!isset($_POST['phone']) || !$_POST['phone']) {
        $errors[] = 'Phone number is required.';
    }

    if (isset($_POST['password1']) || isset($_POST['password2'])) {
        if ($_POST['password1'] || $_POST['password2']) {
            $updatepw = 1;
            if ($_POST['password1'] !== $_POST['password2']) {
                $errors[] = 'New Passwords do not match.';
            }
        }
    }
    if (count($errors)) {
        echo implode("<br>", $errors);
    } else {
        // did they enter the correct password?
        // they must put in their password to change their password
        if (isset($_POST['password1']) && $_POST['password1']) {
            if (!isset($_POST['password']) || !$_POST['password']) {
                echo 'You must enter your current password in order to change it.';
                exit;
            } else {
                $dbresult = db_execute('select 1 from users where userid=' . $GLOBALS['USER']['userid'] . ' and password="' . hash_hmac('sha256', strtolower($_POST['password']), 'a20' . $GLOBALS['salt']) . '"');
                if ($dbresult) {
                    if (!mysqli_num_rows($dbresult)) {
                        ?>Invalid password entered.<?php
                        exit;
                    }
                } else {
                    ?>There was an error processing your request.  Please try again later.<?php
                    exit;
                }
            }
        }
        // make sure a different account doesn't exist with the new email address
        $dbresult = db_execute('select active from users where email="' . escape(strtolower($_POST['email'])) . '" and userid != ' . $GLOBALS['USER']['userid']);
        if ($dbresult) {
            if (mysqli_num_rows($dbresult)) {
                ?>A different account already exists with the new email address.  Each account must have a unique email address.<?php
            } else {
                $pwsql = '';
                if ($updatepw) {
                    $pwsql = ',password="' . hash_hmac('sha256', strtolower($_POST['password1']), 'a20' . $GLOBALS['salt']) . '"';
                }
                db_execute('update users set first_name="' . escape($_POST['first_name']) . '", last_name="' . escape($_POST['last_name']) . '",email="' . escape(strtolower($_POST['email'])) . '",address="' . escape(strtolower($_POST['address'])) . '",city="' . escape(strtolower($_POST['city'])) . '",state="' . escape(strtolower($_POST['state'])) . '",zip="' . escape(strtolower($_POST['zip'])) . '",phone="' . escape(strtolower($_POST['phone'])) . '" where userid=' . $GLOBALS['USER']['userid']);

                if ($GLOBALS['USER']['accesslevel'] > 3) {
                    if (!isset($_POST['attorney_firstname'])) {
                        $_POST['attorney_firstname'] = '';
                    }
                    if (!isset($_POST['attorney_lastname'])) {
                        $_POST['attorney_lastname'] = '';
                    }
                    if (!isset($_POST['attorney_email'])) {
                        $_POST['attorney_email'] = '';
                    }
                    if (!isset($_POST['attorney_phone'])) {
                        $_POST['attorney_phone'] = '';
                    }
                    if (!isset($_POST['attorney_company'])) {
                        $_POST['attorney_company'] = '';
                    }
                    db_execute('update users set attorney_firstname="' . escape($_POST['attorney_firstname']) . '",attorney_lastname="' . escape($_POST['attorney_lastname']) . '",attorney_email="' . escape($_POST['attorney_email']) . '",attorney_phone="' . escape($_POST['attorney_phone']) . '",attorney_company="' . escape($_POST['attorney_company']) . '" where userid=' . $GLOBALS['USER']['userid']);
                    //###DEV need testing
                }
                echo 1;
            }
        } else {
            ?>There was an error processing your request.  Please try again later.<?php
        }
    }
}


function usurvey_edit()
{
    $json = array();
    $json['error'] = '';

    $json['survey'] = array();
    $json['survey']['title'] = 'Error Loading Questionnaire';
    $json['survey']['showProgressBar'] = 'top';
    $json['survey']['showQuestionNumbers'] = 'off';
    $json['survey']['pages'] = '[]';
    $json['survey']['data'] = '{}';

    if (isset($_POST['usurveyid']) && preg_match('/^[0-9]{1,18}$/', $_POST['usurveyid'])) {
        $dbresult = db_execute('select us.data,s.name,s.survey from usersurveys us left join surveys s on us.surveyid = s.surveyid where us.userid=' . $GLOBALS['USER']['userid'] . ' and us.usurveyid = ' . $_POST['usurveyid']);
        if ($dbresult) {
            if (mysqli_num_rows($dbresult)) {
                $survey = mysqli_fetch_assoc($dbresult);
                $json['survey']['title'] = $survey['name'];
                $json['survey']['pages'] = json_decode($survey['survey']);
                $json['survey']['data'] = $survey['data'];
            } else {
                $json['error'] = 'Invalid Questionnaire Selected';
            }
        } else {
            $json['error'] = 'There was an error processing your request.  Please try again later.';
        }
    }
    $json['survey']['decodeerror'] = json_last_error();
    return json_encode($json);
}


function document_save()
{
    if ($GLOBALS['USER']['accesslevel'] < 5) {
        echo 'Invalid Permissions';
        exit;
    }

    $errors = array();
    $visible = 0;
    if (!isset($_POST['name']) || !$_POST['name']) {
        $errors[] = 'Name is required.';
    }
    if (isset($_POST['visible']) && $_POST['visible']) {
        $visible = 1;
    }
    if (!isset($_POST['udocumentid']) || !preg_match('/^[0-9]{1,18}$/', $_POST['udocumentid'])) {
        $errors = array();
        $errors[] = 'Invalid document selected.';
    }

    if (count($errors)) {
        echo implode("<br>", $errors);
    } else {
        $dbresult = db_execute('select 1 from userdocuments where udocumentid=' . $_POST['udocumentid']);
        if ($dbresult) {
            if (mysqli_num_rows($dbresult)) {
                db_execute('update userdocuments set name="' . escape($_POST['name']) . '",visible=' . $visible . ' where udocumentid=' . $_POST['udocumentid']);
                echo 1;
            } else {
                ?>Invalid document selected.<?php
            }
        } else {
            ?>There was an error processing your request.  Please try again later.<?php
        }
    }
    exit;
}


function form_save()
{
    if ($GLOBALS['USER']['accesslevel'] < 5) {
        echo 'Invalid Permissions';
        exit;
    }

    $errors = array();

    $active = 0;
    $sellonline = 0;
    $cost = '0.00';
    $lawyerid = 0;
    if (!isset($_POST['name']) || !$_POST['name']) {
        $errors[] = 'Name is required.';
    }
    if (isset($_POST['form_active']) && $_POST['form_active']) {
        $active = 1;
    }
    if (isset($_POST['form_sellonline']) && $_POST['form_sellonline']) {
        $sellonline = 1;
    }
    if (isset($_POST['lawyerid']) && preg_match('/^[0-9]{1,18}$/', $_POST['lawyerid'])) {
        $lawyerid = $_POST['lawyerid'];
    }
    if (isset($_POST['cost']) && $_POST['cost']) {
        if (preg_match('/^[0-9\.]+$/', $_POST['cost'])) {
            $cost = $_POST['cost'];
        } else {
            $errors[] = 'Cost is an invalid format.  Please enter a dollar amount (eg: 100.00)';
        }
    }
    if (!json_decode($_POST['survey'])) {
        $errors[] = 'Invalid JSON data in form content';
    } else {
        $_POST['survey'] = json_encode(json_decode($_POST['survey']));
    }
    if (!isset($_POST['surveyid']) || !preg_match('/^[0-9]{1,18}$/', $_POST['surveyid'])) {
        $errors = array();
        $errors[] = 'Invalid survey selected.';
    }

    // description, survey

    if (count($errors)) {
        echo implode("<br>", $errors);
    } else {
        $dbresult = db_execute('select 1 from surveys where surveyid=' . $_POST['surveyid']);
        if ($dbresult) {
            if (mysqli_num_rows($dbresult)) {
                db_execute('update surveys set lawyerid=' . $lawyerid . ',name="' . escape($_POST['name']) . '",survey="' . escape($_POST['survey']) . '",cost="' . $_POST['cost'] . '",sellonline=' . $sellonline . ',active=' . $active . ',description="' . escape($_POST['description']) . '" where surveyid=' . $_POST['surveyid']);
                echo 1;
            } else {
                ?>Invalid survey selected.<?php
            }
        } else {
            ?>There was an error processing your request.  Please try again later.<?php
        }
    }
    exit;
}


function document_edit()
{
    $json = array();
    $json['error'] = '';
    $json['html'] = '';
    if ($GLOBALS['USER']['accesslevel'] < 0) {
        $json['error'] = 'Invalid Permissions.';
        return json_encode($json);
    }

    
    ob_start();
    if (isset($_POST['udocumentid']) && preg_match('/^[0-9]{1,18}$/', $_POST['udocumentid'])) {
        $dbresult = db_execute('select udocumentid,name,status,visible from userdocuments where udocumentid=' . $_POST['udocumentid']);
        if ($dbresult) {
            if (mysqli_num_rows($dbresult)) {
                $arrDoc = mysqli_fetch_assoc($dbresult); ?>
				<form name="formDocumentEdit" class="custom-validate" data-success="Document successfully updated." data-error="Please fill in all fields correctly.">
					<input type="hidden" name="a" value="document_save">
					<input type="hidden" name="udocumentid" value="<?=$arrDoc['udocumentid']; ?>">
					<div class="row">
						<span class="h5">Edit Document</span>
						<input name="name" type="text" placeholder="Document Name" class="validate-required" value="<?=$arrDoc['name']; ?>" />
					</div>
					<div class="row" style="padding-left: 20px; margin-top: 10px;">
						<div class="input-checkbox input-checkbox--switch">
							<?php
                            $checked = '';
                if ($arrDoc['visible']) {
                    $checked = ' checked';
                } ?>
							<input id="visible" type="checkbox" name="visible" <?=$checked; ?> />
							<label for="visible"></label>
						</div>
						<span class="text-xs">Visible to Client</span>
					</div>
					<div class="row" style="margin-top: 10px;">
						<button class="btn btn--primary type--uppercase" type="submit">Save Document</button>
					</div>
				</form>
				<?php
            } else {
                $json['error'] = 'Invalid document selected.';
            }
        } else {
            $json['error'] = 'There was an error processing your request.  Please try again later.';
        }
    } else {
        $json['error'] = 'Invalid document selected.';
    }

    $json['html'] = ob_get_clean();
    return json_encode($json);
}

function form_edit()
{
    $json = array();
    $json['error'] = '';
    $json['html'] = '';
    if ($GLOBALS['USER']['accesslevel'] < 5) {
        $json['error'] = 'Invalid Permissions.';
        return json_encode($json);
    }

    ob_start();
    if (isset($_POST['surveyid']) && preg_match('/^[0-9]{1,18}$/', $_POST['surveyid'])) {
        $dbresult = db_execute('select surveyid,survey,name,sellonline,cost,lawyerid,`description`,active from surveys where surveyid=' . $_POST['surveyid']);
        if ($dbresult) {
            if (mysqli_num_rows($dbresult)) {
                $arrForm = mysqli_fetch_assoc($dbresult); ?>
				<div class="col-xs-12">
					<div class="tabs-container" data-content-align="left">
						<ul class="tabs">
							<li class="active">
								<div class="tab__title">
									<span class="h5">Edit Form</span>
								</div>
								<div class="tab__content">
									<form name="formFormEdit" class="custom-validate" data-success="Form successfully updated." data-error="Please fill in all fields correctly.">
										<input type="hidden" name="a" value="form_save">
										<input type="hidden" name="surveyid" value="<?=$arrForm['surveyid']; ?>">
										<div class="row">
											<div class="col-sm-6">
												<div class="row">
													<input name="name" type="text" placeholder="Form Name" class="validate-required" value="<?=$arrForm['name']; ?>" />
												</div>
												<div class="row" style="margin-top: 10px;">
													<div class="input-select">
														<select name="lawyerid">
															<option value="0">Select Responsible Attorney</option>
															<?php
                                                            $dbresult = db_execute('select userid,first_name,last_name from users where accesslevel > 3');
                if ($dbresult && mysqli_num_rows($dbresult)) {
                    while ($row = mysqli_fetch_assoc($dbresult)) {
                        echo '<option value="' . $row['userid'] . '"';
                        if ($arrForm['lawyerid'] === $row['userid']) {
                            echo ' selected';
                        }
                        echo '>' . $row['first_name'] . ' ' . $row['last_name'] . '</option>';
                    }
                } ?>
														</select>
													</div>
												</div>
												<div class="row" style="padding-left: 20px; margin-top: 10px;">
													<div class="col-xs-6">
														<div class="input-checkbox input-checkbox--switch">
															<?php
                                                            $checked = '';
                if ($arrForm['active']) {
                    $checked = ' checked';
                } ?>
															<input id="form_active" type="checkbox" name="form_active" <?=$checked; ?> />
															<label for="form_active"></label>
														</div>
														<span class="text-xs">Active</span>
													</div>
													<div class="col-xs-6">
														<div class="input-checkbox input-checkbox--switch">
															<?php
                                                            $checked = '';
                $hidden = 'display: none;';
                if ($arrForm['sellonline']) {
                    $checked = ' checked';
                    $hidden = '';
                } ?>
															<input id="form_sellonline" type="checkbox" name="form_sellonline" <?=$checked; ?> />
															<label for="form_sellonline"></label>
														</div>
														<span class="text-xs">Sell on Website</span>
													</div>
												</div>
											</div>

											<div class="col-sm-6">
												<div class="row showifsellable" style="<?=$hidden; ?>">
													<div class="col-xs-12">
														<div class="input-icon">
															<i class="fa fa-dollar"></i>
															<input type="text" name="cost" placeholder="Price" value="<?=$arrForm['cost']; ?>" />
														</div>
													</div>
												</div>

												<div class="row showifsellable" style="<?=$hidden; ?>; margin-top: 10px;">
													<div class="col-xs-12">
														<textarea name="description" placeholder="Product Description" maxlength="900"><?=$arrForm['description']; ?></textarea>
													</div>
												</div>
											</div>

											<div class="row" style="margin-top: 10px;">
												<div class="col-xs-12">
													<label>Survey (in <a href="http://json.org" target="_blank">JSON</a> format)</label>
													<textarea name="survey" placeholder="Form JSON" maxlength="100000" style="height: 200px; font-family: monospace; font-size: 14px;"><?=json_encode(json_decode($arrForm['survey']), JSON_PRETTY_PRINT); ?></textarea>
												</div>
											</div>

											<div class="row" style="margin-top: 10px;">
												<button class="btn btn--primary type--uppercase" type="submit">Save Form</button>
											</div>
										</div>
									</form>
								</div>
							</li>

							<li>
								<div class="tab__title">
									<span class="h5">Templates</span>
								</div>
								<div class="tab__content">
									<input type="hidden" id="filter_form_templates" value="0">
									<div id="list_form_templates"></div>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<?php
            } else {
                $json['error'] = 'Invalid form selected.';
            }
        } else {
            $json['error'] = 'There was an error processing your request.  Please try again later.';
        }
    } else {
        $json['error'] = 'Invalid form selected.';
    }

    $json['html'] = ob_get_clean();
    return json_encode($json);
}

function client_edit()
{
    $json = array();
    $json['error'] = '';
    $json['html'] = '';
    if ($GLOBALS['USER']['accesslevel'] < 5) {
        $json['error'] = 'Invalid Permissions.';
        return json_encode($json);
    }

    ob_start();
    if (isset($_POST['userid']) && preg_match('/^[0-9]{1,18}$/', $_POST['userid'])) {
        $dbresult = db_execute('select userid,first_name,last_name,email,active from users where userid=' . $_POST['userid'] . ' and accesslevel = 0');
        if ($dbresult) {
            if (mysqli_num_rows($dbresult)) {
                $arrUser = mysqli_fetch_assoc($dbresult); ?>
				<input type="hidden" id="filter_client_userid" value="<?=$arrUser['userid']; ?>">
				<input type="hidden" id="filter_client_forms" value="<?=$arrUser['userid']; ?>">
				<input type="hidden" id="filter_client_documents" value="<?=$arrUser['userid']; ?>">

				<div class="col-xs-12">
					<div id="client_tabs" class="tabs-container" data-content-align="left">
						<ul class="tabs">
							<li class="active">
								<div class="tab__title">
									<span class="h5">Client</span>
								</div>
								<div class="tab__content">
									<form name="formClientEdit" class="custom-validate" data-success="Client account successfully updated." data-error="Please fill in all fields correctly.">
										<input type="hidden" name="a" value="client_save">
										<input type="hidden" name="userid" value="<?=$arrUser['userid']; ?>">
										<div class="row">
											<div class="col-sm-6">
												<input name="first_name" type="text" placeholder="First Name" class="validate-required" value="<?=htmlspecialchars($arrUser['first_name']); ?>" />
											</div>
											<div class="col-sm-6">
												<input name="last_name" type="text" placeholder="Last Name" class="validate-required" value="<?=htmlspecialchars($arrUser['last_name']); ?>" />
											</div>
											<div class="col-sm-12">
												<input name="email" type="text" placeholder="Email Address" class="validate-required validate-email" value="<?=htmlspecialchars($arrUser['email']); ?>" />
											</div>

											<div class="col-sm-12">
												<div class="input-checkbox input-checkbox--switch">
													<?$ischecked = '';
                if ($arrUser['active']) {
                    $ischecked = ' checked';
                } ?>
													<input type="checkbox" name="account_active" <?=$ischecked; ?> />
													<label for="account_active"></label>
												</div>
												<span class="text-xs">Active</span>
											</div>
											<div class="col-sm-12">
												<button class="btn btn--primary type--uppercase" type="submit">Save Client</button>
											</div>
										</div>
									</form>
								</div>
							</li>
							<li data-loadlist="client_forms">
								<div class="tab__title">
									<span class="h5">Forms</span>
								</div>
								<div class="tab__content">
									<div class="row">
										<div class="col-sm-4">
											<div class="input-checkbox input-checkbox--switch">
												<input id="viewactive_client_forms" type="checkbox" name="viewactive" checked data-loadlist="client_forms" />
												<label for="viewactive"></label>
											</div>
											<span class="text-xs">Active Only</span>
										</div>
										<div class="col-sm-4">
										</div>
										<div class="col-sm-4" style="text-align: right;">
											<span class="text-xs">SHOW</span>
											<ul class="menu-horizontal text-left">
												<li class="dropdown btn--xs btn--secondary">
													<span class="dropdown__trigger">10</span>
													<div class="dropdown__container">
														<div class="container">
															<div class="row">
																<div class="dropdown__content col-sm-2">
																	<ul class="menu-vertical" data-datatype="client_forms">
																		<li><a href="#" class="link_perpage">10</a></li>
																		<li><a href="#" class="link_perpage">25</a></li>
																		<li><a href="#" class="link_perpage">50</a></li>
																		<li><a href="#" class="link_perpage">100</a></li>
																	</ul>
																</div>
															</div>
														</div>
													</div>
												</li>
											</ul>
											<span class="text-xs">ENTRIES</span>
										</div>
										<div class="col-sm-4 text-right">

										</div>
									</div>
									<div id="list_client_forms"></div>
								</div>
							</li>
							<li data-loadlist="client_documents">
								<div class="tab__title">
									<span class="h5">Documents</span>
								</div>
								<div class="tab__content">
									<div class="row">
										<div class="col-sm-4"></div>
										<div class="col-sm-4">
											<div class="input-checkbox input-checkbox--switch">
												<input id="viewactive_client_documents" type="checkbox" name="viewactive" checked data-loadlist="client_documents" />
												<label for="viewactive"></label>
											</div>
											<span class="text-xs">Active Only</span>
										</div>
										<div class="col-sm-4" style="text-align: right;">
											<span class="text-xs">SHOW</span>
											<ul class="menu-horizontal text-left">
												<li class="dropdown btn--xs btn--secondary">
													<span class="dropdown__trigger">10</span>
													<div class="dropdown__container">
														<div class="container">
															<div class="row">
																<div class="dropdown__content col-sm-2">
																	<ul class="menu-vertical" data-datatype="client_documents">
																		<li><a href="#" class="link_perpage">10</a></li>
																		<li><a href="#" class="link_perpage">25</a></li>
																		<li><a href="#" class="link_perpage">50</a></li>
																		<li><a href="#" class="link_perpage">100</a></li>
																	</ul>
																</div>
															</div>
														</div>
													</div>
												</li>
											</ul>
											<span class="text-xs">ENTRIES</span>
										</div>
									</div>
									<div id="list_client_documents"></div>
								</div>
							</li>
							<li>
								<div class="tab__title">
									<span class="h5">Assign Form</span>
								</div>
								<div class="tab__content">
									<div class="row">
										<form name="formAssign" class="custom-validate" data-success="Form(s) successfully assigned to client." data-error="Please fill in all fields correctly.">
											<input type="hidden" name="a" value="assign_form">
											<input type="hidden" name="userid" value="<?=$arrUser['userid']; ?>">
											<div class="col-xs-12">
<!--												<div class="input-select">
													<select multiple name="surveyid">-->
														<?php
                                                            // the intake form (survey id 1) gets assigned automatically when any other survey is assigned to a user (if they don't have an intake survey already).
                                                            $dbresult = db_execute('select surveyid,name from surveys where active = 1 and surveyid != 1 order by name');
                if ($dbresult) {
                    if (mysqli_num_rows($dbresult)) {
                        while ($row = mysqli_fetch_assoc($dbresult)) {
                            ?>
																			<div class="input-checkbox" style="display: block;"><input type="checkbox" name="surveyids[]" id="surveyid_<?=$row['surveyid']; ?>" value="<?=$row['surveyid']; ?>"><label for="surveyid_<?=$row['surveyid']; ?>" style="display: inline-block !important;"></label><span style="position: relative; top: -10px;"><?=$row['name']; ?></span></div>
																		<?php
                        }
                    } else {
                        ?>[ No Forms Listed ]<?php
                    }
                } ?>
<!--													</select>
												</div>-->
											</div>
											<div class="col-xs-12">
												<button class="btn btn--primary type--uppercase" type="submit">Assign Form</button>
											</div>
										</form>
									</div>
								</div>
							</li>
						</ul>
						<div id="tab-document_edit" class="hidden"></div>
					</div>
				</div>
			<?php
            } else {
                $json['error'] = 'Invalid client selected (1).';
            }
        } else {
            $json['error'] = 'There was an error processing your request.  Please try again later.';
        }
    } else {
        $json['error'] = 'Invalid client selected (2).';
    }

    $json['html'] = ob_get_clean();
    return json_encode($json);
}

function admin_edit()
{
    $json = array();
    $json['error'] = '';
    $json['html'] = '';
    if ($GLOBALS['USER']['accesslevel'] < 5) {
        $json['error'] = 'Invalid Permissions.';
        return json_encode($json);
    }

    ob_start();
    if (isset($_POST['userid']) && preg_match('/^[0-9]{1,18}$/', $_POST['userid'])) {
        $dbresult = db_execute('select userid,first_name,last_name,email,active from users where userid=' . $_POST['userid'] . ' and accesslevel = 5');
        if ($dbresult) {
            if (mysqli_num_rows($dbresult)) {
                $arrUser = mysqli_fetch_assoc($dbresult); ?>
					<h3>Edit Admin</h3>

					<form name="formAdminEdit" class="custom-validate" data-success="Admin account successfully updated." data-error="Please fill in all fields correctly.">
						<input type="hidden" name="a" value="admin_save">
						<input type="hidden" name="userid" value="<?=$arrUser['userid']; ?>">
						<div class="row">
							<div class="col-sm-6">
								<input name="first_name" type="text" placeholder="First Name" class="validate-required" value="<?=htmlspecialchars($arrUser['first_name']); ?>" />
							</div>
							<div class="col-sm-6">
								<input name="last_name" type="text" placeholder="Last Name" class="validate-required" value="<?=htmlspecialchars($arrUser['last_name']); ?>" />
							</div>
							<div class="col-sm-12">
								<input name="email" type="text" placeholder="Email Address" class="validate-required validate-email" value="<?=htmlspecialchars($arrUser['email']); ?>" />
							</div>

							<div class="col-sm-12">
								<div class="input-checkbox input-checkbox--switch">
									<?php $ischecked = '';
                if ($arrUser['active']) {
                    $ischecked = ' checked';
                } ?>
									<input type="checkbox" name="account_active" <?=$ischecked; ?> />
									<label for="account_active"></label>
								</div>
								<span class="text-xs">Active</span>
							</div>
							<div class="col-sm-12">
								<button class="btn btn--primary type--uppercase" type="submit">Save Administrator</button>
							</div>
						</div>
					</form>
				<?php
            } else {
                $json['error'] = 'Invalid admin selected.';
            }
        } else {
            $json['error'] = 'There was an error processing your request.  Please try again later.';
        }
    } else {
        $json['error'] = 'Invalid admin selected.';
    }

    $json['html'] = ob_get_clean();
    return json_encode($json);
}


function admin_save()
{
    if ($GLOBALS['USER']['accesslevel'] < 5) {
        echo 'Invalid Permissions';
        exit;
    }

    $errors = array();
    if (!isset($_POST['first_name']) || !$_POST['first_name']) {
        $errors[] = 'First Name is required.';
    }
    if (!isset($_POST['last_name']) || !$_POST['last_name']) {
        $errors[] = 'Last Name is required.';
    }
    if (!isset($_POST['email']) || !$_POST['email']) {
        $errors[] = 'Email is required.';
    }
    if (!isset($_POST['userid']) || !preg_match('/^[0-9]{1,18}$/', $_POST['userid'])) {
        $errors = array();
        $errors[] = 'Invalid admin selected.';
    }

    $active = 0;
    if (isset($_POST['account_active']) && $_POST['account_active'] === 'on') {
        $active = 1;
    }
    if (count($errors)) {
        echo implode("<br>", $errors);
    } else {
        $dbresult = db_execute('select 1 from users where userid=' . $_POST['userid'] . ' and accesslevel > 0');
        if ($dbresult) {
            if (mysqli_num_rows($dbresult)) {
                // make sure a different account doesn't exist with the new email address
                $dbresult = db_execute('select active from users where email="' . escape(strtolower($_POST['email'])) . '" and userid != ' . $_POST['userid']);
                if ($dbresult) {
                    if (mysqli_num_rows($dbresult)) {
                        ?>A different account already exists with the new email address.  Each account must have a unique email address.<?php
                    } else {
                        db_execute('update users set first_name="' . escape($_POST['first_name']) . '", last_name="' . escape($_POST['last_name']) . '",email="' . escape(strtolower($_POST['email'])) . '",active=' . $active . ' where userid=' . $_POST['userid']);
                        echo 1;
                    }
                } else {
                    ?>There was an error processing your request.  Please try again later.<?php
                }
            } else {
                ?>Invalid admin selected.<?php
            }
        } else {
            ?>There was an error processing your request.  Please try again later.<?php
        }
    }
    exit;
}

function usurvey_finalize()
{
    $json = array();
    $json['error'] = '';
    $json['html'] = '';

    if (!isset($_POST['usurveyid']) || !preg_match('/^[0-9]{1,18}$/', $_POST['usurveyid'])) {
        $json['error'] = 'Invalid form selected.';
    } else {
        if ($GLOBALS['USER']['finalize_usurveyid'] == $_POST['usurveyid']) {
            db_execute('update users set finalize_usurveyid = NULL where userid=' . $GLOBALS['USER']['userid']);
        }
        $dbresult = db_execute('select us.userid,us.surveyid,us.data,s.lawyerid from usersurveys us left join surveys s on us.surveyid=s.surveyid where us.usurveyid=' . $_POST['usurveyid']);
        if ($dbresult) {
            if (mysqli_num_rows($dbresult)) {
                list($userid, $surveyid, $jsonSurveyData, $lawyerid) = mysqli_fetch_row($dbresult);
                if ($GLOBALS['USER']['userid'] == $userid || $GLOBALS['USER']['accesslevel'] > 0) {
                    $dbresult = db_execute('select * from users where userid=' . $userid);
                    if ($dbresult && mysqli_num_rows($dbresult)) {
                        // all variables possible for use in this template will be loaded into $vars
                        $vars = array();

                        // load user information into $vars
                        $arrUser = mysqli_fetch_assoc($dbresult);
                        foreach ($arrUser as $key => $val) {
                            $vars['user.' . $key] = $val;
                        }

                        // load survey answers into $vars
                        if ($jsonSurveyData) {
                            $arrData = json_decode($jsonSurveyData);
                            foreach ($arrData as $key => $val) {
                                $vars[$key] = $val;
                            }
                        }
                        // load intake questionnaire answers from this user into $vars
                        $dbresultintake = db_execute('select data from usersurveys where userid=' . $GLOBALS['USER']['userid'] . ' and surveyid=1 and status=2');
                        if ($dbresultintake && mysqli_num_rows($dbresultintake)) {
                            list($intakedata) = mysqli_fetch_row($dbresultintake);
                            $arrData = json_decode($intakedata);
                            foreach ($arrData as $key => $val) {
                                $vars['intake.' . $key] = $val;
                            }
                        }

                        // grab the attorney's information
                        if ($lawyerid) {
                            $dbresult = db_execute('select attorney_firstname,attorney_lastname,attorney_company,attorney_phone,attorney_email from users where userid=' . $lawyerid);
                            if ($dbresult && mysqli_num_rows($dbresult)) {
                                $row = mysqli_fetch_assoc($dbresult);
                                $vars['attorney.first_name'] = $row['attorney_firstname'];
                                $vars['attorney.last_name'] = $row['attorney_lastname'];
                                $vars['attorney.company'] = $row['attorney_company'];
                                $vars['attorney.phone'] = $row['attorney_phone'];
                                $vars['attorney.email'] = $row['attorney_email'];
                            }
                        }

                        // load current date into $vars
                        $vars['date.year'] = date('Y');
                        $vars['date.month'] = date('m');
                        $vars['date.day'] = date('d');

                        // loop through the templates for this survey
                        $dbresult = db_execute('select templateid,name,file from templates where surveyid = ' . $surveyid);
                        if ($dbresult && mysqli_num_rows($dbresult)) {
                            while ($template = mysqli_fetch_assoc($dbresult)) {
                                // copy template
                                db_execute('insert into userdocuments(userid,surveyid,name,status,createdate) values(' . $userid . ',' . $surveyid . ',"' . escape($template['name']) . '",0,NOW())');
                                $userdocumentid = db_insert_id();
//TODO this is where the .docx is appended to each file name before going in the database could change the name of the dirs to have .docx behind them
                                db_execute('update userdocuments set filename="document_' . $userdocumentid . '.docx" where udocumentid=' . $userdocumentid);
                                $clientdir = 'clientfiles/document_' . $userdocumentid;
                                $mkdirres = mkdir($clientdir);
                                $exexres  = exec('cp -r files/template_' . $template['templateid'] . '/* ' . $clientdir);

                                // This section takes the XML template and re-writes it as a PHP script (converting pseudo-code like [[if and [[foreach
                                // into PHP code).  Then executes it to get the final XML document.
                                // Easy peasy, right? XD

                                // to capture errors, we'll gather all output before writing it to the file
                                ob_start();

                                $text = file_get_contents($clientdir . '/word/document.xml');
                                $text = str_replace('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>', '', $text);
				

                                $startp = preg_match('/\<w\:p(\s|\>)/', $text, $matches, PREG_OFFSET_CAPTURE) ? $matches[0][1] : -1;
                                $lastp = strrpos($text, '</w:p>');
                                $text_start = substr($text, 0, $startp);
                                $text_end = substr($text, $lastp+6);
                                $text = substr($text, $startp, $lastp);
				

                                $arrPanels = array();
                                $arrForms = array();

                                $arrForms['user'] = 1;
                                $arrForms['intake'] = 1;
                                $arrForms['attorney'] = 1;
                                $arrForms['date'] = 1;
			
                                $newtext = $text_start;
                                // loop through every <w:p></w:p>
                                preg_match_all('/\<w\:p(\s|\>)(.*?)\<\/w\:p\>/', $text, $matches);
                                foreach ($matches[0] as $idx => $para) {
                                    $para = preg_replace_callback('/\[\[(.*?)\]\]/', function ($codematches) use ($vars,&$arrPanels) {
                                        $stripped = strip_tags($codematches[1]);
							
                                        if (!$stripped) {
                                            return $codematches[0];
                                        } else {
                                            $newval = '';
                                            if ($stripped == 'endif') {
				    		//file_put_contents('~/vardump.log', 'stripped=' . var_dump($stripped), FILE_APPEND);
                                                return str_replace($stripped, '<?php endif; ?>', $codematches[1]);
                                            } elseif ($stripped == 'else') {
                                                return str_replace($stripped, '<?php else: ?>', $codematches[1]);
                                            } elseif ($stripped == 'endforeach') {
                                                return str_replace($stripped, '<?php endforeach; ?>', $codematches[1]);
                                            } else {
                                                if (strpos($stripped, 'foreach') > -1) {
                                                    $pnlstart = strpos($stripped, '{')+1;
                                                    $panel = substr($stripped, $pnlstart, strpos($stripped, '}')-$pnlstart);
                                                    $arrPanels[$panel] = 1;
                                                    $newval = str_replace('foreach', '<?php foreach (', $stripped);
                                                    $newval .= ' as $idx => $arrval): ?>';
                                                }
                                                if (substr($stripped, 0, 2) == 'if') {
                                                    $newval = '<?php if (' . substr($stripped, 2);
                                                    $newval = preg_replace('/(?!\!)\=/', '==', $newval);
                                                    $newval .= '): ?>';

                                                }
                                                if (substr($stripped, 0, 6) == 'elseif') {
                                                    $newval = '<?php elseif (' . substr($stripped, 6);
                                                    $newval = preg_replace('/(?!(\!|\>|\<))\=/', '==', $newval);
                                                    $newval .= '): ?>';
                                                }

                                                // replace variables that are within the foreach/if statement
                                                $newval = preg_replace_callback('/\{(.*?)\}/', function ($varmatches) use ($vars,$arrPanels,$arrForms) {
                                                    if (strpos($varmatches[1], '.') > -1) {
                                                        $vargroup = substr($varmatches[1], 0, strpos($varmatches[1], '.')-1);
                                                        $varvar = substr($varmatches[1], strpos($varmatches[1], '.')+1);
                                                        if (isset($arrPanels[$vargroup])) {
                                                            return str_replace("$vargroup.$varvar", '$arrval[\'' . $varvar . '\']', $varmatches[1]);
                                                        } elseif (isset($arrForms[$vargroup])) {
                                                            return str_replace("$vargroup.$varvar", '$vars[\'' . $vargroup . '.' . $varvar . '\']', $varmatches[1]);
                                                        } else {
                                                            return $varmatches[1];
                                                        }
                                                    } else {
                                                        return '$vars[\'' . $varmatches[1] . '\']';
                                                    }
                                                }, $newval);



                                                if (substr($codematches[1], 0, 7) == 'foreach') {
                                                    $codematches[1] = preg_replace('/^foreach\s?\{?/', '', $codematches[1]);
                                                    $codematches[1] = preg_replace('/\}$/', '', $codematches[1]);

                                                    // Word is quirky and sometimes puts one string of text into multiple <w:t></w:t> tags.
                                                    // so we'll replace the contents of the first <w:t> and then remove the contents in others.
                                                    // May have to do this with other tags
                                                    $codematches[1] = preg_replace_callback('/\<w\:t\>(.*?)\<\/w\:t\>/', function ($textmatches) use ($newval) {
                                                        static $replaced = 0;
                                                        if (!$replaced) {
                                                            $replaced++;
                                                            return '<w:t>' . $newval . '</w:t>';
                                                        }
                                                        return '<w:t></w:t>';
                                                    }, $codematches[1]);

                                                    $codematches[1] = str_replace('[[', '{', $codematches[1]);
                                                    $codematches[1] = str_replace(']]', '}', $codematches[1]);
                                                    return $codematches[1];
                                                } else {
                                                    $newval = str_replace('[[', '{', $newval);
                                                    $newval = str_replace(']]', '}', $newval);
                                                    return str_replace($stripped, $newval, $codematches[1]);
                                                }
                                            }
                                        }
                                    }, $para);

                                    // replace variables that are outside of a foreach/if statement
                                    $para = preg_replace_callback('/\{(.*?)\}/', function ($varmatches) use ($vars,$arrPanels,$arrForms) {
                                        $stripped = strip_tags($varmatches[1]);

                                        $newvar = '';
                                        if (strpos($stripped, '.') > -1) {
                                            $vargroup = substr($stripped, 0, strpos($stripped, '.'));
                                            $varvar = substr($stripped, strpos($stripped, '.')+1);
                                            if (isset($arrPanels[$vargroup])) {
                                                return str_replace($vargroup . '.' . $varvar, '<?php echo $arrval[\'' . $varvar . '\'];?>', $varmatches[1]);
                                            } elseif (isset($arrForms[$vargroup])) {
                                                return str_replace($vargroup . '.' . $varvar, '<?php echo $vars[\'' . $vargroup . '.' . $varvar . '\'];?>', $varmatches[1]);
                                            } else {
                                                return $varmatches[1];
                                            }
                                        } else {
                                            $newvar = '<?php echo $vars[\'' . $stripped . '\'];?>';
                                        }
                                        return str_replace($stripped, $newvar, $varmatches[1]);
                                    }, $para);

                                    $newtext .= $para;
                                }
                                $newtext .= $text_end;

                                $filecontents = '<?php error_reporting(error_reporting() & ~E_NOTICE );?>' . $newtext;
	

                                foreach ($vars as $idx => $val) {
				    //ob_start();
                                    if (is_array($val) || is_object($val)) { // I ADDED the is_object test because the single input matrix questions have a $val of object(stdClass) and not array
					//I ADDED THIS CODE
					//var_dump($idx, $val);
					//$vout = ob_get_clean();
					//file_put_contents($clientdir . '/vardump.txt', $vout, FILE_APPEND);
					//I ADDED THIS CODE TO DEBUG

                                        $str = '$vars[\'' . str_replace("'", '\\\'', $idx) . '\'] = json_decode(\'' . str_replace("'", '\\\'', json_encode($val)) . '\',true);' . "\n";
                                        echo $str . "\n";
                                    } else {
					// I ADDED THIS CODE
					//var_dump($idx, $val);
					//$vout = ob_get_clean();
					//file_put_contents($clientdir . '/vardump.txt', $vout, FILE_APPEND);
					// I ADDED THIS CODE TO DEBUG
                                        echo '$vars[\'' . str_replace("'", '\\\'', $idx) . '\'] = \'' . str_replace("'", '\\\'', $val) . '\';' . "\n";
                                    }
                                }

                                $filecontents = '<?php $vars = array(); ' . ob_get_clean() . '?>' . "\n\n" . $filecontents;
                                file_put_contents($clientdir . '/word/document.php', $filecontents);

                                ob_start();
                                require($clientdir . '/word/document.php');
                                $doc = ob_get_clean();

                                $compiledcontents = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
                                $compiledcontents .= $doc;
				$compiledcontents = preg_replace('/\x{feff}/u', '', $compiledcontents);
				file_put_contents($clientdir . '/word/document.xml', $compiledcontents);
				//Remember this working dir
				$cwd = getcwd();
                                chdir('clientfiles/document_' . $userdocumentid);
                                // zip files to create .docx
                                exec('zip -r ../document_' . $userdocumentid . '.docx *');
                                // TODO commented out to help with debugging
				unlink('/home/bitnami/htdocs/portal/' . $clientdir . '/word/document.php');

				//Change back to working dir
				chdir($cwd);
                            }
                        }
                        db_execute('update usersurveys set status=1,finaldate=NOW() where userid=' . $GLOBALS['USER']['userid'] . ' and usurveyid=' . $_POST['usurveyid']);
                    } else {
                        $json['error'] = 'There was an error processing your request.';
                    }
                } else {
                    $json['error'] = 'Invalid form selected.';
                }
            } else {
                $json['error'] = 'Invalid form selected.';
            }
        } else {
            $json['error'] = 'There was an error processing your request.  Please try again later.';
        }
    }
    return json_encode($json);
}


function usurvey_save()
{
    $json = array();
    $json['error'] = '';
    $json['loadusurveyid'] = '0';
    $json['finalize_usurveyid'] = '0';
    $json['isintake'] = '0';
    $errors = array();

    if (!isset($_POST['data']) || !$_POST['data']) {
        $errors[] = 'No form data submitted.';
    }
    if (!isset($_POST['usurveyid']) || !preg_match('/^[0-9]{1,18}$/', $_POST['usurveyid'])) {
        $errors = array();
        $errors[] = 'Invalid form selected.';
    }

    if (count($errors)) {
        $json['error'] = implode("<br>", $errors);
    } else {
        $dbresult = db_execute('select surveyid from usersurveys where usurveyid=' . $_POST['usurveyid']);
        if ($dbresult) {
            if (mysqli_num_rows($dbresult)) {
                list($surveyid) = mysqli_fetch_row($dbresult);

                if ($surveyid !== '1') {
                    db_execute('update usersurveys set data="' . escape($_POST['data']) . '" where usurveyid=' . $_POST['usurveyid']);

                    // check if the user needs to fill out the intake survey before finalizing this one
                    $dbresult = db_execute('select usurveyid,finaldate from usersurveys where userid=' . $GLOBALS['USER']['userid'] . ' and surveyid = 1 and active = 1');
                    if ($dbresult) {
                        if (mysqli_num_rows($dbresult)) {
                            list($usurveyid, $finaldate) = mysqli_fetch_row($dbresult);
                            if (!$finaldate) {
                                // the user needs to finalize their intake survey
                                db_execute('update users set finalize_usurveyid = ' . $_POST['usurveyid'] . ' where userid=' . $GLOBALS['USER']['userid']);
                                $json['loadusurveyid'] = "$usurveyid";
                            }
                        }
                    }
                } else {
                    db_execute('update usersurveys set data="' . escape($_POST['data']) . '",finaldate=NOW(),status=2 where usurveyid=' . $_POST['usurveyid']);

                    $json['isintake'] = '1';
                    // if this is the intake form and the user has another survey pending finalization, load it
                    if ($GLOBALS['USER']['finalize_usurveyid']) {
                        $json['finalize_usurveyid'] = $GLOBALS['USER']['finalize_usurveyid'];
                    }
                }
            } else {
                $json['error'] = 'Invalid form selected.';
            }
        } else {
            $json['error'] = 'There was an error processing your request.  Please try again later.';
        }
    }
    return json_encode($json);
}

function client_save()
{
    if ($GLOBALS['USER']['accesslevel'] < 5) {
        echo 'Invalid Permissions';
        exit;
    }

    $errors = array();
    if (!isset($_POST['first_name']) || !$_POST['first_name']) {
        $errors[] = 'First Name is required.';
    }
    if (!isset($_POST['last_name']) || !$_POST['last_name']) {
        $errors[] = 'Last Name is required.';
    }
    if (!isset($_POST['email']) || !$_POST['email']) {
        $errors[] = 'Email is required.';
    }
    if (!isset($_POST['userid']) || !preg_match('/^[0-9]{1,18}$/', $_POST['userid'])) {
        $errors = array();
        $errors[] = 'Invalid client selected.';
    }

    $active = 0;
    if (isset($_POST['account_active']) && $_POST['account_active'] === 'on') {
        $active = 1;
    }
    if (count($errors)) {
        echo implode("<br>", $errors);
    } else {
        $dbresult = db_execute('select 1 from users where userid=' . $_POST['userid'] . ' and accesslevel = 0');
        if ($dbresult) {
            if (mysqli_num_rows($dbresult)) {
                // make sure a different account doesn't exist with the new email address
                $dbresult = db_execute('select active from users where email="' . escape(strtolower($_POST['email'])) . '" and userid != ' . $_POST['userid']);
                if ($dbresult) {
                    if (mysqli_num_rows($dbresult)) {
                        ?>A different account already exists with the new email address.  Each account must have a unique email address.<?php
                    } else {
                        db_execute('update users set first_name="' . escape($_POST['first_name']) . '", last_name="' . escape($_POST['last_name']) . '",email="' . escape(strtolower($_POST['email'])) . '",active=' . $active . ' where userid=' . $_POST['userid']);
                        echo 1;
                    }
                } else {
                    ?>There was an error processing your request.  Please try again later.<?php
                }
            } else {
                ?>Invalid client selected.<?php
            }
        } else {
            ?>There was an error processing your request.  Please try again later.<?php
        }
    }
    exit;
}

function template_delete()
{
    $json = array();
    $json['error'] = '';

    if ($GLOBALS['USER']['accesslevel'] < 5) {
        echo 'Invalid Permissions';
        exit;
    }
    if (!isset($_POST['templateid']) || !preg_match('/^[0-9]{1,18}$/', $_POST['templateid'])) {
        echo 'Invalid template selected.';
        exit;
    }
    $dbresult = db_execute('select file from templates where templateid=' . $_POST['templateid']);
    if ($dbresult && mysqli_num_rows($dbresult)) {
        list($filename) = mysqli_fetch_row($dbresult);
        @unlink('files/' . $filename);
        db_execute('delete from templates where templateid=' . $_POST['templateid']);
    } else {
        $json['error'] = 'There was an error processing your request.  Please try again later.';
    }
    return json_encode($json);
}

function usurvey_delete()
{
    $json = array();
    $json['error'] = '';

    if ($GLOBALS['USER']['accesslevel'] < 5) {
        echo 'Invalid Permissions';
        exit;
    }
    if (!isset($_POST['usurveyid']) || !preg_match('/^[0-9]{1,18}$/', $_POST['usurveyid'])) {
        echo 'Invalid form selected.';
        exit;
    }
    $dbresult = db_execute('select 1 from usersurveys where usurveyid=' . $_POST['usurveyid']);
    if ($dbresult && mysqli_num_rows($dbresult)) {
        db_execute('update usersurveys set active=0 where usurveyid = ' . $_POST['usurveyid']);
    } else {
        $json['error'] = 'There was an error processing your request.  Please try again later.';
    }
    return json_encode($json);
}

function assign_form()
{
    if ($GLOBALS['USER']['accesslevel'] < 5) {
        echo 'Invalid Permissions';
        exit;
    }
    if (!isset($_POST['surveyids']) || !is_array($_POST['surveyids'])) {
        echo 'Invalid form(s) selected.';
        exit;
    }
    if (!isset($_POST['userid']) || !preg_match('/^[0-9]{1,18}$/', $_POST['userid'])) {
        echo 'Invalid client selected.';
        exit;
    }

    $dbresult = db_execute('select surveyid from surveys where surveyid in (' . implode(',', $_POST['surveyids']) . ')');
    if ($dbresult) {
        if (mysqli_num_rows($dbresult)) {
            $dbresult2 = db_execute('select 1 from users where userid=' . $_POST['userid']);
            if ($dbresult2) {
                if (mysqli_num_rows($dbresult2)) {
                    while (list($surveyid) = mysqli_fetch_row($dbresult)) {
                        if (preg_match('/^[0-9]{1,18}$/', $surveyid)) {
                            db_execute('insert into usersurveys(userid,surveyid,paid,status,startdate,active) values(' . $_POST['userid'] . ',' . $surveyid . ',1,0,NOW(),1);');
                        }
                    }
                    // if the user doesn't have an intake form, assign one to them
                    $dbresult = db_execute('select 1 from usersurveys where userid=' . $_POST['userid'] . ' and surveyid = 1 and active = 1');
                    if ($dbresult) {
                        if (!mysqli_num_rows($dbresult)) {
                            db_execute('insert into usersurveys(userid,surveyid,paid,status,startdate,active) values(' . $_POST['userid'] . ',1,1,0,NOW(),1)');
                        }
                    }
                    echo 1;
                } else {
                    ?>Invalid client selected.<?php
                }
            } else {
                ?>There was an error processing your request.  Please try again later.<?php
            }
        } else {
            ?>Invalid form selected.<?php
        }
    } else {
        ?>There was an error processing your request.  Please try again later.<?php
    }
    echo 1;
}

function usurvey_undelete()
{
    $json = array();
    $json['error'] = '';

    if ($GLOBALS['USER']['accesslevel'] < 5) {
        echo 'Invalid Permissions';
        exit;
    }
    if (!isset($_POST['usurveyid']) || !preg_match('/^[0-9]{1,18}$/', $_POST['usurveyid'])) {
        echo 'Invalid form selected';
        exit;
    }
    $dbresult = db_execute('select 1 from usersurveys where usurveyid=' . $_POST['usurveyid']);
    if ($dbresult && mysqli_num_rows($dbresult)) {
        db_execute('update usersurveys set active=1 where usurveyid = ' . $_POST['usurveyid']);
    } else {
        $json['error'] = 'There was an error processing your request.  Please try again later.';
    }
    return json_encode($json);
}

function admin_add()
{
    if ($GLOBALS['USER']['accesslevel'] < 5) {
        echo 'Invalid Permissions';
        exit;
    }

    $errors = array();
    if (!isset($_POST['first_name']) || !$_POST['first_name']) {
        $errors[] = 'First Name is required.';
    }
    if (!isset($_POST['last_name']) || !$_POST['last_name']) {
        $errors[] = 'Last Name is required.';
    }
    if (!isset($_POST['email']) || !$_POST['email']) {
        $errors[] = 'Email is required.';
    }

    if (count($errors)) {
        echo implode("<br>", $errors);
    } else {
        // make sure an account doesn't exist with the new email address
        $dbresult = db_execute('select active from users where email="' . escape(strtolower($_POST['email'])) . '"');
        if ($dbresult) {
            if (mysqli_num_rows($dbresult)) {
                ?>A different account already exists with the new email address.  Each account must have a unique email address.<?php
            } else {
                $resetcode = pwgen(20, 1);
                $dbresult = db_execute('insert into users(first_name,last_name,email,pwresetcode,pwresetdate,createdate,active,accesslevel) values("' . escape($_POST['first_name']) . '","' . escape($_POST['last_name']) . '","' . escape(strtolower($_POST['email'])) . '","' . $resetcode . '",DATE_ADD(NOW(),INTERVAL 1 YEAR),NOW(),1,5)');
                if ($dbresult) {
                    $userid = db_insert_id();
                    ob_start();
                    require('../email_templates/new_admin.php');
                    $html = ob_get_clean();

                    sendEmail($_POST['email'], 'Bell Ripper Portal - New Administrator Account', $html);
                    echo 1;
                } else {
                    ?>There was an error processing your request.  Please try again later.<?php
                }
            }
        } else {
            ?>There was an error processing your request.  Please try again later.<?php
        }
    }
    exit;
}

function getSurvey()
{
    $json = array();
    $json['error'] = '';
    $json['html'] = '';
    $json['usurveyid'] = 0;
    $json['paid'] = 0;
    $json['status'] = 0;
    $json['startdate'] = '';
    $json['finaldate'] = '';
    $json['survey'] = '';
    $json['data'] = '';
    $json['name'] = '';

    ob_start();
    if (isset($_POST['usurveyid']) && preg_match('/^[0-9]{1,18}$/', $_POST['usurveyid'])) {
        $dbresult = db_execute('select s.survey,s.name,us.usurveyid,us.paid,us.status,us.startdate,us.finaldate,us.data,us.userid from usersurveys us left join surveys s on us.surveyid=s.surveyid where us.usurveyid=' . $_POST['usurveyid']);
        if ($dbresult) {
            if (mysqli_num_rows($dbresult)) {
                $row = mysqli_fetch_assoc($dbresult);

                if ($GLOBALS['USER']['accesslevel'] < 5) {
                    if ($row['userid'] !== $GLOBALS['USER']['userid']) {
                        $json['error'] = 'Invalid Permissions.';
                        return json_encode($json);
                    }
                }

                $json['usurveyid'] = $row['usurveyid'];
                $json['paid'] = $row['paid'];
                $json['status'] = $row['status'];
                $json['startdate'] = $row['startdate'];
                $json['finaldate'] = $row['finaldate'];
                $json['name'] = $row['name'];
                $json['survey'] = $row['survey'];
                $json['data'] = $row['data'];
            } else {
                $json['error'] = 'Invalid form selected.';
            }
        } else {
            $json['error'] = 'There was an error processing your request.  Please try again later.';
        }
    } else {
        $json['error'] = 'Invalid form selected.';
    }

    $json['html'] = ob_get_clean();
    return json_encode($json);
}

function file_download($preview = 1)
{
    if (isset($_GET['docid']) && preg_match('/^[0-9]{1,18}$/', $_GET['docid'])) {
        if ($GLOBALS['USER']['accesslevel'] < 1) {
            $dbresult = db_execute('select udocumentid,status,filename from userdocuments where userid=' . $GLOBALS['USER']['userid'] . ' and udocumentid=' . $_GET['docid']);
        } else {
            $dbresult = db_execute('select udocumentid,status,filename from userdocuments where udocumentid=' . $_GET['docid']);
        }
        if ($dbresult) {
            if (mysqli_num_rows($dbresult)) {
                list($docid, $status, $filename) = mysqli_fetch_row($dbresult);

                if ($preview) {
                    // when previewing, remove the .docx and replace with .pdf
                    $filename = str_replace('.docx', '.pdf', $filename);
                }
		//TODO $filename contains the .docx extenstion, which causes it to not recognize the dir in the test
		//TODO this line of code allows the file to be downloaded		
		//$filename = str_replace('.docx', '', $filename);

                $file = 'clientfiles/' . $filename;

                $contenttype = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                if (substr($filename, -4) == '.pdf') {
                    $contenttype = 'application/pdf';
                }
                if (file_exists($file)) {
                    header('Content-Type: ' . $contenttype);
                    header("Content-Transfer-Encoding: Binary");
                    header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    readfile($file);
                } else {
                    echo 'Error locating file.  Please contact the administrator.' . '$file=' . $file . ' $filename=' . $filename;
                }
            } else {
                echo 'Invalid permissions to view file.';
            }
        } else {
            echo 'There was an error processing your request.  Please try again later.';
        }
    } else {
        echo 'Invalid file.';
    }
    exit;
}

function process_purchase()
{
    require('../utils/stripe-php-5.1.1/init.php');
    $error = '';

    $surveyid = 0;
    $name = '';
    $amount = '';
    if (isset($_GET['surveyid']) && $_GET['surveyid']) {
        $dbresult = db_execute('select surveyid,name,cost from surveys where sellonline = 1 and active = 1 and surveyid = ' . $_GET['surveyid']);
        if ($dbresult) {
            if (mysqli_num_rows($dbresult)) {
                list($surveyid, $name, $amount) = mysqli_fetch_row($dbresult);
            } else {
                $error = 'Invalid survey selected.';
            }
        } else {
            $error = 'There was an error processing your request.  Please try again later.';
        }
    }
    
    if (!isset($_POST['stripeToken'])) {
        $error = 'Invalid information submitted.  Transaction not processed.';
    }

    if ($error) {
        echo "ERROR: $error<br>\n";
    } else {
        try {
            \Stripe\Stripe::setApiKey($GLOBALS['CONFIG']['stripe_secret_key']);
            $charge = \Stripe\Charge::create(array(
                "amount" => ($amount * 100),
                "currency" => "usd",
                "description" => $name,
                "source" => $_POST['stripeToken']
            ), array('stripe_userid' => $GLOBALS['USER']['userid'],'stripe_surveyid' => $surveyid));
            $token = $charge->id;
            $status = $charge->paid;
            
            $dbresult = db_execute('insert into usersurveys(userid,surveyid,paid,startdate,active) values(' . $GLOBALS['USER']['userid'] . ',' . $surveyid . ',1,NOW(),1)');
            $usurveyid = db_insert_id();
            db_execute('insert into charges(token,userid,usurveyid,transdate,transamount,status) values("' . $token . '",' . $GLOBALS['USER']['userid'] . ',' . $usurveyid . ',NOW(),"' . $amount . '",0)');

            // if the user doesn't have an intake form, assign one to them
            $dbresult = db_execute('select 1 from usersurveys where userid=' . $GLOBALS['USER']['userid'] . ' and surveyid = 1');
            if ($dbresult) {
                if (!mysqli_num_rows($dbresult)) {
                    db_execute('insert into usersurveys(userid,surveyid,paid,status,startdate,active) values(' . $GLOBALS['USER']['userid'] . ',1,1,0,NOW(),1)');
                }
            }

            echo 1;
        } catch (\Stripe\Error\Card $e) {
            $body = $e->getJsonBody();
            echo $body['error']['type'] . ' ' . $body['error']['message'];
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $body = $e->getJsonBody();
            echo $body['error']['type'] . ' ' . $body['error']['message'];
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $body = $e->getJsonBody();
            echo $body['error']['type'] . ' ' . $body['error']['message'];
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $body = $e->getJsonBody();
            echo $body['error']['type'] . ' ' . $body['error']['message'];
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            $body = $e->getJsonBody();
            echo $body['error']['type'] . ' ' . $body['error']['message'];
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $body = $e->getJsonBody();
            echo $body['error']['type'] . ' ' . $body['error']['message'];
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $body = $e->getJsonBody();
            echo $body['error']['type'] . ' ' . $body['error']['message'];
        }
    }
}


function printshop($asjson)
{
    ob_start();
    $products = array();
    $dbresult = db_execute('select surveyid,name,cost,description from surveys where sellonline = 1 and active = 1');
    if ($dbresult && mysqli_num_rows($dbresult)) {
        while ($row = mysqli_fetch_assoc($dbresult)) {
            $products[] = $row;
        }
    }

    $productcount = count($products);
    $rows = floor($productcount / 2);
    $rows += $productcount % 2;

    $pointer = 0;
    for ($x = 0; $x < $rows; $x++) {
        $pointer = $x * 2; ?><div class="row row-eq-height">
		<div class="col-xs-6 shopbox1"><h3><?=$products[$pointer]['name']; ?></h3><p><?=$products[$pointer]['description']; ?></p></div><?php
        if (($pointer+1) < $productcount) {
            ?><div class="col-xs-6 shopbox1"><h3><?=$products[$pointer+1]['name']; ?></h3><p><?=$products[$pointer+1]['description']; ?></p></div><?php
        } ?>
		</div>
		<div class="row row-eq-height">
			<div class="col-xs-6 shopbox2">
				<h5>$<?=$products[$pointer]['cost']; ?></h5>
				<button class="btn btn--primary btn-shop" style="width: 100%;" data-amount="<?=($products[$pointer]['cost']*100); ?>" data-name="<?=str_replace('"', '\\"', $products[$pointer]['name']); ?>" data-surveyid="<?=$products[$pointer]['surveyid']; ?>">Purchase</button>
			</div>
			<?php if (($pointer+1) < $productcount) { ?>
				<div class="col-xs-6 shopbox2">
					<h5>$<?=$products[$pointer+1]['cost'];?></h5>
					<button class="btn btn--primary btn-shop" style="width: 100%;" data-amount="<?=($products[$pointer+1]['cost']*100);?>" data-name="<?=str_replace('"', '\\"', $products[$pointer+1]['name']);?>" data-surveyid="<?=$products[$pointer+1]['surveyid'];?>">Purchase</button>
				</div>
			<?php } ?>
		</div>
	<?php
    }
    if ($asjson) {
        $json = array();
        $json['error'] = '';
        $json['html'] = ob_get_clean();
        return json_encode($json);
    } else {
        echo ob_get_clean();
    }
}

?>
