<!DOCTYPE html>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.3.0/knockout-min.js"></script>
<link href="https://surveyjs.azureedge.net/1.0.1/surveyeditor.css" type="text/css" rel="stylesheet" />
<script src="https://surveyjs.azureedge.net/1.0.1/survey.ko.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/ace.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/worker-json.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/mode-json.js" type="text/javascript"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" type="text/css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js" type="text/javascript"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css" type="text/css" rel="stylesheet"/>

<script src="https://surveyjs.azureedge.net/1.0.1/surveyeditor.min.js"></script>


<script>
	$(document).ready(function() {
		var editorOptions = {showEmbededSurveyTab: true}; //see examples below
		var survey = new SurveyEditor.SurveyEditor("surveyEditorContainer", editorOptions);
		//set function on save callback
		survey.saveSurveyFunc = saveMySurvey;
	});

	function saveMySurvey(){
		var yourNewSurveyJSON = editor.text;
		//send updated json in your storage  
	}

</script>

<div id="surveyEditorContainer"></div>
