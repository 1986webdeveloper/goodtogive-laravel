/*=========================================================================================
    File Name: wizard-steps.js
    Description: wizard steps page specific js
    ----------------------------------------------------------------------------------------
    Item Name: Modern Admin - Clean Bootstrap 4 Dashboard HTML Template
   Version: 3.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/
var form = $(".number-tab-steps").show();

// Wizard tabs with numbers setup
$(".number-tab-steps").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Submit'
    },
    onStepChanging: function (event, currentIndex, newIndex) {
        
        // Allways allow previous action even if the current form is not valid!
        if (currentIndex > newIndex) {
            return true;
        }
        // Forbid next action on "Warning" step if the user is too young
        if (newIndex === 3 && Number($("#age-2").val()) < 18) {
            return false;
        }
        // Needed in some cases if the user went back (clean up)
        if (currentIndex < newIndex) {
            // To remove error styles
            $(".number-tab-steps").find(".body:eq(" + newIndex + ") label.error").remove();
            $(".number-tab-steps").find(".body:eq(" + newIndex + ") .error").removeClass("error");
        }
        $(".number-tab-steps").validate().settings.ignore = ":disabled,:hidden";
        return $(".number-tab-steps").valid();
    },
    onFinishing: function (event, currentIndex) {
        $(".number-tab-steps").validate().settings.ignore = ":disabled";
        return $(".number-tab-steps").valid();
    },
    onFinished: function (event, currentIndex) {
        
        $("#target").submit();

        // alert("Form submitted.");
    }
});

// Wizard tabs with icons setup
$(".icons-tab-steps").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Submit'
    },
    onFinished: function (event, currentIndex) {
        $("#projectFileUpload").submit();
        // alert("Form submitted.");
    }
});

// Vertical tabs form wizard setup
$(".vertical-tab-steps").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    stepsOrientation: "vertical",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Submit'
    },
    onFinished: function (event, currentIndex) {
        alert("Form submitted.");
    }
});

// Validate steps wizard

// Show form
var form = $(".steps-validation").show();

$(".steps-validation").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Submit'
    },
    onStepChanging: function (event, currentIndex, newIndex) {
        // Allways allow previous action even if the current form is not valid!
        if (currentIndex > newIndex) {
            return true;
        }
        // Forbid next action on "Warning" step if the user is too young
        if (newIndex === 3) {
            return false;
        }
        // Needed in some cases if the user went back (clean up)
        if (currentIndex < newIndex) {
            // To remove error styles
            form.find(".body:eq(" + newIndex + ") label.error").remove();
            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
        }
        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    },
    onFinishing: function (event, currentIndex) {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
    },
    onFinished: function (event, currentIndex) {
        $("#target").submit();
    }
});

// Initialize validation
$(".steps-validation").validate({
    ignore: 'input[type=hidden]', // ignore hidden fields
    errorClass: 'danger',
    successClass: 'success',
    highlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
    },
    unhighlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
    },
    errorPlacement: function (error, element) {
        error.insertAfter(element);
    },
    rules: {
        email: {
            email: true
        }
    }
});
var firstname = {
        required: "Please enter firstname."
}

var lastname = {
    required: "Please enter lastname."
}

var mobile = {
    required: "Please enter mobile number.",
    remote: "Mobile number already exists."
}

var email = {
    required: "Please enter email.",
    email: "Please enter valid email address.",
    remote:"Email address already exists."
}
var password = {
    required: "Please enter password.",
    minlength: "Password length must be minimum 6 character."
}

var cpassword = {
    required: "Please enter confirm password.",
    equalTo: "Your password and confirmation password do not match",
}
var userid = $('#userid').val();
// var user_role = $(".is_church").val();
// Initialize validation

$(".number-tab-steps").validate({
    ignore: 'input[type=hidden]', // ignore hidden fields
    errorClass: 'danger',
    successClass: 'success',
    highlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
        
    },
    unhighlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
    },
    errorPlacement: function (error, element) {
        if($(element).attr('name')=='church_id') {
            if($("#user_role_id").val() != '3')
                error.insertAfter(element);
        }else{
            error.insertAfter(element);
        }
    },
    
    rules: {
        firstname:{required:true},
        
        email: {
            required:true,
            email: true,
            remote: {
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "check_email",
                type: "get",
                data: {id:''}
            }
        },
        mobile:{
            required:true,
            remote: { 
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "check_mobile",
                type: "get",
                data: {id:''}
            }
        },
        password:{
            required:true,
            minlength:6,
        },
        cpassword:{
            required:true,
            equalTo: "input:password[name='password']"
        },
        user_role_id:{
            required:true
        },
        image:{
            required:true
        },
        NOTIFICATION_OF:{
            required:true
        },
        church_id:{
            required: function(element){
                            return $("#user_role_id").val()!="3";
                        }
        }
    },
    messages:{
        firstname:firstname,
        password:password,
        cpassword:cpassword,
        mobile:mobile,
        email:email,
        user_role_id:"Please select user role.",
        image:"Please select photo.",
        NOTIFICATION_OF:"Please select notification type.",
        church_id:"Please select church name."
    }
});

var form = $(".edit-user-submit").show();

// Wizard tabs with numbers setup
$(".edit-user-submit").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Submit'
    },
    onStepChanging: function (event, currentIndex, newIndex) {
        
        // Allways allow previous action even if the current form is not valid!
        if (currentIndex > newIndex) {
            return true;
        }
        // Forbid next action on "Warning" step if the user is too young
        if (newIndex === 3 ) {
            return false;
        }
        // Needed in some cases if the user went back (clean up)
        if (currentIndex < newIndex) {
            // To remove error styles
            $(".edit-user-submit").find(".body:eq(" + newIndex + ") label.error").remove();
            $(".edit-user-submit").find(".body:eq(" + newIndex + ") .error").removeClass("error");
        }
        $(".edit-user-submit").validate().settings.ignore = ":disabled,:hidden";
        return $(".edit-user-submit").valid();
    },
    onFinishing: function (event, currentIndex) {
        $(".edit-user-submit").validate().settings.ignore = ":disabled";
        return $(".edit-user-submit").valid();
    },
    onFinished: function (event, currentIndex) {
        $("#target").submit();
    }
});


$(".edit-user-submit").validate({
    ignore: 'input[type=hidden]', // ignore hidden fields
    errorClass: 'danger',
    successClass: 'success',
    highlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
    },
    unhighlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
    },
    errorPlacement: function (error, element) {
        if($(element).attr('name')=='church_id') {
            if($("#user_role_id").val() != '3')
                error.insertAfter(element);
        }else{
            error.insertAfter(element);
        }
    },
    
    rules: {
        firstname:{required:true},
        lastname:{required:true},
        email: {
            required:true,
            email: true,
            remote: {
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: window.AJAX_SITE_URL+"check_email",
                type: "get",
                data: {id:userid}
            }
        },
        mobile:{
            required:true,
            remote: { 
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: window.AJAX_SITE_URL+"check_mobile",
                type: "get",
                data: {id:userid}
            }
        },
        user_role_id:{
            required:true
        },
        church_id:{
            required: function(element){
                            return $("#user_role_id").val()!="3";
                        }
        },
        NOTIFICATION_OF:{
            required:true
        },
    },
    messages:{
        firstname:firstname,
        lastname:lastname,
        mobile:mobile,
        email:email,
        user_role_id: "Please select user role.",
        church_id: "Please select church name.",
        NOTIFICATION_OF: "Please select notification type.", 
    }
});



// wizard to add new project
$(".project-validation").validate({
    ignore: 'input[type=hidden]', // ignore hidden fields
    errorClass: 'danger',
    successClass: 'success',
    highlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
    },
    unhighlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
    },
    errorPlacement: function (error, element) {
        error.insertAfter(element);
    },
    
    rules: {
        church_id:{
            required: true,
        },
        church_fund_id:{
            required:true,
        },
        name:{
            required:true,
        },
        goal_amount:{
            required:true,
        },
        startdate:{
            required:true,
        },
        enddate:{
            required:true,
        },
        description:{
            required:true,
        },
        project_image:{
            required:true,
        },
        repeater:{
            required:true,
        },
    },
    messages:{
        church_id:"Please select church.",
        church_fund_id:"Please select church fund name.",
        name:"Please enter project name.",
        goal_amount:"Please enter goal amount.",
        startdate:"Please enter start date.",
        enddate:"Please enter end date.",
        description:"Please enter description.",
        project_image:"Please enter project images.",
        repeater:"Please enter donation slab."
    }
});

$(".project-validation").steps({
    headerTag: "h6",
    bodyTag: "fieldset",
    transitionEffect: "fade",
    titleTemplate: '<span class="step">#index#</span> #title#',
    labels: {
        finish: 'Submit'
    },
    onStepChanging: function (event, currentIndex, newIndex) {
        
        // Allways allow previous action even if the current form is not valid!
        if (currentIndex > newIndex) {
            return true;
        }
        // Forbid next action on "Warning" step if the user is too young
        if (newIndex === 4 ) {
            return false;
        }
        // Needed in some cases if the user went back (clean up)
        if (currentIndex < newIndex) {
            // To remove error styles
            $(".project-validation").find(".body:eq(" + newIndex + ") label.error").remove();
            $(".project-validation").find(".body:eq(" + newIndex + ") .error").removeClass("error");
        }
        $(".project-validation").validate().settings.ignore = ":disabled,:hidden";
        return $(".project-validation").valid();
    },
    onFinishing: function (event, currentIndex) {
        $(".project-validation").validate().settings.ignore = ":disabled";
        return $(".project-validation").valid();
    },
    onFinished: function (event, currentIndex) {
        $("#projectFileUpload").submit();
    }
});
// Initialize plugins
// ------------------------------

// Date & Time Range
$('.datetime').daterangepicker({
    timePicker: true,
    timePickerIncrement: 30,
    locale: {
        format: 'MM/DD/YYYY h:mm A'
    }
});