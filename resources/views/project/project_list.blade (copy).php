



<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
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
<h1>DONATION</h1>

<p><?php echo $project->name; ?></p>
<h2>Amount :  <?php echo $project->goal_amount; ?>  / <?php echo $project->total_amount; ?></h2> 
 <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(300)->generate($project->qrcode))!!} ">

<div id="bars">
   
    <div class="bar" data-percent="<?php echo $project->percentage ?>">
       
        <canvas class="bar-circle" width="70" height="70"></canvas>
    </div>
   
   
</div>  
</body>
</html>




<script type="text/javascript">
    
    /* Credits: 
 * https://www.developphp.com/video/JavaScript/Circular-Progress-Loader-Canvas-JavaScript-Programming-Tutorial
 */

(function() {
    
    var Progress = function( element ) {
        
        this.context = element.getContext( "2d" );
        this.refElement = element.parentNode;
        this.loaded = 0;
        this.start = 4.72;
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
            self.context.strokeStyle = "#asdsad";
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
</script>