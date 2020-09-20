
function initBR() {
	$("[data-scrollto-id]").each(function() {
		var scrolltoid = $(this).attr("data-scrollto-id");
		$(this).on("click",function() {
			var newtop = $("#" + scrolltoid).offset().top;
			if (newtop > 100) {
				newtop = newtop - 20;
			} else {
				newtop = newtop - 100;
			}
			$("html, body").animate({
				scrollTop: newtop
			}, "fast");
		});
	});

	$("[data-loadlist]").each(function() {
		$(this).on("click",function() {
			loadData($(this).attr("data-loadlist"));
		});
	});

	$("[data-loadonclick]").each(function() {
		$(this).on("click",function() {
			loadOnClick($(this).attr("data-loadonclick"));
		});
	});

	mr.documentReady();
}

	var lists = {"admins": {}, "clients": {}, "mydocuments": {}, "myforms": {}, "forms": {}, "form_templates": {}, "client_forms": {}, "client_documents": {}};
	lists["admins"]["page"] = 1;
	lists["admins"]["sort"] = "desc";
	lists["admins"]["sortcol"] = "first_name";
	lists["admins"]["perpage"] = 10;

	lists["myforms"]["page"] = 1;
	lists["myforms"]["sort"] = "desc";
	lists["myforms"]["sortcol"] = "startdate";
	lists["myforms"]["perpage"] = 10;

	lists["mydocuments"]["page"] = 1;
	lists["mydocuments"]["sort"] = "desc";
	lists["mydocuments"]["sortcol"] = "createdate";
	lists["mydocuments"]["perpage"] = 10;

	lists["clients"]["page"] = 1;
	lists["clients"]["sort"] = "asc";
	lists["clients"]["sortcol"] = "last_name";
	lists["clients"]["perpage"] = 10;

	lists["forms"]["page"] = 1;
	lists["forms"]["sort"] = "desc";
	lists["forms"]["sortcol"] = "name";
	lists["forms"]["perpage"] = 10;

	lists["form_templates"]["page"] = 1;
	lists["form_templates"]["sort"] = "asc";
	lists["form_templates"]["sortcol"] = "name";
	lists["form_templates"]["perpage"] = 100;

	lists["client_forms"]["page"] = 1;
	lists["client_forms"]["sort"] = "desc";
	lists["client_forms"]["sortcol"] = "finaldate";
	lists["client_forms"]["perpage"] = 10;

	lists["client_documents"]["page"] = 1;
	lists["client_documents"]["sort"] = "desc";
	lists["client_documents"]["sortcol"] = "finaldate";
	lists["client_documents"]["perpage"] = 10;

$(document).ready(function() {
	initBR();
});


function showError(error) {
	$('body').find('.form-error, .form-success').remove();
	$('body').append('<div class="form-error" style="display: none;">' + error + '</div>');
	$('body').append('<div class="form-success" style="display: none;"></div>');
	formError = $("body").find(".form-error");
	formSuccess = $("body").find(".form-success");
	mr.forms.showFormError(formSuccess, formError, 1000, 5000, 500);
}

$(document).on("click",".btn-download",function() {
	window.open('index.php?a=dl&docid=' + $(this).attr("data-docid"));
});

$(document).on("click",".btn-preview",function() {
	window.open('index.php?a=preview&docid=' + $(this).attr("data-docid"));
});

$(document).on("click","#template_upload",function() {
	var parentspan = $(this).parent();
	parentspan.addClass("loading").addClass("disabled");
	setTimeout(function() {
		parentspan.removeClass("loading").removeClass("disabled");
	},2000);
	$('#template_upload').fileupload({
		url: "index.php?a=template_upload&surveyid=" + $("#filter_form_templates").val(),
		done: function (e, data) {
			setTimeout(function() {
				loadData("form_templates");
			},2000);
		},
		fail: function(e, data) {
			alert('Error Uploading File');
		}
	});
});

$(document).on("keydown","#document_name",function() {
	$("#document_name.field-error").removeClass("field-error");
});

$(document).on("click","#document_upload",function(e) {
	if ($("#document_name").val() == "") {
		e.preventDefault();
		e.stopPropagation();
		$("#document_name").addClass("field-error");
		$("#document_name").focus();
		return;
	}

	var parentspan = $(this).parent();
	parentspan.addClass("loading").addClass("disabled");
	setTimeout(function() {
		parentspan.removeClass("loading").removeClass("disabled");
	},2000);

	var visible = 0;
	if ($("#client_documents_visible").prop("checked")) {visible = 1;}
	$('#document_upload').fileupload({
		url: "index.php?a=document_upload&userid=" + $("#filter_client_userid").val() + "&visible=" + visible + "&name=" + encodeURIComponent($("#document_name").val()),
		done: function (e, data) {
			setTimeout(function() {
				loadData("client_documents");
			},2000);
		},
		fail: function(e, data) {
			alert('Error Uploading File');
		}
	});
});

function loadOnClick(action) {
	$("#data_" + action).html("").addClass("divloading");
	var	objData = {
		a: action
	};

	$.ajax({
		url: "index.php",
		method: "POST",
		data: objData,
		error: function(response) {
			setTimeout(function() {
				$("#data_" + action).removeClass("divloading");
				showError("Unable to Reach Server.");
			},1000);
		},
		success: function(json) {
			objData = tryParseJSON(json);
			if (objData.error) {
				$("#data_" + action).removeClass("divloading");
				showError(objData.error);
			} else {
				setTimeout(function() {
					$("#data_" + action).html(objData.html).removeClass("divloading");
				},1000);
			}
		}
	});
}

function loadData(datatype) {
	$("#list_" + datatype).html("").addClass("divloading");
	filterval = "";
	viewactive = 0;
	viewincomplete = 0;
	if ($("#filter_" + datatype).length) {
		filterval = $("#filter_" + datatype).val();
	}
	if ($("#viewactive_" + datatype).length) {
		if ($("#viewactive_" + datatype).prop("checked")) {
			viewactive = 1;
		} else {
			viewactive = 0;
		}
	}
	if ($("#viewincomplete_" + datatype).length) {
		if ($("#viewincomplete_" + datatype).prop("checked")) {
			viewincomplete = 1;
		} else {
			viewincomplete = 0;
		}
	}

	var	objData = {
		a: "list_" + datatype,
		page: lists[datatype]["page"],
		sort: lists[datatype]["sort"],
		sortcol: lists[datatype]["sortcol"],
		perpage: lists[datatype]["perpage"],
		filter: encodeURIComponent(filterval),
		viewactive: viewactive,
		viewincomplete: viewincomplete
	};

	$.ajax({
		url: "index.php",
		method: "POST",
		data: objData,
		error: function(response) {
			setTimeout(function() {
				$("#list_" + datatype).removeClass("divloading");
				showError("Unable to Reach Server.");
			},1000);
		},
		success: function(json) {
			objData = tryParseJSON(json);
			if (objData.error) {
				$("#list_" + datatype).removeClass("divloading");
				showError(objData.error);
			} else {
				setTimeout(function() {
					$("#list_" + datatype).html(objData.html).removeClass("divloading");
				},1000);
			}
		}
	});
}

function prepareSurveyView(usurveyid,clientview,showintake,showreview) {
	$(".portal-tab:not(.hidden)").addClass("hidden");
	$("#surveyElement").html("");
	$("#surveyResult").html("");
	$("#tab-survey").removeClass("hidden").addClass("divloading");
	$("#usurveyid").val(usurveyid);
	$("#clientview").val(clientview);
	$("#surveyFinalize").hide();
	$("#surveyResult").removeClass("divloading");
	$("#surveyNeedIntake").hide();
	$("#surveyReview").hide();
        $("#surveyHeader").hide();
	$("#surveyIntakeComplete").hide();
	if (showintake) {
		$("#surveyNeedIntake").show();
	}
	if (showreview) {
		$("#surveyReview").show();
	}

	if (clientview) {
		$("#surveyWarning").show();
	} else {
		$("#surveyWarning").hide();
	}
}

$(document).on("click",".link_viewform",function(e) {
	fetchAndLoadSurvey($(this).attr("data-usurveyid"),1,0,0);
});

function fetchAndLoadSurvey(usurveyid,clientview,showintake,showreview) {
	prepareSurveyView(usurveyid,clientview,showintake,showreview);

	$.ajax({
		url: "index.php",
		method: "POST",
		data: {
			a: "getSurvey",
			usurveyid: usurveyid
		},
		error: function(response) {
			showError("Unable to Reach Server.");
			$("#tab-survey").removeClass("divloading");
		},
		success: function(json) {
			objData = tryParseJSON(json);
			console.log(objData);
			if (objData["error"]) {
				showError(objData["error"]);
			} else {
				setTimeout(function() {
					$("#tab-survey").removeClass("divloading");
					loadSurvey(objData["name"],objData["survey"],objData["data"]);
				},1000);
			}
		}
	});
}

$(document).on("click",".link_viewclientform",function(e) {
	var usurveyid = $(this).attr("data-usurveyid");
	prepareSurveyView(usurveyid,0,0,0);

	$.ajax({
		url: "index.php",
		method: "POST",
		data: {
			a: "getSurvey",
			usurveyid: usurveyid
		},
		error: function(response) {
			showError("Unable to Reach Server.");
			$("#tab-survey").removeClass("divloading");
		},
		success: function(json) {
			objData = tryParseJSON(json);
			if (objData.error) {
				showError(objData.error);
			}
			setTimeout(function() {
				$("#tab-survey").removeClass("divloading");
				loadSurvey(objData["name"],objData["survey"],objData["data"]);
			},1000);
		}
	});
});

$(document).on("click",".delete_template",function(e) {
	var templateid = $(this).attr("data-templateid");
	$.ajax({
		url: "index.php",
		method: "POST",
		data: {
			a: "delete_template",
			templateid: templateid
		},
		error: function(response) {
			showError("Unable to Reach Server.");
			loadData("form_templates");
		},
		success: function(json) {
			objData = tryParseJSON(json);
			if (objData.error) {
				showError(objData.error);
			}
			loadData("form_templates");
		}
	});
});

$(document).on("click",".delete_usurvey",function(e) {
	e.stopPropagation();
	e.preventDefault();

	var usurveyid = $(this).attr("data-usurveyid");
	$.ajax({
		url: "index.php",
		method: "POST",
		data: {
			a: "delete_usurvey",
			usurveyid: usurveyid
		},
		error: function(response) {
			showError("Unable to Reach Server.");
			loadData("client_forms");
		},
		success: function(json) {
			objData = tryParseJSON(json);
			if (objData.error) {
				showError(objData.error);
			}
			loadData("client_forms");
		}
	});
});

$(document).on("click",".undelete_usurvey",function(e) {
	e.stopPropagation();
	e.preventDefault();

	var usurveyid = $(this).attr("data-usurveyid");
	$.ajax({
		url: "index.php",
		method: "POST",
		data: {
			a: "undelete_usurvey",
			usurveyid: usurveyid
		},
		error: function(response) {
			showError("Unable to Reach Server.");
			loadData("client_forms");
		},
		success: function(json) {
			objData = tryParseJSON(json);
			if (objData.error) {
				showError(objData.error);
			}
			loadData("client_forms");
		}
	});
});

function tryParseJSON(json) {
	errmsg = "";
	if (json === "Invalid Login") {
		errmsg = "Your session has expired.  Please <a href='index.php'>Sign In</a>.";
	} else if (json === "Invalid Permissions") {
		errmsg = "You do not have the necessary permissions.";
	} else {
		try {
			objData = jQuery.parseJSON(json);
		} catch (e) {
			console.log("Invalid JSON: " + json);
			return {error: "There was an error processing your request."};
		}
		return objData;
	}
	return {error: errmsg};
}

$(document).on("click",".link_perpage",function(e) {
	e.preventDefault();
	e.stopPropagation();
	var perpage = $(this).text();
	var dropdown = $(this).closest(".dropdown");
	dropdown.find(".dropdown__trigger").text(perpage);
	dropdown.toggleClass('dropdown--active');

	var datatype = $(this).closest("ul").attr("data-datatype");
	lists[datatype]["perpage"] = perpage;
	loadData(datatype);
});

$(document).on("click","a[data-page]",function() {
	var newpage = $(this).attr("data-page");
	var datatype = $(this).attr("data-type");
	lists[datatype]["page"] = newpage;
	loadData(datatype);
});

$(document).on("change","#form_sellonline",function(e) {
	if ($("#form_sellonline").prop("checked")) {
		$(".showifsellable").css("display","block");
	} else {
		$(".showifsellable").css("display","none");
	}
});


$(document).on("click",".tabs > li",function(e) {
	$("#tab-document_edit").addClass("hidden");
});


$(document).on("click",".link_viewdocument",function(e) {
	$("#client_tabs li.active").removeClass("active");
	$("#tab-document_edit").removeClass("hidden").html("").addClass("divloading");
	var udocumentid = $(this).attr("data-udocumentid");

	$.ajax({
		url: "index.php",
		method: "POST",
		data: {
			a: 'document_edit',
			udocumentid: udocumentid
		},
		error: function(response) {
			setTimeout(function() {
				$("#tab-document").removeClass("divloading");
				showError("Unable to Reach Server.");
			},1000);
		},
		success: function(json) {
			objData = tryParseJSON(json);
			if (objData.error) {
				showError(objData.error);
			} else {
				setTimeout(function() {
					$("#tab-document_edit").html(objData.html).removeClass("divloading");
					initBR();
				},1000);
			}
		}
	});	
});


$(document).on("click",".link_editclient",function(e) {
	$(".portal-tab:not(.hidden)").addClass("hidden");
	$("#tab-client").removeClass("hidden").html("").addClass("divloading");
	var userid = $(this).attr("data-userid");

	$.ajax({
		url: "index.php",
		method: "POST",
		data: {
			a: 'client_edit',
			userid: userid
		},
		error: function(response) {
			setTimeout(function() {
				$("#tab-client").removeClass("divloading");
				showError("Unable to Reach Server.");
			},1000);
		},
		success: function(json) {
			objData = tryParseJSON(json);
			if (objData.error) {
				showError(objData.error);
			} else {
				setTimeout(function() {
					$("#tab-client").html(objData.html).removeClass("divloading");
					$("#filter_client_userid").val(userid);
					initBR();
				},1000);
			}
		}
	});
});

$(document).on("click",".link_editform",function(e) {
	$(".portal-tab:not(.hidden)").addClass("hidden");
	$("#tab-form_edit").removeClass("hidden").html("").addClass("divloading");
	var surveyid = $(this).attr("data-surveyid");

	$.ajax({
		url: "index.php",
		method: "POST",
		data: {
			a: 'form_edit',
			surveyid: surveyid
		},
		error: function(response) {
			setTimeout(function() {
				$("#tab-form_edit").removeClass("divloading");
				showError("Unable to Reach Server.");
			},1000);
		},
		success: function(json) {
			objData = tryParseJSON(json);
			if (objData.error) {
				showError(objData.error);
			} else {
				setTimeout(function() {
					$("#tab-form_edit").html(objData.html).removeClass("divloading");
					$("#filter_form_templates").val(surveyid);
					loadData("form_templates");
					initBR();
				},1000);
			}
		}
	});
});

/*
$(document).on("click",".link_viewsurvey",function(e) {
	$(".portal-tab:not(.hidden)").addClass("hidden");
	$("#surveyElement").html("");
	$("#surveyResult").html("");
	$("#tab-forms").removeClass("hidden");
	$("#surveyElement").addClass("divloading");

	$.ajax({
		url: "index.php",
		method: "POST",
		data: {
			a: 'usurvey_edit',
			usurveyid: $(this).attr("data-usurveyid")
		},
		error: function(response) {
			setTimeout(function() {
				$("#surveyElement").removeClass("divloading");
				showError("Unable to Reach Server.");
			},1000);
		},
		success: function(json) {
			objData = tryParseJSON(json);
			if (objData.error) {
				showError(objData.error);
			} else {
				setTimeout(function() {
					$("#surveyElement").removeClass("divloading");
					loadSurvey(objData.name,objData.survey);
				},1000);
			}
		}
	});
});
*/

$(document).on("click",".link_editadmin",function(e) {
	$(".portal-tab:not(.hidden)").addClass("hidden");
	$("#tab-admins_edit").removeClass("hidden").html("").addClass("divloading");

	$.ajax({
		url: "index.php",
		method: "POST",
		data: {
			a: 'admin_edit',
			userid: $(this).attr("data-userid")
		},
		error: function(response) {
			setTimeout(function() {
				$("#tab-admins_edit").removeClass("divloading");
				showError("Unable to Reach Server.");
			},1000);
		},
		success: function(json) {
			objData = tryParseJSON(json);
			if (objData.error) {
				showError(objData.error);
			} else {
				setTimeout(function() {
					$("#tab-admins_edit").html(objData.html).removeClass("divloading");
					initBR();
				},1000);
			}
		}
	});
});

function loadSurvey(surveytitle,surveyjson,data) {
	Survey.Survey.cssType = "bootstrap";
	Survey.defaultBootstrapCss.navigationButton = "btn btn--primary";

	var customCss = {
		navigationButton: "btn btn--primary"
	};

	var json = jQuery.parseJSON(surveyjson);
	json["title"] = surveytitle;
	json["showProgressBar"] = "top";
	json["showCompletedPage"] = false;
	json["showQuestionNumbers"] = "false";

	window.survey = new Survey.Model(json);
	if (data) {
		window.survey.data = jQuery.parseJSON(data);
                //if a header exists
                if (window.survey.data.header) {
                    var $surveyHeader = $('#surveyHeader');
                    $surveyHeader.prepend('<ul id="headerData"></ul>');
                    var $headerData = $('#headerData')
                    $.each(window.survey.data.header, function (key, value) {
                        $headerData.prepend('<li>' + key + ' : ' + value + '</li>')
                    })
                    $surveyHeader.show();

                }
	}

	$("#surveyElement").Survey({
		model: window.survey,
		css: customCss,
		onComplete: saveForm
	});
}

$(document).on("click",".btn-shop",function(e) {
	e.preventDefault();

	var surveyid = $(this).attr("data-surveyid");

	var handler = StripeCheckout.configure({
		key: 'pk_test_FhQjnJfPUcPbnPTR5OR6X8WI00lNXwP2pZ',
		image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
		locale: 'auto',
		token: function(token) {
			$.ajax({
				url: "index.php?a=p&surveyid=" + surveyid,
				method: "POST",
				data: {stripeToken: token.id, surveyid: $(this).attr("data-surveyid")},
				error: function() {
					setTimeout(function() {
						showError("Unable to Reach Server.");
					},1000);
				},
				success: function(result) {
					if (result === '1') {
						$(".portal-tab:not(.hidden)").addClass("hidden");
						$("#tab-purchase").removeClass("hidden");
					} else {
						showError(result);
					}
				}
			});
		}
	});

	handler.open({
		name: $(this).attr("data-name"),
		description: $(this).attr("data-name"),
		zipCode: false,
		amount: parseInt($(this).attr("data-amount"))
	});

	// Close Checkout on page navigation:
	window.addEventListener('popstate', function() {handler.close();});
});


$(document).on("click","#btnFinalize",function(e) {
	$("#surveyFinalize").hide();
	$("#surveyResult").addClass("divloading");
	$.ajax({
		url: "index.php",
		method: "POST",
		data: {
			a: 'usurvey_finalize',
			usurveyid: $("#usurveyid").val(),
			clientview: $("#clientview").val()
		},
		error: function(response) {
			setTimeout(function() {
				showError("Unable to Reach Server.");
				$("#surveyResult").removeClass("divloading");
			},1000);
		},
		success: function(json) {
			objData = tryParseJSON(json);
			if (objData["error"]) {
				$("#surveyResult").removeClass("divloading");
				showError(objData["error"]);
			} else {
				setTimeout(function() {
					$("#surveyResult").removeClass("divloading");
					if ($("#clientview").val() === "1") {
						$("#surveyResult").html("Your form has been submitted for review.");
					} else {
						$("#surveyResult").html("Client documents have been prepared.");
					}
				},1000);
			}
		}
	});
});

function saveForm(survey) {
	$("#surveyWarning").hide();
	$("#surveyNeedIntake").hide();
	$("#surveyReview").hide();
	$("#surveyResult").addClass("divloading");
	window.scrollTo(0,0);

	$.ajax({
		url: "index.php",
		method: "POST",
		data: {
			a: 'usurvey_save',
			usurveyid: $("#usurveyid").val(),
			data: JSON.stringify(survey.data)
		},
		error: function(response) {
			$("#surveyResult").removeClass("divloading");
			setTimeout(function() {
				showError("Unable to Reach Server.");
			},1000);
		},
		success: function(json) {
			$("#surveyResult").removeClass("divloading");
			objData = tryParseJSON(json);
			if (objData["error"]) {
				showError(objData["error"]);
			} else if (objData["loadusurveyid"] !== "0") {
				fetchAndLoadSurvey(objData["loadusurveyid"],1,1,0);
			} else if (objData["finalize_usurveyid"] !== "0") {
				$("#surveyReview").show();
				fetchAndLoadSurvey(objData["finalize_usurveyid"],1,0,1);
			} else {
				setTimeout(function() {
					if (objData["isintake"] === "1") {
						$("#surveyIntakeComplete").show();
					} else {
						$("#surveyFinalize").show();
					}
					if ($("#clientview").val() === "1") {
					} else {
						$("#surveyResult").html("Client form has been updated.");
					}
//					$("#tab-admins_edit").html(objData.html).removeClass("divloading");
//					initBR();
				},1000);
			}
		}
	});

//	document.querySelector('#surveyResult').innerHTML = "result: " + JSON.stringify(survey.data);
}
