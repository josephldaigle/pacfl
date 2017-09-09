/** Recaptcha Scripts **/


function recaptchaCallback() {
	document.getElementById("wp-submit").removeAttribute('disabled');
	document.getElementById("wp-submit").style.cursor = "pointer";
}