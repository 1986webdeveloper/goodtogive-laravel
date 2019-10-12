<style type="text/css">
  


body, html {
  margin: 0 auto;
  width: 100%;
  height: 100%;
  background-color: #c7c7c7;
  background-image: url("http://192.168.1.39/goodtogive-web/public/storage/public/storage/user_images/1567420781_project_image_1.jpg");
  /*background-repeat: no-repeat;*/
}



.me {
  position: relative;
  background-size: auto;
  /*background-repeat: no-repeat;*/
  display: inline-block;
  margin: 2.2px;
  margin-top: -0.5px;
  border-radius: 10px;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
  transition: .2s;
}

.full {
  height: 100%;
  width: 100%;
  border-radius: 15px;
}

/*.me_0 {
  height:101.99px;
  width:101.99px;
  background-position: -0px -0px;
}

.me_1 {
  height:101.99px;
  width:101.99px;
  background-position: -133.33px -0px;
}

.me_2 {
  height:101.99px;
  width:101.99px;
  background-position: -266.66px -0px;
}

.me_3 {
  height:101.99px;
  width:101.99px;
  background-position: -399.99px -0px;
}

.me_4 {
  height:101.99px;
  width:101.99px;
  background-position: -533.32px -0px;
}

.me_5 {
  height:101.99px;
  width:101.99px;
  background-position: -666.65px -0px;
}

.me_6 {
  height:101.99px;
  width:101.99px;
  background-position: -0px -133.33px;
}

.me_7 {
  height:101.99px;
  width:101.99px;
  background-position: -133.33px -133.33px;
}

.me_8 {
  height:101.99px;
  width:101.99px;
  background-position: -266.66px -133.33px;
}

.me_9 {
  height:101.99px;
  width:101.99px;
  background-position: -399.99px -133.33px;
}

.me_10 {
  height:101.99px;
  width:101.99px;
  background-position: -533.32px -133.33px;
}

.me_11 {
  height:101.99px;
  width:101.99px;
  background-position: -666.65px -133.33px;
}

.me_12 {
  height:101.99px;
  width:101.99px;
  background-position: -0px -266.66px;
}

.me_13 {
  height:101.99px;
  width:101.99px;
  background-position: -133.33px -266.66px;
}

.me_14 {
  height:101.99px;
  width:101.99px;
  background-position: -266.66px -266.66px;
}

.me_15 {
  height:101.99px;
  width:101.99px;
  background-position: -399.99px -266.66px;
}

.me_16 {
  height:101.99px;
  width:101.99px;
  background-position: -533.32px -266.66px;
}

.me_17 {
  height:101.99px;
  width:101.99px;
  background-position: -666.65px -266.66px;
}
.me_18, .me_19,.me_20,.me_21,.me_22,.me_23,.me_24,.me_25,.me_26,.me_27,.me_28,.me_29,.me_30,.me_31,.me_32,.me_33,.me_34,.me_35,.me_36,.me_37,.me_38,.me_39,.me_40,.me_41,.me_42,.me_43,.me_44,.me_45,.me_46,.me_47,.me_48,.me_49,.me_50,.me_51,.me_52,.me_53,.me_54,.me_55,.me_56,.me_57,.me_58,.me_59,.me_60,.me_61,.me_62,.me_63,.me_64,.me_65,.me_66,.me_67,.me_68,.me_69,.me_70,.me_71,.me_72,.me_73,.me_74,.me_75,.me_76,.me_77,.me_78,.me_79,.me_80,.me_81,.me_82,.me_83,.me_84,.me_85,.me_86,.me_87,.me_88,.me_89,.me_90,.me_91,.me_92,.me_93,.me_94,.me_95,.me_96,.me_97,.me_98,.me_99, .me_100 {
  height:101.99px;
  width:101.99px;
  background-position: -666.65px -266.66px;
}*/


.correct {
  border-radius: 0px;
  box-shadow: 0 0 0 transparent, 0 0 0 transparent;
  animation: corect .5s ease;
  animation-delay: .2s;
}

@keyframes corect {
  0% {
    transform: scale(1);
    border-radius: 10px;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
  }
  50% {
    transform: scale(1.25);
    border-radius: 5px;
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
  }
  100% {
    transform: scale(1);
    border-radius: 0px;
    box-shadow: 0 0 0 transparent, 0 0 0 transparent;
  }
}
.pre_img {
  margin-top: 10px;
  width: 100%;
  position: absolute;
  left: 100px;
}

.pre_img li {
  display: inline-block;
  list-style: none;
}

.pre_img li img {
  width: 150px;
  height: 75px;
  position: relative;
  cursor: pointer;
}

.cover {
  display: none;
  position: absolute;
  background-color: rgba(0, 0, 0, 0.38);
  width: 100%;
  height: 100%;
  z-index: 9999;
}

.score {
  margin: 13% auto;
  padding: 20px;
  background: #fff;
  border: 1px solid #666;
  width: 300px;
  box-shadow: 0 0 50px rgba(0, 0, 0, 0.8);
  position: relative;
}

#scr_head {
  text-align: center;
  font-weight: 600;
  font-size: 30px;
  font-family: cursive;
  color: #3d3d3d;
}

#scr_time {
  text-align: center;
  font-weight: 600;
  font-size: 22px;
  font-family: cursive;
  color: #3d3d3d;
}

#scr_moves {
  text-align: center;
  font-weight: 600;
  font-size: 22px;
  font-family: cursive;
  color: #3d3d3d;
}


.i {
  text-align: center;
  font-family: cursive;
  font-weight: 550;
  color: #3c3c3c3;
}
</style>
<?php 
    $j = 0;
    $k = 0;
  for ($i=0; $i<=100; $i++){  
    $pos = 154 * $j; 
    $poss = 154 * $k; 
?>

  <style type="text/css">
    .me_<?php echo $i; ?> {
      height:154px;
      width:154px;
      background-position: <?php echo '-'.$pos.'px';?> <?php echo '-'.$poss.'px';?>;
    }
  </style>  

<?php 
    if($j == 9){
      $j = 0;
      $k = $k + 1; 
    }else{
      $j++;
    }
  }  
?>

<meta name="csrf-token" content="{{ csrf_token() }}">
  <input type="hidden" name="" id="rendoms" value="{{$project->temppercentage}}">
  <input type="hidden" name="" id="exepdata" value="">
  <input type="hidden" name="percentag_val" id="percentag_val" value="{{$project->temppercentage}}">
  <!-- <a href="#" class="button start">Start</a> -->
  <div class="box"> 
    <div class="me full"></div>
  </div>
  <div class="pre_img"> 
<!--    <li data-bid="0"><img src="https://preview.ibb.co/kMdsfm/kfp.png"></li>
    <li data-bid="1"><img src="https://preview.ibb.co/kWOEt6/minion.png"></li>
    <li data-bid="2"><img src="https://preview.ibb.co/e0Rv0m/ab.jpg"></li>  
    <li data-bid="3" id="upfile1"><img src="https://image.ibb.co/cXSomR/upload1.png" /></li>
    <input type="file" name="image" id="file1" style="display: none">
  </div> -->
  <!-- <div align="center"><a href="#" class="button reset" align="center">Reset</a></div> -->
</div>

<!-- <div class="cover" >
  <div class="score">
    <p id="scr_head"> &#9875 Puzzel Solved &#9875</p>
    <p id="scr_time"> Time : <span id="min">00</span> Min <span id="sec">00</span> Sec</p>
    <p id="scr_moves"> Moves : <span id="moves"></span></p>
    <p class="i">developed by mayur birle</p>
    <div class="button OK">OK</div>
  </div>
</div> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php session_start(); ?>
<script type="text/javascript">

  $(document).ready(function() {


  var box = $(".box"),
    orginal = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100],
    temp = orginal,
    x = [],
    sec = 0,
    date1,date2,
    moves = 0,
    mm = 0,
    ss = 0,
    upIMG,
    images = ["https://sector4.acquaintsoft.com/goodtogive-web/public/storage/public/storage/user_images/1567772093_project_image_1.jpg"]
    img = 0;




  $('.me').css({"background-image" : 'url('+images[0]+')'});

  $( document ).ready(function() {
    $(".start").delay(100).slideUp(500);
    $(".full").hide();
    $(".pre_img").addClass("prevent_click");
    
    date1 = new Date();
    Start();
    return 0;
  });

  function Start() {
    randomTile();
    changeBG(img);
    var count = 0,
      a,
      b,
      A,
      B;
    /*$(".me").click(function() {
      count++;
      if (count == 1) {
        a = $(this).attr("data-bid");
        $('.me_'+a).css({"opacity": ".65"});
      } else {
        b = $(this).attr("data-bid"); 
        $('.me_'+a).css({"opacity": "1"});
        if (a == b) {
        } else {
          $(".me_" + a)
            .addClass("me_" + b)
            .removeClass("me_" + a);
          $(this)
            .addClass("me_" + a)
            .removeClass("me_" + b);
          $(".me_" + a).attr("data-bid", a);
          $(".me_" + b).attr("data-bid", b);
        }
        moves++;
        swapping(a, b);
        checkCorrect(a);
        checkCorrect(b);
        a = b = count = A = B = 0;
      }
      if (arraysEqual(x)) { 
        date2 = new Date();
        timeDifferece();
        showScore();
        return 0;
      }
    });*/
    return 0;
  }

  function randomTile() {
    var i;
    for (i = orginal.length-1; i >= 0; i--) {
      var flag = i;
      x[i] = temp[flag];
      temp[flag] = temp[i];
      temp[i] = x[i];
    }
    for (i = 0; i < orginal.length; i++) {
      box.append(
        '<div id="me_'+ x[i] +'"  class="me me_' + x[i] + ' tile" data-bid="' + x[i] + '"></div>'
      );
      if ((i + 1) % 15 == 0) box.append("<br>");
    }
    i = 100;
    tests(); 
    return 0;

  }

  function arraysEqual(arr) {
    var i;
    for (i = orginal.length - 1; i >= 0; i--) {
      if (arr[i] != i) return false;
    }
    return true;
  }

  function checkCorrect(N1) {
    var pos = x.indexOf(parseInt(N1, 10));
    if (pos != N1) {
      return;
    }
    $(".me_" + N1).addClass("correct , prevent_click ");
    return;
  }

  function swapping(N1, N2) {
    var first = x.indexOf(parseInt(N1, 10)),
      second = x.indexOf(parseInt(N2, 10));
    x[first] = parseInt(N2, 10);
    x[second] = parseInt(N1, 10);
    return 0;
  }
  
  function getRandom(min, max) {
      return Math.floor(Math.random() * (max - min + 1)) + min;
    }
  
  function timeDifferece(){
    var diff = date2 - date1;
    var msec = diff;
    var hh = Math.floor(msec / 1000 / 60 / 60);
    msec -= hh * 1000 * 60 * 60;
    mm = Math.floor(msec / 1000 / 60); // Gives Minute
    msec -= mm * 1000 * 60;
    ss = Math.floor(msec / 1000);   // Gives Second
    msec -= ss * 1000;
    return 0;
  }


  function changeBG(img){ 
    if(img != 3){
    $('.me').css({
      "background-image" : "url("+images[img]+")"
    });
    return
    }
    else
      $('.me').css({"background-image" : "url("+upIMG+")"});
  }

  $('.pre_img li').hover(function(){
      img = $(this).attr("data-bid");
      changeBG(img);

    });
  
  function showScore(){
    $('#min').html(mm);
    $('#sec').html(ss);
    $('#moves').html(moves);
    setTimeout(function(){
    $('.cover').slideDown(350);
    },1050);
    return 0;
  }

  $('.OK').click(function(){
    $('.cover').slideUp(350);
  });

  $('.reset').click(function(){
    $(".tile").remove();
    $("br").remove();
    $(".full").show();
    $(".start").show();
    $(".pre_img").removeClass("prevent_click");
    
    temp = orginal;
    x = [];
    moves =  ss = mm = 0;
    return 0;
  });

  $("#upfile1").click(function () {
     $("#file1").trigger('click');
  });

  $("#file1").change(function(){
        readURL(this);
    });

     function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
               upIMG =  e.target.result;
               img = 3;
               changeBG(3);
            }
            reader.readAsDataURL(input.files[0]);
        }

    }
  
});

function tests(){
  
   var rendoms = $('#rendoms').val();
   var exepdata = $('#exepdata').val();
   var trainindIdArray = exepdata.replace("[","").replace("]","").split(',');

   var noneed = []; 
      var x = [];
    var orginals = [];
    for (var i = 0; i <= 100; i++) {
      if(!trainindIdArray.includes(i.toString())){
          orginals.push(i);
      }      
    }
    console.log(orginals);
    temp = orginals;
    var i;
    var val = [];


    var i;
    for (i = orginals.length-1; i >= 0; i--) {
      var flag = getRandom(0,i);
      x[i] = temp[flag];
      temp[flag] = temp[i];
      temp[i] = x[i];
    }

    function getRandom(min, max) {
      return Math.floor(Math.random() * (max - min + 1)) + min;
    }

          
        for (i = 0; i < orginals.length; i++) {
      if(i < rendoms){  
      noneed.push(x[i]);
      
      $("#me_"+x[i]).css("opacity","0");
      }
    }
    $('#exepdata').val(exepdata+','+noneed);

    
}


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
    var rendoms = $('#rendoms').val();
    if(rendoms != msg.tempper){
      var totalsum = (msg.tempper - rendoms);
      $('#rendoms').val(totalsum);
    tests();  
    $('#rendoms').val(msg.tempper);
    }
   // $('.left-background').css({"width":msg.per});
   // $('#amount-teffect').html(msg.donation); 
   }
   });
   }, 1000);

</script>

