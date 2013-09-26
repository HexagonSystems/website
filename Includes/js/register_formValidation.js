var xmlhttp;

var head_input = "register_";
var head_message = "message_";
var contentBox_name = "submitBox";

var validChecker = {
		"username"	: null,
		"email"		: null,
		"pass"	: null
	};

/**
 * formValidation
 * 
 * @param data
 * @param type
 */
function formValidation(field_value, field_name) {
	
	var field_input = getInputBox(field_name);
	var field_message = getMessageBox(field_name);
	
	xmlhttp = createStuff();

	xmlhttp.open("GET", "includes/php/register_" + field_name
			+ "Validation.php?data=" + field_value, true);

	xmlhttp.onreadystatechange = process;

	xmlhttp.send();

	function process() {

		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			response = xmlhttp.responseText;
			field_input = document.getElementById(field_input);
			if (response !== "null") {
				if (response === "true") {
					field_input.style.border = "3px solid green";
					validChecker[field_name] = true;
					if (messageBoxActive(field_message)) {
						hideMessageBox(field_message);
					}
				} else {
					createMessageBox(field_name, response);
				}
			} else {
				displayInputReset(field_name);
				if (messageBoxActive(field_message)) {
					hideMessageBox(field_message);
				}
			}
		}
	}

}

function validateEntry(field_input){
	var field_name = getNameFromInput(field_input.id);
	var field_has_data = false;
	if(field_name == "pass"){
		field_has_data = true;
	}else{
		field_has_data = fieldHasData(field_name);
	}
	
	if(field_has_data){
		switch(field_name){
		case "username":	validate_username(field_name);
							break;
		case "email":		validate_email(field_name);
							break;		
		case "pass":		validate_pass(field_name);
							break;	
		}
		
	}
	var contentBox = document.getElementById(contentBox_name);
	if(fieldsValid()){
		contentBox.getElementsByTagName("span")[0].innerHTML = '';
		contentBox.getElementsByTagName("button")[0].disabled = false;
		contentBox.getElementsByTagName("button")[0].className = "enabledButton";
	}else{
		contentBox.getElementsByTagName("span")[0].innerHTML = 'Please fill in all fields correctly';
		contentBox.getElementsByTagName("button")[0].disabled = true;
		contentBox.getElementsByTagName("button")[0].className = "disabledButton";
	}
	
	function fieldsValid(){
		for(var field_name in validChecker){
			if(!validChecker[field_name] || validChecker[field_name] === null){
				return false;
			}
		}
		
		return true;
	}
}

function fieldHasData(field_name) {

	var field_input = getInputBox(field_name);
	var field_value = getInputValue(field_input);

	if (field_value.length > 0) {
		return true;
	} else {
		var field_message = getMessageBox(field_name);
		displayInputReset(field_name);
		return false;
	}
}
		
function getNameFromInput(field_input){
	var splitString = field_input.split("_");
	return splitString[1];
}

function checkEmpty(element_id) {
	element_id = document.getElementById(element_id);
	if (empty(element_id.value)) {
		return true;
	} else {
		return false;
	}
}
/*
 * function submitForm() { values = array('username', 'email', 'pass');
 * 
 * foreach(values as id){ element_id = document.getElementById(id);
 * if(checkEmpty("register_" + id)) { createMessageBox(id, "Please enter in your " +
 * id); }else { } } }
 */
function createStuff() {
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		return new XMLHttpRequest();
	} else {// code for IE6, IE5
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
}

/*
 * GET FUNCTIONS
 */
function getInputBox(field_name) {
	return head_input + field_name;
}

function getMessageBox(field_name) {
	return head_message + field_name;
}

function getInputValue(field_input) {
	if(document.getElementById(field_input).value != null){
		return document.getElementById(field_input).value;
	}else{
		return "";
	}
}

function displayInputReset(field_name) {
	
	validChecker[field_name] = null;
	
	var field_input = getInputBox(field_name);
	var field_message = getMessageBox(field_name);

	fieldToReset = document.getElementById(field_input);
	fieldToReset.style.border = "3px solid #C4C4C4";

	if (messageBoxActive(field_message)) {
		hideMessageBox(field_message);
	}
}

function messageBoxActive(messageBox) {
	messageBox = document.getElementById(messageBox);
	if (messageBox.style.visibility == "visible") {
		return true;
	} else {
		return false;
	}
}

function validate_username(field_name) {

	var field_input = getInputBox(field_name);
	var field_message = getMessageBox(field_name);
	var field_value = getInputValue(field_input);
	
	if (/\s/g.test(field_value)) {
		createMessageBox(field_name, "Please remove any white space from your username");
	} else {
		formValidation(field_value, field_name);
	}
}

function validate_email(field_name) {
	
	var field_input = getInputBox(field_name);
	var field_message = getMessageBox(field_name);
	var field_value = getInputValue(field_input);
	
	if (/\s/g.test(field_value)) {
		createMessageBox(field_name, "Please remove any white space from your username");
	} else {
		formValidation(field_value, field_name);
	}
}

function usernameValidation(username) {
	if (/\s/g.test(username)) {
		createMessageBox('username',
				"Please remove any white space from your username");
	} else {
		formValidation(username, 'username');
	}
}

/**
 * Create Message Box Changes the border color of the input box to a red-toned
 * color. Inputs errorMessage retrieved through previous AJAX command into the
 * id of the current message box. Makes the current message box visible to the
 * user
 * 
 * @param inputBox
 *            Id of current inputBox that caused the error
 * @param messageBox
 *            Id of messageBox for the message to be displayed in
 * @param errorMessage
 *            Message to be displayed to the user
 */
function createMessageBox(field_name, errorMessage) {
	
	validChecker[field_name] = false;
	
	var field_input = getInputBox(field_name);
	var field_message = getMessageBox(field_name);

	var messageBox = document.getElementById(field_message);
	var inputBox = document.getElementById(field_input);

	inputBox.style.border = "3px solid #FA4B3E";

	messageBox.innerHTML = errorMessage;
	messageBox.style.visibility = "visible";
}

/**
 * Hide Message Box Takes the id of a message box div as the parameter. Change
 * the visibility of the message box to hidden.
 * 
 * @param messageBox
 *            Id of the message box div currently in use
 */
function hideMessageBox(field_message) {
	var messageBox = document.getElementById(field_message);
	messageBox.style.visibility = "hidden";
}