$(document).ready(function(){
    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
    
    $('body').on("change", "#confirmPassword", function(){
        if ($("#confirmPassword").val() != $("#inputPasswordRegister").val()) {
            $("#confirmPasswordRow").addClass("has-error");
            $("#confirmPasswordDiv").show();
        }
        
        if ($("#confirmPassword").val() == $("#inputPasswordRegister").val()) {
            $("#confirmPasswordRow").removeClass("has-error");
            $("#confirmPasswordDiv").hide();
        }
    });
    
    $('body').on("click", "#registerButton", function(){
        var fields = ["inputUsernameRegister", "inputForename", "inputSurname", "inputEmail", "inputPasswordRegister", "confirmPassword"];
        
        errorFlag = "";
        
        for (var x in fields) {
            if (!$("#"+fields[x]).val()) {
                $("#"+fields[x]+"Row").addClass("has-error");
                errorFlag = true;
            } else {
                $("#"+fields[x]+"Row").removeClass("has-error");  
            }
        }
            
        if (!errorFlag) {
            $("#registerform").submit();
        } else {
            $("#showErrorsDiv").show(); 
        }
    });    
});