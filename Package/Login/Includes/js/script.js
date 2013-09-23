/*
* Validate register
*/
$(document).ready(function(){
	$(".jqRegister").validate({
		rules: {
			firstnameInput: {
				required: true,
				letterswithbasicpunc: true, 
				rangelength: [1, 40]
			},
			lastnameInput: {
				required: true,
				letterswithbasicpunc: true, 
				rangelength: [1, 40]
			},
			usernameInput:{
				required: true,
				rangelength: [1, 15]
			},
			ageInput:{
				required: true,
				rangelength: [1, 3],
				digits: true
			},
			countryInput:{
				required: true,
				rangelength: [1, 30],
				letterswithbasicpunc: true 
			},
			email: {
				required: true,
				email: true
			},
			passwordInput:{
				required: true,
				rangelength: [1, 20]
			},
			passwordConfirmInput:{
				required: true,
				rangelength: [1, 20]
			}
		},
		messages: {
			firstnameInput: {
				required: 'Please enter your first name.',
				letterswithbasicpunc: 'Please enter letters only.',
				rangelength: 'Please enter between 1-40 characters.'
			},
			lastnameInput: {
				required: 'Please enter your surname.',
				letterswithbasicpunc: 'Please enter letters only.',
				rangelength: 'Please enter between 1-40 characters.'
			},
			usernameInput:{
				required: 'Please enter your username.',
				rangelength: 'Please enter between 1-15 characters.'
			},
			ageInput:{
				required: 'Please enter your age.',
				rangelength: 'Please enter between 1-3 characters.',
				digits: 'Please enter digits only.'
			},
			countryInput:{
				required: 'Please enter your country name.',
				rangelength: 'Please enter between 1-30 characters.',
				letterswithbasicpunc: 'Please enter letters only.'
			},
			email: {
				required: 'Please enter your email address.',
				email: 'Please enter a valid email address.'
			},
			passwordInput:{
				required: 'Please choose a password.',
				rangelength: 'Please enter between 1-20 characters.'
			},
			passwordConfirmInput:{	
				required: 'Please re-enter your password.',
				rangelength: 'Please enter between 1-20 characters.'
			}
		}
		
	});
	return;
});


/*
* Validate login
*/
$(document).ready(function(){
	$(".jqlogin").validate({
		rules: {
			usernameInput: {
				required: true,
				rangelength: [1, 15]
			},
			password: {
				required: true,
				rangelength: [1, 20]
			}
		},
		messages: {
			usernameInput: {
				required: 'Please enter your username.',
				rangelength: 'Your username should be 1-15 characters long.'
			},
			password: {
				required: 'Please enter a password.',
				rangelength: 'Your password should be 1-20 characters long.'
			}
		}
	});
	return;
});