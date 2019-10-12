<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Modern admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities with bitcoin dashboard.">
    <meta name="keywords" content="admin template, modern admin template, dashboard template, flat admin template, responsive admin template, web app, crypto dashboard, bitcoin dashboard">
    <meta name="author" content="PIXINVENT">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="apple-touch-icon" href="{{ asset('app-assets/images/ico/favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('app-assets/images/ico/favicon.ico')}}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">
    
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/vendors.min.css') }}">
     <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/toggle/bootstrap-switch.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/toggle/switchery.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/ui/prism.min.css') }}">
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/file-uploaders/dropzone.css') }}"> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/extensions/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/extensions/colReorder.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/extensions/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/extensions/fixedHeader.dataTables.min.css') }}">
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/datedropper.min.css') }}"> -->
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/colors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/components.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/extensions/timedropper.min.css') }}">

    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->

   
  
    
    
    

    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu-modern.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/switch.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/charts/jquery-jvectormap-2.0.3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/charts/morris.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/fonts/simple-line-icons/style.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/fonts/simple-line-icons/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/forms/wizard.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/pickers/daterange/daterange.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/core/colors/palette-switch.css') }}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/dropzone.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/ui/jqueryui.css') }}">
    <!-- END: Custom CSS-->

    <link href="{{ asset('app-assets/external-css/fileinput.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('app-assets/external-css/theme.css') }}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous">

    <!-- blueimp Gallery styles -->
    <link rel="stylesheet" href="https://blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" href="https://blueimp.github.io/jQuery-File-Upload/css/jquery.fileupload.css">
    <link rel="stylesheet" href="https://blueimp.github.io/jQuery-File-Upload/css/jquery.fileupload-ui.css">
    <script type="text/javascript">

   
       //window.AJAX_SITE_URL = "http://192.168.1.39/goodtogive-web/admin/";
       window.AJAX_SITE_URL = "<?php echo config('app.url'); ?>";
      

       
    </script> 
 
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
<body class="vertical-layout vertical-menu-modern 2-columns   fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-dark navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mobile-menu d-lg-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                    <li class="nav-item mr-auto"><a class="navbar-brand" href="{{ route('dashboard') }}"><img class="brand-logo" alt="GTG admin logo" src="{{ asset('app-assets/images/ico/logo.png') }}">
                            <h3 class="brand-text">GoodToGive</h3>
                        </a></li>
                    <li class="nav-item d-none d-lg-block nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="toggle-icon ft-toggle-right font-medium-3 white" data-ticon="ft-toggle-right"></i></a></li>
                    <li class="nav-item d-lg-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a></li>
                </ul>
            </div>
            <div class="navbar-container content">
                <div class="collapse navbar-collapse" id="navbar-mobile">
                    <ul class="nav navbar-nav mr-auto float-left">
                        <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>
                        <li class="nav-item nav-search"><a class="nav-link nav-link-search" href="#"><i class="ficon ft-search"></i></a>
                            <div class="search-input">
                                <input class="input" type="text" placeholder="Explore GTG">
                            </div>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav float-right">
                        <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"><span class="mr-1 user-name text-bold-700">GTG Admin</span><span class="avatar avatar-online"><img src="{{ asset('app-assets/images/ico/avatar-logo.png') }}" alt="avatar"><i></i></span></a>
                            <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="{{ route('change-profile') }}"><i class="ft-user"></i> Edit Profile</a><a class="dropdown-item" href="{{ route('change-password') }}"><i class="ft-mail"></i> Change Password</a>
                                <div class="dropdown-divider"></div><a class="dropdown-item" href="{{ route('user-logout') }}"><i class="ft-power"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- END: Header-->
    
    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
        @yield('sidebar')
    </div>
    <!-- END: Main Menu-->
    
    <!-- BEGIN: Content-->
    <div class="app-content content">
        @yield('content')
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light navbar-border navbar-shadow">
        <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block darken-2">Made with <i class="fa fa-heart red"></i> by <a class="text-bold-800 grey darken-2" href="https://www.acquaintsoft.com/" target="_blank">Acquaint Softtech Private Limited</a></span><span id="scroll-top"></span></span></p>
    </footer>
    <!-- END: Footer-->

   
    
     
    
 


    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/toggle/bootstrap-switch.min.js')}}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/toggle/switchery.min.js')}}"></script>
     <script src="{{ asset('app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js')}}"></script>
     <script src="{{ asset('app-assets/vendors/js/extensions/dragula.min.js') }}"></script>
     <script src="{{ asset('app-assets/js/scripts/extensions/drag-drop.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/extensions/datedropper.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/extensions/date-time-dropper.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/libraries/jquery_ui/jquery-ui.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/charts/chart.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/charts/raphael-min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/charts/morris.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/charts/jvector/jquery-jvectormap-2.0.3.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/charts/jvector/jquery-jvectormap-world-mill.js') }}"></script>
    <script src="{{ asset('app-assets/data/jvector/visitor-data.js') }}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <!--
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.colReorder.min.js') }}"></script>
    
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.fixedHeader.min.js') }}"></script> -->
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/extensions/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/pickers/daterange/daterangepicker.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/wizard-steps.js') }}"></script>
    <!-- END: Page JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app.js') }}"></script>
    <!-- END: Theme JS-->
   <script src="{{ asset('app-assets/js/scripts/forms/switch.js')}}"></script>
    <!-- BEGIN: Page JS-->
    <script src="{{ asset('app-assets/js/scripts/pages/dashboard-sales.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js') }}"></script>
    <!-- END: Page JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('app-assets/js/scripts/forms/form-repeater.js') }}"></script>
    <!-- <script src="{{ asset('app-assets/vendors/js/extensions/dropzone.min.js') }}"></script> -->
    <script src="{{ asset('app-assets/vendors/js/ui/prism.min.js') }}"></script>
    <!-- <script src="{{ asset('app-assets/js/scripts/tables/datatables-extensions/datatable-responsive.js') }}"></script> -->
    <!-- END: Page JS-->
    
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>    
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('app-assets/vendors/js/tables/datatable/DT_bootstrap.js') }}"></script>    
    <script type="text/javascript" src="{{ asset('app-assets/vendors/js/tables/datatable/bootstrap-toggle.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.colReorder.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.fixedHeader.min.js') }}"></script>    
    

   
    
    <script src="{{ asset('app-assets/vendors/js/editors/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/editors/editor-ckeditor.js') }}"></script>

    <script src="{{ asset('public/js/custom.js') }}"></script>
   
    <!-- BEGIN: Page JS-->
    <!-- <script src="{{ asset('app-assets/js/scripts/extensions/dropzone.js') }}"></script> -->
    <script src="{{ asset('app-assets/vendors/js/extensions/timedropper.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/ui/jquery-ui/date-pickers.js') }}"></script>
    <!-- END: Page JS-->
    <!-- BEGIN: Page VAlidation JS-->
    <script src="{{ asset('public/js/jquery.form.js') }}"></script>
    <script src="{{ asset('public/js/error.js') }}"></script>
    <script src="{{ asset('public/js/common.js') }}"></script>
    <!-- END: Page Validation JS-->
     <script src="{{ asset('app-assets/external/sortable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('app-assets/external/fileinput.js') }}" type="text/javascript"></script>
    <script src="{{ asset('app-assets/external/theme.js') }}" type="text/javascript"></script>
    <script src="{{ asset('app-assets/external/theme1.js') }}" type="text/javascript"></script>
    <!-- The main application script -->
    <!-- <script src="{{ asset('assets/js/main.js') }}"></script> -->
    <?php
        if(!isset($user))
            $user = (object) array('image' => '');
           // $propert_image ='';
    ?>

    <script>
        $(document).ready(function(){


            jQuery('body').on('click','.apreject',function(e){
            
            
            var id = $(this).attr('data-id');
            var action = $(this).attr('data-action');
            
                if(action == 'approve'){
                 var result = confirm("Are you sure you want to do approve member?");
                 var  new_request = 'Y';
                 var  is_deleted  = '0';
                }else{
                  var result = confirm("Are you sure you want to do reject member?");
                  var  new_request = 'N';
                  var  is_deleted  = '1';
                }
          
            if (result) {
               //  $("body.loading .modal").css("background", "rgba( 255, 255, 255, .8 )url(../../images/loader-64x/Preloader_2.gif)");
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('request-member-user-list') }}",
                    data: {id: id,new_request: new_request,is_deleted:is_deleted},
                    success: function( msg ) {
                    // console.log(msg);
                        if(action == 'approve'){
                       alert('Member request has been approved');
                       }else{
                        alert('Member request has been rejected')
                       }
                        location.reload();

                    }
                });
            }
            else {
                if($(this).is(':checked')){
                    $(this).attr('checked',true);
                }else{
                    $(this).attr('checked',false);
                }
                return true;
            }
        });
       
// switchChange.bootstrapSwitch
       jQuery('#editable-sample').on('change', 'input.status_toggle_class', function(e){
            e.preventDefault();
            e.stopPropagation();
            
            var id = $(this).attr('data-id');
           
            if($(this).is(':checked')){
                var status = 'Y'
            } else {
                var status = 'N'
            }
            
            var result = confirm("Are you sure you want to do this?");
            if (result) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('update-status') }}",
                    data: {id: id, status: status},
                    success: function( msg ) {
                     // alert('Preacher Active successfully');
                    }
                });
            }
            else {
                if($(this).is(':checked')){
                   
                  /*  $(this).parent().removeClass("toggle btn btn-success");
                     $(this).parent().addClass("toggle btn btn-danger off");*/

                    $(this).prop('checked',false);
                }else{

             /*       $(this).parent().removeClass("toggle btn btn-danger off");
                     $(this).parent().addClass("toggle btn btn-success");*/

                    $(this).prop('checked',true);
                }
                return true;
            }
        });

            $( "#start-event" ).datepicker({
                minDate: new Date(),
                showOtherMonths: true,
                selectOtherMonths: true,
                changeMonth: true,
                changeYear: true
            });
            $( "#end-event" ).datepicker({
                minDate: new Date(),
                showOtherMonths: true,
                selectOtherMonths: true,
                changeMonth: true,
                changeYear: true
            });
            $( "#taskDate" ).datepicker({
                minDate: new Date(),
                showOtherMonths: true,
                selectOtherMonths: true,
                changeMonth: true,
                changeYear: true
            });
            $( "#edit-task-date" ).datepicker({
                minDate: new Date(),
                showOtherMonths: true,
                selectOtherMonths: true
            });
            $("#user_image").fileinput({
                'theme': 'explorer-fas',
                overwriteInitial: true,
                initialPreviewAsData: true,
                allowedFileExtensions: ['jpg', 'png', 'gif'],
                'showUpload': false,
                'showCaption': false,
                'showRemove': false,
            });
            $("#edit_user_image").fileinput({
                'theme': 'explorer-fas',
                "showRemove": false,
                "showUpload": false,
                "browseLabel": '',
                allowedFileExtensions: ["jpg", "png", "gif"],
                defaultPreviewContent: '<img src="{{ asset("app-assets/images/ico/logo.png") }}" alt="Your Avatar" style="width:auto;height:auto;max-width:100%;max-height:100%;">',
                initialPreview: [   
                    "<img src = '{{$user->image}}' class='file-preview-image kv-preview-data' title='nature-1.jpg' alt='nature-1.jpg' style='width:auto;height:60px;max-width:100%;max-height:100%;' />",
                ]
            });

            // var fileinput_options = {
            //                         "showRemove": false,
            //                         "showUpload": false,
            //                         "showCaption": false,
            //                         //"browseClass": "btn btn-primary btn-block",
            //                         "theme": 'explorer-fas',
            //                         "uploadUrl": "{{ route('project-image-upload') }}",
            //                         "uploadExtraData": {
            //                             "id": parseInt('0')
            //                         },
            //                         "overwriteInitial": false,
            //                         "initialPreviewAsData": false,
            //                     };

            // var images_element = $("input[type='file'][name='project_image[]']");
            //     images_element.fileinput(fileinput_options).on("filebatchselected", function (event, files) {
            //     images_element.fileinput("upload");
            // });
            $("select.church_name").change(function(){
                var selectedChurch = $(".church_name option:selected").val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('church-fund') }}",
                    data: { churchid : selectedChurch } 
                }).done(function(data){
                    // Get select
                    var select = document.getElementById('fund_name');
                    if(data == "" || data == undefined)
                        $(select).html('<option value="">Select Fund Name</option>');
                    else
                        for (var i in data) {
                            $(select).append('<option value=' + data[i]['id'] + '>' + data[i]['name'] + '</option>');
                        }
                });
            });

                $("select.is_church_referrer").change(function(){
                    var selectedRole = $(".is_church_referrer option:selected").val();
                    var selectedchurch = $("#church_referrer option:selected").val();

                    $.ajax({
                    type: "POST",
                    url: "{{ route('get-referrer-id') }}",
                    data: { id : selectedchurch } 
                }).done(function(data){
                   //console.log(data.is_deleted);
                    if(data){
                   $('#referrer_id').val(data.referrer_Id);
                   $('#vendor_id').val(data.vendor_id);
                   }else{
                    $('#referrer_id').val('');
                   $('#vendor_id').val('');
                   }
                });
                });
            $("select.is_church").change(function(){
                var selectedRole = $(".is_church option:selected").val();
                var selectedchurch = $("#church_list option:selected").val();
                if(selectedRole != 3){
                    $.ajax({
                        type: "get",
                        url: "{{ route('church-list') }}"
                    }).done(function(data){
                        // Get select
                        if(data[i]['id'] == selectedchurch){
                            var selected = 'selected';
                        }else{
                            var selected = '';
                        }
                        var select = document.getElementById('church_list');
                        if(data == "" || data == undefined){
                            $(select).html('<option value="">Select Church</option>');
                        }
                        else{
                            $(select).html('<option value="">Select Church</option>');
                            for (var i in data) {
                                $(select).append('<option value=' + data[i]['id'] + ' '+selected+'>' + data[i]['firstname'] + ' ' + data[i]['lastname'] + '</option>');
                            }
                        }
                    });
                }
                else{
                    var select = document.getElementById('church_list');
                    $(select).html('<option value="">Select Church</option>');
                }
            });
            $('#download_qr_code').click(function() {
                download($('#download_png').attr('src'),"strcode.png","image/png");
            });

            $("#generate_qr_code").click(function(){
                var projectId = $("#project_id").val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('generate-qr-code') }}",
                    data: { id : projectId } 
                }).done(function(data){
                    // Get select
                    var dataid = projectId;
                    var url = '{{ route("qr-code-view", ":id") }}';
                    window.location.href = url.replace(':id', dataid);
                });
            });
            $("#church_generate_qr_code").click(function(){

                var user_id = $("#user_id").val();
                var qrcode = $("#qrcode").val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('church-generate-qr-code') }}",
                    data: { id : user_id,qrcode:qrcode } 
                }).done(function(data){
                    // Get select
                    var dataid = user_id;
                    var url = '{{ route("church-qr-code-view", ":id") }}';
                    window.location.href = url.replace(':id', dataid);
                });
            });
            $("#church_generate_qr_code_vistor").click(function(){

                var user_id = $("#user_id").val();
                var qrcode = $("#qrcode_visitor").val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('church-generate-qr-code-visitor') }}",
                    data: { id : user_id,qrcode:qrcode } 
                }).done(function(data){
                    // Get select
                    var dataid = user_id;
                    var url = '{{ route("church-qr-code-view", ":id") }}';
                    window.location.href = url.replace(':id', dataid);
                });
            });
            $("#user_generate_qr_code").click(function(){

                var user_id = $("#user_id").val();
                var qrcode = $("#qrcode").val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('user-generate-qr-code') }}",
                    data: { id : user_id,qrcode:qrcode } 
                }).done(function(data){
                    // Get select
                    var dataid = user_id;
                    var url = '{{ route("user-qr-code-view", ":id") }}';
                    window.location.href = url.replace(':id', dataid);
                });
            });
             $("select.c-select").change(function(){
                var selectedChurch = $(".c-select option:selected").val();
                var values = $("input[name='userid[]']")
              .map(function(){return $(this).val();}).get();
               
               if(selectedChurch){
                $.ajax({
                    type: "POST",
                    url: "{{ route('get-group-users') }}",
                    data: { churchid : selectedChurch } 
                }).done(function(data){
                    // Get select

                    var select = document.getElementById('group_user');
                    $(select).html('');
                    if(data == "" || data == undefined)
                        $(select).html('No User Found');
                    else
                        var selected = '';

                        for (var i in data) {
                            if(jQuery.inArray(data[i]['id'].toString(), values) != -1) {
                            var selected = 'selected';
                            } else {
                            var selected = '';
                            }  
                            $(select).append('<option value=' + data[i]['id'] + ' '+selected+'>' + data[i]['firstname'] + ' ' + data[i]['lastname'] + '</option>');
                            
                        }
                        $('select[name="user_id[]"]').multiselect('rebuild');
                        
                });
            }
            });

             $("select.user-select").change(function(){
                var selectedChurch = $(".user-select option:selected").val();
                var values = $("input[name='userid[]']")
              .map(function(){return $(this).val();}).get();
               var members = $("input[name='member[]']")
              .map(function(){return $(this).val();}).get();
              
               if(selectedChurch){
                $.ajax({
                    type: "POST",
                    url: "{{ route('get-group') }}",
                    data: { churchid : selectedChurch } 
                }).done(function(data){
                    // Get select
                  var data  = JSON.parse(data);
                 
                   console.log(data['arr1']);

                    var select = document.getElementById('location2');
                    $(select).html('');
                    if(data['arr1'] == "" || data['arr1'] == undefined && data['arr2'] == "" || data['arr2'] == undefined)
                        $(select).html('No User Found');
                    else
                        var selected = '';

                        for (var i in data['arr1']) {
                            if(jQuery.inArray(data['arr1'][i]['id'].toString(), values) != -1) {
                            var selected = 'selected';
                            } else {
                            var selected = '';
                            }  
                            $(select).append('<option value=group_' + data['arr1'][i]['id'] + ' '+selected+'>' + data['arr1'][i]['group_title'] + ' - (Group)</option>');
                            
                        }
                         for (var i in data['arr2']) {
                            if(jQuery.inArray(data['arr2'][i]['id'].toString(), members) != -1) {
                            var selected1 = 'selected';
                            } else {
                            var selected1 = '';
                            }  
                            $(select).append('<option value=member_' + data['arr2'][i]['id'] + ' '+selected1+'>' + data['arr2'][i]['firstname'] + ' ' + data['arr2'][i]['lastname'] + ' - (Individual Member)</option>');
                            
                        }

                        $('select[name="group_list[]"]').multiselect('rebuild');
                        
                });

            }
            });

        });
        $("#title_cms").keyup(function(){
                $("#slug_cms").val($("#title_cms").val().trim().toLowerCase().replace(/\s+/g, '-'));
        });
        $("#generate_slug").click(function(){
            if($('#slug_cms').attr('readonly') == undefined){
                $('#slug_cms').attr('readonly', true);
                $("#slug_cms").val($("#title_cms").val().trim().toLowerCase().replace(/\s+/g, '-'));
                $("#generate_slug").html('Generate Own Slug');
            }
            else{
                $('#slug_cms').attr('readonly', false);
                $("#slug_cms").val('');
                $("#generate_slug").html('System Generated Slug');
            }
        });

       
     CKEDITOR.replace('shortDescription1');
    </script>
<?php if(Route::currentRouteName() == 'add-project-image-setting'){ ?>  
      <script type="text/javascript">
         $(document).ready(function () {
         var fileinput_options = {
                    "maxFileCount": 4,
                       "showRemove": false,
                       "showUpload": false,
                       "showCaption": false,
                       //"browseClass": "btn btn-primary btn-block",
                       "theme": 'explorer-fas',
                       "uploadUrl": "{{route('project_global.service_provider_image_upload_default')}}",
                       "uploadExtraData": {
                           "id": parseInt('0')
                       },
                       "overwriteInitial": false,
                       "initialPreviewAsData": false,
                       "validateInitialCount": true,
         
                   };
                   var images_element = $("input[type='file'][name='project_image[]']");
                   
                   var result_sprovider_images = @json([$propert_image]);
                    
                   if (result_sprovider_images.length > 0 && result_sprovider_images != '') {
                       fileinput_options.initialPreview = [];
                       fileinput_options.initialPreviewConfig = [];

                       $.each(result_sprovider_images, function (k, row) {
                         $.each(row, function (k, row1) {
                           fileinput_options.initialPreview.push('<img src="' + row1.images + '" class="file-preview-image kv-preview-data"/><input type="hidden" name="sprovider_images_id[]" value="' + row1.id + '" />');
                           fileinput_options.initialPreviewConfig.push({
                               "url": "{{ route('upload_myfile_default') }}",
                               "key": row1.id,
                               "extra": {
                                   "id": row1.id,
                                   "_token": $('meta[name="csrf-token"]').attr('content')
                               }
                           });
                       });
                       });
                   }
               
                   images_element.fileinput(fileinput_options).on("filebatchselected", function (event, files) {
                       images_element.fileinput("upload");
                   });

        });
    </script>
<?php } ?>
<?php if(Route::currentRouteName() == 'project-add' || Route::currentRouteName() == 'project-edit'){ ?>   

    <script type="text/javascript">
         $(document).ready(function () {
         var fileinput_options = {
                       
                       "showRemove": false,
                       "showUpload": false,
                       "showCaption": false,
                       //"browseClass": "btn btn-primary btn-block",
                       "theme": 'explorer-fas',
                       "uploadUrl": "{{route('project.service_provider_image_upload')}}",
                       "uploadExtraData": {
                           "id": parseInt('0')
                       },
                       "overwriteInitial": false,
                       "initialPreviewAsData": false,
                   };
                   var images_element = $("input[type='file'][name='project_image[]']");
                   
                   var result_sprovider_images = @json([$propert_image]);
                    
                   if (result_sprovider_images.length > 0 && result_sprovider_images != '') {
                       fileinput_options.initialPreview = [];
                       fileinput_options.initialPreviewConfig = [];

                       $.each(result_sprovider_images, function (k, row) {
                         $.each(row, function (k, row1) {
                           fileinput_options.initialPreview.push('<img src="' + row1.images + '" class="file-preview-image kv-preview-data"/><input type="hidden" name="sprovider_images_id[]" value="' + row1.id + '" />');
                           fileinput_options.initialPreviewConfig.push({
                               "url": "{{ route('upload_myfile') }}",
                               "key": row1.id,
                               "extra": {
                                   "id": row1.id,
                                   "_token": $('meta[name="csrf-token"]').attr('content')
                               }
                           });
                       });
                       });
                   }
               
                   images_element.fileinput(fileinput_options).on("filebatchselected", function (event, files) {
                       images_element.fileinput("upload");
                   });

        });
    </script>
<?php } ?>

<link rel="stylesheet" href="{{ asset('app-assets/js/data-tables/DT_bootstrap.css') }}" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="{{ asset('app-assets/js/bootstrap/css/bootstrap-toggle.min.css') }}" />
<script src="{{ asset('app-assets/js/bootstrap/js/multiselect.js') }}"></script>


<script type="text/javascript">
    var available_to_work_multiselect_button_text = function (options, select) {
    if (options.length === 0) {
        return this.nonSelectedText;
    } else {
        return 'Select Group Member (' + options.length + ')';
    }
};

$('select[name="user_id[]"]').multiselect({
"buttonWidth": "100%",
"maxHeight": 500,
"enableCaseInsensitiveFiltering": true,
"nonSelectedText": 'Select Group Member',
"buttonText": available_to_work_multiselect_button_text,
"buttonTitle": available_to_work_multiselect_button_text,
"onChange": function (option, checked, select) {
    $(this.$select[0]).blur();
}
});
 var available_to_work_multiselect_button_text2 = function (options, select) {
    if (options.length === 0) {
        return this.nonSelectedText;
    } else {
        return 'Select Group (' + options.length + ')';
    }
};

$('select[name="group_list[]"]').multiselect({
"buttonWidth": "100%",
"maxHeight": 500,
"enableCaseInsensitiveFiltering": true,
"nonSelectedText": 'Select Group',
"buttonText": available_to_work_multiselect_button_text2,
"buttonTitle": available_to_work_multiselect_button_text2,
"onChange": function (option, checked, select) {
    $(this.$select[0]).blur();
}
});
</script>
    <script type="text/javascript">
    $(document).ready(function(){
    $("select.c-select").trigger("change");
    $("select.user-select").trigger("change");
    });

function addmultichutch(){
    
    var userids = $("#userids").val();
    var church_ids = $("#church_ids").val();
    var user_role_ids = $("#user_role_idss").val();
    if(church_ids == '' || church_ids == null){
    $('#church_ids').css({"border-color": "red", 
             "border-width":"1px", 
             "border-style":"solid"});
      return false;
    }else{
      $('#church_ids').css({"border-color": "#cacfe7", 
             "border-width":"1px", 
             "border-style":"solid"});
     
    }
    if(user_role_ids == '' || user_role_ids == null){
        $('#user_role_idss').css({"border-color": "red", 
             "border-width":"1px", 
             "border-style":"solid"});
     return false;
    }else{
     $('#user_role_idss').css({"border-color": "#cacfe7", 
             "border-width":"1px", 
             "border-style":"solid"});
       
    }
$.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type: "POST",
    url: "{{ route('action-morechurch-add') }}",
    data: {user_id: userids,church_id: church_ids,user_role_id:user_role_ids},
    success: function( msg ) {

     $("#churchreplace").html(msg['append_church']);
     $("#appendchurch").append(msg['append_data']);
     $("#user_role_idss")[0].selectedIndex = 0;
     if(msg['count_church'] == 0){
     $("#countdata").hide();   
     }
     $('#inlineForm').modal('hide');
     /*if(msg == 'approve'){
       alert('Member request has been approved');
       }else{
        alert('Member request has been rejected')
       }*/
       // location.reload();

    }
});
}
function switchuserrole(user_role_id,user_id,church_id){
var result = confirm("Are you sure want to switch user role?");
if(result){
$.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type: "POST",
    url: "{{ route('upate_user_role') }}",
    data: {user_role_id: user_role_id,user_id: user_id,church_id:church_id},
    success: function( msg ) {
   
     /*if(msg == 'approve'){
       alert('Member request has been approved');
       }else{
        alert('Member request has been rejected')
       }*/
     location.reload();

    }
});
}
}
                                      
   $(document).ready(function () {

                $(function(){
        var dtToday = new Date();
    
        var month = dtToday.getMonth() + 1;// jan=0; feb=1 .......
        var day = dtToday.getDate();
        var year = dtToday.getFullYear() - 10;
        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();
        var minDate = year + '-' + month + '-' + day;
        var maxDate = year + '-' + month + '-' + day;
        $('#dob').attr('max', maxDate);
    });
            });
    </script>

<?php if(Session::has('triggeronnext')){ ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('a[href="#next"]').click(); 
        });   
    </script>               
<?php
    Session::forget('triggeronnext');
} 
?> 
</body>
<!-- END: Body-->
</html>
