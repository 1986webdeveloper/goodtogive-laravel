<!DOCTYPE html>
<html>
<head>
  <title>{{ $title }}</title>
  <!-- Bootstrap CDN's -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
  <style type="text/css">
    img {
      max-width: 100%;
      width: 100%;
      height: 300px;
    }
    .wrapper {
      background-image:  url(<?php echo $backgroun_image; ?>);
      background-size: cover
    }
    .container {
      max-width: 100%;
    }
    .col-lg-4,.col {
      padding: 0;
    }
    .main-button {
      position: absolute;
        z-index: 1;
        left: 50%;
        top: 50%;
        transform: translateY(-50%);
    }
    #myProgress {
      width: 100%;
      background-color: #ddd;
    }

    #myBar {
      width: 0%;
      height: 30px;
      background-color: #4CAF50;
      text-align: center;
      line-height: 30px;
      color: white;
    }
  </style>

</head>

<body>
  <div class="wrapper">
    <div class="main-button"><div id="myProgress">
      <!-- <div id="myBar">0%</div> -->
    </div>
     <!--  <button onclick="move()">click here</button> -->
      <input type="text" value="{{$project->temppercentage}}" id="percentage">
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-4 img1">
          <img src="http://192.168.1.39/goodtogive-web/public/storage/public/storage/user_images/1567420906_project_image_1.jpg">
        </div>
        <div class="col-lg-4 img2">
          <img src="http://192.168.1.39/goodtogive-web/public/storage/public/storage/user_images/1567420906_project_image_1.jpg">
        </div>
        <div class="col-lg-4 img3">
          <img src="http://192.168.1.39/goodtogive-web/public/storage/public/storage/user_images/1567420906_project_image_1.jpg">
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4 img4">
          <img src="http://192.168.1.39/goodtogive-web/public/storage/public/storage/user_images/1567420906_project_image_1.jpg">
        </div>
        <div class="col-lg-4 img5">
          <img src="http://192.168.1.39/goodtogive-web/public/storage/public/storage/user_images/1567420906_project_image_1.jpg">
        </div>
        <div class="col-lg-4 img6">
          <img src="http://192.168.1.39/goodtogive-web/public/storage/public/storage/user_images/1567420906_project_image_1.jpg">
        </div>
      </div>
      <div class="row">
        <div class="col img7">
          <img src="http://192.168.1.39/goodtogive-web/public/storage/public/storage/user_images/1567420906_project_image_1.jpg">
        </div>
        <div class="col img8">
          <img src="http://192.168.1.39/goodtogive-web/public/storage/public/storage/user_images/1567420906_project_image_1.jpg">
        </div>
        <div class="col img9">
          <img src="http://192.168.1.39/goodtogive-web/public/storage/public/storage/user_images/1567420906_project_image_1.jpg">
        </div>
        <div class="col img10">
          <img src="http://192.168.1.39/goodtogive-web/public/storage/public/storage/user_images/1567420906_project_image_1.jpg">
        </div>
      </div>
    </div>
  </div>  
<script type="text/javascript">
  $( document ).ready(function() {
    var width = $( "#percentage" ).val();
    var myrendom = [];
    var test = [];
    for (var a = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10], i = a.length; i--; ) {
        rendom = a.splice(Math.floor(Math.random() * (i + 1)), 1)[0];
        myrendom.push(rendom);
    }

    /* for (var as = [11, 21, 31, 41, 51, 61, 71, 81, 91, 100], j = as.length; j--; ) {
        rendoms = as.splice(Math.floor(Math.random() * (j + 1)), 1)[0];
         $.each(myrendom, function(key,val) {
            test.push(val);
        });


      }*/

      if(width > 10 && width <= 20){

        var widthTest = 1;
      }else if(width > 20 && width <= 30){
        
        var widthTest = 2;
      }else if(width > 30 && width <= 40){
        
        var widthTest = 3;
      }else if(width > 40 && width <= 50){
        
        var widthTest = 4;
      }else if(width > 50 && width <= 60){
        
        var widthTest = 5;
      }else if(width > 60 && width <= 70){
        
        var widthTest = 6;
      }else if(width > 70 && width <= 80){
        
        var widthTest = 7;
      }else if(width > 80 && width <= 90){
        
        var widthTest = 8;
      }else if(width > 90 && width <= 100){
        
        var widthTest = 9;
      }
      
      
      for(i = 0; i < widthTest; i++) { 
        $('.img'+myrendom[i]).css('opacity','0.01');
      }

  });

  function move() {
    var elem = document.getElementById("myBar");   
    var width = 0;
    var id = setInterval(frame, 200);

    function frame() {
      if (width >= 100) {
        clearInterval(id);
      } else {
        width++; 
        elem.style.width = width + '%';
        elem.innerHTML = width * 1  + '%';
        if(width == '11'){
          $('.img10').css('opacity','0.01');
        }
        if(width == '21'){
          $('.img9').css('opacity','0.01');
        }
        if(width == '31'){
          $('.img8').css('opacity','0.01');
        }
        if(width == '41'){
          $('.img7').css('opacity','0.01');
        }
        if(width == '51'){
          $('.img6').css('opacity','0.01');
        }
        if(width == '61'){
          $('.img5').css('opacity','0.01');
        }
        if(width == '71'){
          $('.img4').css('opacity','0.01');
        }
        if(width == '81'){
          $('.img3').css('opacity','0.01');
        }
        if(width == '91'){
          $('.img2').css('opacity','0.01');
        }
        if(width == '100'){
          $('.img1').css('opacity','0.01');
        }
      }
    }
  }
</script>

 <input type="hidden" name="percentag_val" id="percentag_val" value="{{$project->percentage}}">
<script type="text/javascript">
   window.setInterval(function ()
   {

   var projectid = '<?php echo $project->id; ?>';
   var percentage = $('#percentag_val').val();
   var percentages = $('#percentage').val();

   $.ajax({
   headers: {
   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   },
   type: "POST",
   url: "{{ route('ajax_church_donation') }}",
   data: { id : projectid,skillpercentage : percentage },
   success: function( msg ) {
    
    $('#percentage').val(msg.tempper);
   // $('.left-background').css({"width":msg.per});
  
    var width = $( "#percentage" ).val();
    if(percentages != width){
        $('.img10').css('opacity','1');
        $('.img9').css('opacity','1');
        $('.img8').css('opacity','1');
        $('.img7').css('opacity','1');
        $('.img6').css('opacity','1');
        $('.img5').css('opacity','1');
        $('.img4').css('opacity','1');
        $('.img3').css('opacity','1');
        $('.img2').css('opacity','1');
        $('.img1').css('opacity','1');
    var myrendom = [];
    var test = [];
    for (var a = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10], i = a.length; i--; ) {
        rendom = a.splice(Math.floor(Math.random() * (i + 1)), 1)[0];
        myrendom.push(rendom);
    }

    if(width > 10 && width <= 20){
        
        var widthTest = 1;
      }else if(width > 20 && width <= 30){
        
        var widthTest = 2;
      }else if(width > 30 && width <= 40){
        
        var widthTest = 3;
      }else if(width > 40 && width <= 50){
        
        var widthTest = 4;
      }else if(width > 50 && width <= 60){
        
        var widthTest = 5;
      }else if(width > 60 && width <= 70){
        
        var widthTest = 6;
      }else if(width > 70 && width <= 80){
        
        var widthTest = 7;
      }else if(width > 80 && width <= 90){
        
        var widthTest = 8;
      }else if(width > 90 && width < 100){
        
        var widthTest = 9;
      }else if(width == 100){

        var widthTest = 10;
      }
      
      
      for(i = 0; i < widthTest; i++) { 
        $('.img'+myrendom[i]).css('opacity','0.01');
      }
    }
    //$('#amount-teffect').html(msg.donation); 
   }
   });
   }, 1000);
</script>
</body>
</html>



<!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
 <title>{{ $title }}</title>
 <link rel="apple-touch-icon" href="{{ asset('app-assets/images/ico/favicon.ico') }}">
  <link rel="shortcut icon" type="image/x-icon" href="{{asset('app-assets/images/ico/favicon.ico')}}"> -->
<style type="text/css">

/*@font-face {
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
/*}
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
}*/
</style>
<!------ Include the above in your HEAD tag ---------->
<!-- {{$transition_position}} -->
<!-- <div id="donection_affected">
<div class="left-background" id="slide"></div>
<div class="container">
  <div class="denation-box">
    <div class="border-box">
    <div class="top-border"></div>
    <div class="left-border"></div>
    <h1 class="denation-title">DONATION</h1>    
         <div id="donection_affected">
              <span class="project-name">{{$project->name}}</span>
              <h2 class="amount-text">Amount : <span id="amount-teffect">{{$project->total_amount}} /  {{$project->goal_amount}}</span></h2>
              <div class="img-box"><img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(300)
->generate($project->qrcode))!!} "></div>
         </div>
          <input type="hidden" name="percentag_val" id="percentag_val" value="{{$project->percentage}}">
          <div class="right-border"></div>
          <div class="bottom-border"></div>
         </div>
           
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
   <!--</div>
 </div>
</div> -->
