/*
 * Password Criteria
 * * *
 * Requirements:
 * 	- At least 1 lowercase letter
 * 	- At least 1 uppercase letter
 * 	- At least 1 number
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



function passValidation(pass) {
	alert("testing");
	
	var vali_number = "0123456789";
	var vali_special = "!@#$%^&*?_~";
	
	var countLength = 0;
	var countUpper = 0;
	var countLower = 0;
	var countNumber = 0;
	var countSpecial = 0;

	var countRecHasHappened = 0;
	
	// count length
	// countLength = countLength();
	
	// check upper and lowercase counts
	countCase();
	
	countNumber = countPassContains(vali_number);
	countSpecial = countPassContains(vali_special);
	
	alert("countLength: " + countLength + "<br/>"
			+ "countUpper: " + countUpper + "<br/>"
			+ "countLower: " + countLower + "<br/>"
			+ "countNumber: " + countNumber + "<br/>"
			+ "countSpecial: " + countSpecial);
	
	function countCase(){
		for(int passChar = 0;passChar < pass.length;passChar++){
			if(passChar == passChar.toUpperCase()){
				countUpper++;
			}else if(passChar == passChar.toLowerCase()){
				countLower++;
			}
		}
	}
	
	function checkReoccuring(){
		/*
		 * count re-occurring characters * * If three or more characters in a
		 * row equal the same countRecHasHappened will be increment by 1
		 * countRecHasHappened will be used later to determine how many points
		 * should be deducted off of the total points
		 */
		
		var recCount = 0;
		
		for(var checkRec = 0;checkRec<pass.length;checkRec++){
			// count if this is the first time the loop is running
			if (checkRec > 0) {
				// count if the character is the same as the previous one
				if (pass[checkRec - 1] == pass[checkRec]) {
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
		var countRec = 0;
		for ( var i = 0; i < data.length; i++) {

			var currentPoints = 0;
			for ( var x = 0; i < countExists.length; x++) {
				if (pass[i] == countExists[x]) {
					currentPoints++;
				}
			}
		}
		return currentPoints;
	}
}

function displayPassOk() {
	document.getElementById("register_pass").style.border = "3px solid green";
	if (document.getElementById("message_pass").style.visibility == "visible") {
		hideMessageBox(type);
	}
}



