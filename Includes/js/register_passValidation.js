/*
 * Password Criteria
 * * *
 * Requirements:
 * 	- At least 1 lowercase letter
 * 	- At least 1 uppercase letter
 * 	- At least 1 number
 *  - At least at Good strength (80+ points)
 *  - 
 * * *
 * Points:
 *  - 10 | Per character
 *  - 20 | Upper and lower
 *  - 20 | 3 numbers
 *  - 20 | 2 symbols
 * * *
 * Deductions
 *  - 20 | 3 same characters in a row
 */

/*
 * Passwords:
 * 
 * Weak Password 0 - 70
 * - Teeefff1 40 points (8 Characters, 2 Deductions)
 * - Teeestt1 60 points (8 Characters, 1 Deduction)
 * - Teees111 60 points (8 Characters, 3 Numbers, 1 Deduction)
 * 
 * Good Password 80 - 180
 * - Testing1 80 points (8 Characters, 0 Deductions)
 * - Teess221 100 points (8 Characters, 3 Numbers, 0 Deductions)
 * - Teess1%% 100 points (8 Characters, 2 Special, 0 Deductions)
 * - Tee123%% 120 points (8 Characters, 3 Numbers, 2 Special, 0 Deductions)
 * 
 * Strong Password 190 - 240
 * - Tee123%%abcdefg 190 (15 Characters, 3 Numbers, 2 Special, 0 Deductions)
 * - Tee123&&abcdefghijkl 240 (20 Characters, 3 Numbers, 2 Special, 0 Deductions)
 * 
 * Password Strength Bar
 * [10] [50] [80] [120] [140] [160] [190] [210] [240]
 */

var vali_number = "0123456789";
var vali_special = "!@#$%^&*?_~";

var countLength = 0;
var countUpper = 0;
var countLower = 0;
var countNumber = 0;
var countSpecial = 0;
var countLength = 0;
var correctLength;

var validPassword = {
	"length" : 0,
	"upper" : 0,
	"lower" : 0,
	"number" : 0,
	"special" : 0,
	"length_count" : 0,
	"length_correct" : null
};


var field_name;
;
var field_input;
var field_message;
var field_value;

function validate_pass(field_name) {
	field_name = field_name;
	field_input = getInputBox(field_name);
	field_inputBox = document.getElementById(field_input);
	field_message = getMessageBox(field_name);
	field_value = getInputValue(field_input);

	correctLength = checkCorrectLength();

	countRecHasHappened = 0;

	var points = 0;

	// count length
	countLength = field_value.length;

	// check upper and lowercase counts
	countCase();

	// check for reoccuring characters
	checkReoccuring();

	// check number count
	countNumber = countNumbers();

	// check for special characters
	countSpecial = countSpecial();

	// calculate points
	calculatePoints();

	updatePasswordBox();

	/*
	 * alert("countLength: " + countLength + "\n" + "countUpper: " + countUpper +
	 * "\n" + "countLower: " + countLower + "\n" + "countNumber: " + countNumber +
	 * "\n" + "countSpecial: " + countSpecial + "\n" + "Total Points: " +
	 * points);
	 */
	
	function passwordValid(){
		if(	countUpper > 0 && countLower > 0
			&& countNumber > 0 && checkCorrectLength()){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * Calculate Points
	 */
	function calculatePoints() {
		// 10 points for every character
		points += (countLength * 10);

		// 20 points if the password contains more than 3 numbers
		if (countNumber >= 3) {
			points += 20;
		}

		// 20 points if the password contains more than 2 special characters
		if (countSpecial >= 2) {
			points += 20;
		}
		// -20 points for every group of 3 re-occuring characters
		if (countRecHasHappened > 0) {
			points -= (countRecHasHappened * 20);
		}
	}

	function countCase() {
		for ( var passChar = 0; passChar < field_value.length; passChar++) {
			// if(pass[passChar] == pass[passChar].toUpperCase()){
			if (field_value[passChar].match(/^([A-Z])$/)) {
				countUpper++;
			} else if (field_value[passChar].match(/^([a-z])$/)) {
				countLower++;
			}
		}
	}

	function countNumbers() {
		var tempCount = 0;
		for ( var passChar = 0; passChar < field_value.length; passChar++) {
			// if(pass[passChar] == pass[passChar].toUpperCase()){
			if (field_value[passChar].match(/^([0-9])$/)) {
				tempCount++;
			}
		}
		return tempCount;

	}

	function countSpecial() {
		var tempCount = 0;
		for ( var passChar = 0; passChar < field_value.length; passChar++) {
			// if(pass[passChar] == pass[passChar].toUpperCase()){
			if (field_value[passChar].match(/^([!@#$%^&*?_~])$/)) {
				tempCount++;
			}
		}
		return tempCount;

	}

	function checkReoccuring() {
		/*
		 * count re-occurring characters * * If three or more characters in a
		 * row equal the same countRecHasHappened will be increment by 1
		 * countRecHasHappened will be used later to determine how many points
		 * should be deducted off of the total points
		 */

		var recCount = 0;
		var checkRecHasHappened;

		for ( var checkRec = 0; checkRec < field_value.length; checkRec++) {
			// count if this is the first time the loop is running
			if (checkRec > 0) {
				// count if the character is the same as the previous one
				if (field_value[checkRec - 1] == field_value[checkRec]) {
					// Add one to countRec
					recCount++;
				} else {
					// Reset countRec
					recCount = 0;
				}
				// If the character has been entered 3 times in a row
				if (recCount == 2) {
					countRecHasHappened++;
				}

			}
		}

	}

	function countPassContains(countExists) {
		var currentPoints = 0;
		// loop through password
		for ( var i = 0; i < field_value.length; i++) {
			// loop through vali_ (special characters and numbers)
			for ( var x = 0; x < countExists.length; x++) {
				if (field_value[i] == countExists[x]) {
					currentPoints++;
				}
			}
		}
		return currentPoints;
	}

	function checkCorrectLength() {
		if (field_value.length >= 8) {
			if (field_value.length <= 20) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function updatePasswordBox() {
		var passwordStrengthBar = document
				.getElementById("passwordStrengthBar");
		var psb = passwordStrengthBar.getElementsByTagName('span')[0];

		var psbColorCode = Array();
		psbColorCode['0'] = "#A30000";
		psbColorCode['80'] = "#FF3300";
		psbColorCode['120'] = "#FFA319";
		psbColorCode['190'] = "#00CC00";
		psbColorCode['210'] = "#00A300";

		if (points < 80) {
			psb.style.backgroundColor = psbColorCode['0'];
			displayPassInvalid();
		} else {
			if(passwordValid()){
				displayPassValid();
			}else{
				displayPassInvalid();
			}
			
			if (points >= 80 && points < 190) {
				if (points < 120) {
					psb.style.backgroundColor = psbColorCode['80'];
				} else if (points >= 120) {
					psb.style.backgroundColor = psbColorCode['120'];
				}
			} else if (points >= 190) {
				if (points < 210) {
					psb.style.backgroundColor = psbColorCode['190'];
				} else if (points >= 210) {
					psb.style.backgroundColor = psbColorCode['210'];
				}
			}
		}

		/*
		 * if (points < 80) { passwordNotValid("Your password is too weak");
		 * psb.style.backgroundColor = "#A30000"; } else if (points >= 80 &&
		 * points < 190) { if(points > 120){ psb.style.backgroundColor =
		 * "#FFA319"; }else{ psb.style.backgroundColor = "#FF3300"; }
		 * 
		 * passwordNotValid("Your password is too weak"); // displayPassOk(); }
		 * else { if(points < 210){ psb.style.backgroundColor = "#00CC00";
		 * }else{ psb.style.backgroundColor = "#00A300"; }
		 * passwordNotValid("Your password is too weak"); // displayPassOk(); }
		 */

		// 240 == 100%
		if (points >= 240) {
			points = 100;
		} else {
			points = points / 240 * 100;
		}
		// alert(points);

		psb.style.width = points + "%";
	}

	function passwordNotValid(message) {
		field_inputx.style.border = "3px solid #FA4B3E";

		fields_messagex.style.visibility = "visible";
		var messageBoxArea = messageBox.getElementsByTagName('p')[0];
		messageBoxArea.innerHTML = points;
	}

	function displayPassValid() {
		field_inputBox.style.border = "3px solid green";
		validChecker['pass'] = true;
	}
	
	function displayPassInvalid(){
		field_inputBox.style.border = "3px solid #C4C4C4";
		validChecker['pass'] = false;
	}

	function openPasswordBox() {
		field_message.style.visibility = "visible";
	}

}

function openPasswordBox() {
	document.getElementById("message_pass").style.visibility = "visible";
}