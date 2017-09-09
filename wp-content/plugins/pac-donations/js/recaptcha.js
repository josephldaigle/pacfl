/** Recaptcha Scripts **/


function recaptchaCallback() {
	
	document.getElementById("submit").removeAttribute('disabled');
	document.getElementById("submit").style.cursor = "pointer";
	}
	
function resetRecaptca() {
	
}

function resetForm(){
    setTimeout(function(){
		//window.alert(document.getElementById("pac-don-recatpcha"));
		ScriptManager.RegisterStartupScript(this, this.GetType(), "CaptchaReload", "$.getScript(\"https://www.google.com/recaptcha/api.js\", function () {});", true);
		grecaptcha.reset();
    }, 50);
    return true;
}