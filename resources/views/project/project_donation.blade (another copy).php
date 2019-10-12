<!DOCTYPE html>
<html>
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
         window.AJAX_SITE_URL = "http://192.168.1.39/goodtogive-web/admin/";
         //window.AJAX_SITE_URL = "https://sector4.acquaintsoft.com/goodtogive-web/admin/";
      </script> 
      <style>
         * {box-sizing: border-box}
         .container {
         width: 100%;
         background-color: #ddd;
         }
         .skills {
         text-align: right;
         padding-top: 10px;
         padding-bottom: 10px;
         color: white;
         }
         .html {width: 90%; background-color: #4CAF50;}
         .css {width: 80%; background-color: #2196F3;}
         .js {width: 65%; background-color: #f6cc35;}
         .php {width: 60%; background-color: #808080;}
         #bars {
         margin: 2em auto;
         max-width: 960px;
         overflow: hidden;
         }
         .bar {
         float: left;
         width: 20%;
         text-align: center;
         }
         .bar h3 {
         font-size: 1.4em;
         font-weight: normal;
         margin: 0;
         text-transform: uppercase;
         }
         .bar-circle {
         display: block;
         margin: 0.7em auto;
         }
      </style>
   </head>
   <body>
      <div class="content-wrapper">
         <h1>DONATION</h1>
         <div class="content-body">
            <section id="number-tabs">
               <div class="row">
                  <div class="col-6">
                     <div id="donection_affected">
                        <span>{{$project->name}}</span>
                        <h2>Amount : {{$project->total_amount}} /  {{$project->goal_amount}}</h2>
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(300)->generate($project->qrcode))!!} ">
                     </div>
                  </div>
                  <div class="col-6">
                     <div id="canvas-chart-update">
                        <div id="bars">
                           <div class="bar" data-percent="{{$project->percentage}}">
                              <canvas class="bar-circle" width="70" height="70"></canvas>
                           </div>
                        </div>
                        <input type="hidden" name="percentag_val" id="percentag_val" value="{{$project->percentage}}">
                     </div>
                  </div>
               </div>
            </section>
         </div>
      </div>
   </body>
</html>





<!-- BEGIN: Vendor JS-->
<script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
<script type="text/javascript">
   (function() {
   var Progress = function( element ) {
   
   this.context = element.getContext( "2d" );
   this.refElement = element.parentNode;
   this.loaded = 0;
   this.start = 1;
   this.width = this.context.canvas.width;
   this.height = this.context.canvas.height;
   this.total = parseInt( this.refElement.dataset.percent, 10 );
   this.timer = null;
   
   this.diff = 0;
   
   this.init();    
   };
   
   Progress.prototype = {
   init: function() {
   var self = this;
   self.timer = setInterval(function() {
   self.run(); 
   }, 25);
   },
   run: function() {
   var self = this;
   
   self.diff = ( ( self.loaded / 100 ) * Math.PI * 2 * 10 ).toFixed( 2 );  
   self.context.clearRect( 0, 0, self.width, self.height );
   self.context.lineWidth = 10;
   self.context.fillStyle = "#000";
   self.context.strokeStyle = "#f6cc35";
   self.context.textAlign = "center";


   
   self.context.fillText( self.loaded + "%", self.width * .5, self.height * .5 + 2, self.width );
   self.context.beginPath();
   self.context.arc( 35, 35, 30, self.start, self.diff / 10 + self.start, false );
   self.context.stroke();
   
   if( self.loaded >= self.total ) {
   clearInterval( self.timer );
   }
   
   self.loaded++;
   }
   };
   
   var CircularSkillBar = function( elements ) {
   this.bars = document.querySelectorAll( elements );
   if( this.bars.length > 0 ) {
   this.init();
   }   
   };
   
   CircularSkillBar.prototype = {
   init: function() {
   this.tick = 25;
   this.progress();
   
   },
   progress: function() {
   var self = this;
   var index = 0;
   var firstCanvas = self.bars[0].querySelector( "canvas" );
   var firstProg = new Progress( firstCanvas );
   
   
   
   var timer = setInterval(function() {
   index++;
   
   var canvas = self.bars[index].querySelector( "canvas" );
   var prog = new Progress( canvas );
   
   if( index == self.bars.length ) {
       clearInterval( timer );
   } 
   
   }, self.tick * 100);
   
   }
   };
   
   document.addEventListener( "DOMContentLoaded", function() {
   var circularBars = new CircularSkillBar( "#bars .bar" );
   });
   
   })();
   
   window.setInterval(function ()
   {
   var projectid = '<?php echo $project->id; ?>';
   var percentage = $('#percentag_val').val();
   $.ajax({
   headers: {
   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   },
   type: "POST",
   url: "{{ route('ajax_church_donation') }}",
   data: { id : projectid,skillpercentage : percentage },
   success: function( msg ) {
   $('#donection_affected').html(msg.html); 
   if(msg.updated == 'true'){   
   
   $('#canvas-chart-update').html(msg.canvas_chart);  
   
   var Progress = function( element ) {
   this.context = element.getContext( "2d" );
   this.refElement = element.parentNode;
   this.loaded = 0;
   this.start = 1;
   this.width = this.context.canvas.width;
   this.height = this.context.canvas.height;
   this.total = parseInt( this.refElement.dataset.percent, 10 );
   this.timer = null;
   
   this.diff = 0;
   
   this.init();    
   };
   
   Progress.prototype = {
   init: function() {
   var self = this;
   self.timer = setInterval(function() {
   self.run(); 
   }, 25);
   },
   run: function() {
   var self = this;
   self.diff = ( ( self.loaded / 100 ) * Math.PI * 2 * 10 ).toFixed( 2 );  
   self.context.clearRect( 0, 0, self.width, self.height );
   self.context.lineWidth = 10;
   self.context.fillStyle = "#000";
   self.context.strokeStyle = "#f6cc35";
   self.context.textAlign = "center";
   
   self.context.fillText( self.loaded + "%", self.width * .5, self.height * .5 + 2, self.width );
   self.context.beginPath();
   self.context.arc( 35, 35, 30, self.start, self.diff / 10 + self.start, false );
   self.context.stroke();
   
   if( self.loaded >= self.total ) {
   clearInterval( self.timer );
   }
   
   self.loaded++;
   }
   };
   var CircularSkillBar = function( elements ) {
   
   this.bars = document.querySelectorAll( elements );
   if( this.bars.length > 0 ) {
   this.init();
   }   
   };
   // return false;
   CircularSkillBar.prototype = {
   init: function() {
   this.tick = 25;
   this.progress();
   
   },
   progress: function() {
   
   var self = this;
   var index = 0;
   var firstCanvas = self.bars[0].querySelector( "canvas" );
   var firstProg = new Progress( firstCanvas );
   
   
   var timer = setInterval(function() {
   index++;
   
   var canvas = self.bars[index].querySelector( "canvas" );
   var prog = new Progress( canvas );
   
   if( index == self.bars.length ) {
       clearInterval( timer );
   } 
   
   }, self.tick * 100);
   
   }
   };
   var circularBars = new CircularSkillBar( "#bars .bar" );
   
   }
   }
   });
   
   
   }, 1000);
   
   
</script>