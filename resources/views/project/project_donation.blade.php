<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
 <title>{{ $title }}</title>
 <link rel="apple-touch-icon" href="{{ asset('app-assets/images/ico/favicon.ico') }}">
  <link rel="shortcut icon" type="image/x-icon" href="{{asset('app-assets/images/ico/favicon.ico')}}">
<style type="text/css">

@font-face {
  font-family: 'GIL';
  src: url('{{ asset('app-assets/real-time-donation/fonts/GIL.eot') }}');
  src: url('{{ asset('app-assets/real-time-donation/fonts/GIL.eot') }}') format('embedded-opentype'),
       url('{{ asset('app-assets/real-time-donation/fonts/GIL.woff2') }}') format('woff2'),
       url('{{ asset('app-assets/real-time-donation/fonts/GIL.woff') }}') format('woff'),
       url('{{ asset('app-assets/real-time-donation/fonts/GIL.ttf') }}') format('truetype'),
       url('{{ asset('app-assets/real-time-donation/fonts/GIL.svg#GIL') }}') format('svg');
}
body {
  overflow-x: hidden;
  font-family: 'GIL';
  background-size: cover;
  height: 100%;
  background-image:  url({{$backgroun_image}});
}
.denation-box {
  position: fixed;
  z-index: 50;
  text-align: center;
  width: 100%;
  top: 50%;
  transform: translateY(-50%);
  left: 0;
  right: 0;
  color: {{$font_color}}; 
  font-family: 'GIL';

}
.denation-box .denation-title {
  font-size: 90px;
  font-weight: 700;
  text-align: center;
  display: inline-block;
  width: 100%;
  margin: 0 0 0 0;
}
.denation-box .project-name {
  font-size: 70px;
  font-weight: normal;
  text-align: center;
  display: inline-block;
  width: 100%;
  margin: 20px 0;
}
.denation-box .amount-text {
  font-size: 80px;
  font-weight: normal;
  text-align: center;
  display: inline-block;
  width: 100%;
  margin: 0 0 40px;
}
.denation-box .amount-text span {
  font-weight: 700;
}
.border-box {
  border: 1px solid {{$color_br}};
  margin: 0 auto;
  max-width: 1170px;
  padding: 30px;
  position: relative;
}
.border-box .top-border {
  content: "";
  height: 1px;
  width: 40px;
  position: absolute;
  left: -41px;
  background: {{$color_br}};
  top: -1px;
}
.border-box .top-border:before {
  content: "";
  height: 40px;
  width: 1px;
  position: absolute;
  left: 40px;
  background: {{$color_br}};
  top: -40px;
}
.border-box .left-border {
  content: "";
  height: 1px;
  width: 40px;
  position: absolute;
  right: -41px;
  background: {{$color_br}};
  top: -1px;
}
.border-box .left-border:before {
  content: "";
  height: 40px;
  width: 1px;
  position: absolute;
  right: 40px;
  background: {{$color_br}};
  top: -40px;
}
.border-box .bottom-border {
  content: "";
  height: 1px;
  width: 40px;
  position: absolute;
  left: -41px;
  background: {{$color_br}};
  bottom: -1px;
}
.border-box .bottom-border:before {
  content: "";
  height: 40px;
  width: 1px;
  position: absolute;
  left: 40px;
  background: {{$color_br}};
  bottom: -40px;
}
.border-box .right-border {
  content: "";
  height: 1px;
  width: 40px;
  position: absolute;
  right: -41px;
  background: {{$color_br}};
  bottom: -1px;
  display: block;
}
.border-box .right-border:before {
  content: "";
  height: 40px;
  width: 1px;
  position: absolute;
  right: 40px;
  background: {{$color_br}};
  bottom: -40px;
}
.img-box {
  width: 100%;
  max-width: 300px;
  display: inline-block;
}
.img-box img {
  width: auto;
  border-radius: 20px;
  height: 200px;
/*  border: 2px solid rgba(255,255,255,0.7);*/
}
.border-box{
    margin-top: 70px
    margin-bottom: 70px
}
.left-background {
    position: absolute;
    top: 0;
    left: -{{$project->percentage}}%;
    width: {{$project->percentage}}%;
    height: 100vh;
    background-color: {{$background_color}};
    -webkit-animation: left-background 0.6s forwards;
    -webkit-animation-delay: 0.6s;
    animation: left-background 0.6s forwards;
    animation-delay: 0.6s;
    transition: width .6s ease-in-out;
}
@-webkit-keyframes left-background {
    100% { left: 0; }
}
@keyframes left-background {
    100% { left: 0; }
}

.right-background {
    position: absolute;
    top: 0;
    right: -{{$project->percentage}}%;
    width: {{$project->percentage}}%;
    height: 100vh;
    background-color: {{$background_color}};
    -webkit-animation: right-background 0.6s forwards;
    -webkit-animation-delay: 0.6s;
    animation: right-background 0.6s forwards;
    animation-delay: 0.6s;
    transition: width .6s ease-in-out;
}
@-webkit-keyframes right-background {
    100% { right: 0; }
}
@keyframes right-background {
    100% { right: 0; }
}

.top-background {
    position: absolute;
    top: -{{$project->percentage}}%;
    right:0;
    left: 0;
    width: 100%;
    height: {{$project->percentage}}vh;
    background-color: {{$background_color}};
    -webkit-animation: top-background 0.6s forwards;
    -webkit-animation-delay: 0.6s;
    animation: top-background 0.6s forwards;
    animation-delay: 0.6s;
    transition: width .6s ease-in-out;
}
@-webkit-keyframes top-background {
    100% { top: 0; }
}
@keyframes top-background {
    100% { top: 0; }
}

.bottom-background {
    position: absolute;
    bottom: -{{$project->percentage}}%;
    right:0;
    left: 0;
    width: 100%;
    height: {{$project->percentage}}vh;
    background-color: {{$background_color}};
    -webkit-animation: bottom-background 0.6s forwards;
    -webkit-animation-delay: 0.6s;
    animation: bottom-background 0.6s forwards;
    animation-delay: 0.6s;
    transition: width .6s ease-in-out;
}
@-webkit-keyframes bottom-background {
    100% { bottom: 0; }
}
@keyframes bottom-background {
    100% { bottom: 0; }
}

@media screen and (max-height: 600px) {
   .denation-box .denation-title {
      font-size: 60px; 
   }
    .denation-box .project-name, .denation-box .amount-text {
      font-size: 50px; 
   }
}
@media only screen and (min-width: 1024px) {
  body {
    overflow: hidden;
  }
}
@media only screen and (max-width: 1168px) {
  .denation-box {
    padding: 50px;
  }
}
@media only screen and (max-width: 767px) {
  .denation-box {
    position: relative;
    top: 0;
    transform: translateY(0);
    padding: 0 0 0 0;
  }
   .denation-box .denation-title {
      font-size: 40px; 
   }
    .denation-box .project-name, .denation-box .amount-text {
      font-size: 30px; 
   }
}
</style>
<!------ Include the above in your HEAD tag ---------->
<!-- {{$transition_position}} -->
<div id="donection_affected">
<div class="left-background" id="slide"></div>
<div class="container">
  <div class="denation-box">
    <div class="border-box">
    <div class="top-border"></div>
    <div class="left-border"></div>
    <h1 class="denation-title">DONATION</h1>    
         <div id="donection_affected">
              <span class="project-name">{{$project->name}}</span>
              <h2 class="amount-text">Amount : <span id="amount-teffect"><span>&#163;</span>{{$project->total_amount}} /  <span>&#163;</span>{{$project->goal_amount}}</span></h2>
              <div class="img-box"><img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(300)
->generate($project->qrcode))!!} "></div>
         </div>
          <input type="hidden" name="percentag_val" id="percentag_val" value="{{$project->percentage}}">
          <div class="right-border"></div>
          <div class="bottom-border"></div>
         </div>
         <!--   
      <div class="col-sm-6">
         <div id="canvas-chart-update">
         <div class="progressbar">
         <div class="second circle" data-percent="{{$project->percentage}}">
         <strong></strong>
         </div>
         </div>
         <input type="hidden" name="percentag_val" id="percentag_val" value="{{$project->percentage}}">
         </div>
      </div> -->      
   </div>
 </div>
</div>
<script src="https://rawgit.com/kottenator/jquery-circle-progress/1.2.2/dist/circle-progress.js"></script>
<script type="text/javascript">
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
    $('.left-background').css({"width":msg.per});
    $('#amount-teffect').html(msg.donation); 
   }
   });
   }, 1000);
</script>