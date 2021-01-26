<!DOCTYPE html> 

<html>
<head>
  <title>Javascript Racer - v4 (final)</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link href="common.css" rel="stylesheet" type="text/css" />
  <style>
      div {
          font-family: Microsoft JhengHei;
      }
      body {
          background-color: #333333;
      }
      .instructions {
          width: 330px;
          height: 185px;
          position: absolute;
          bottom: 19px;
          left: 25px;
          background-color: white;
          border: 2px solid #000;
          padding: 0px 32px 20px;         
      }
      .canvas {
          position: absolute;
          top: 35px;
          left: 140px;
      }
      .bandwidth {
          width: 450px;
          position: absolute;
          top: 23px;
          left: 24px;
          color: white;
          font-size: 40px;
      }
      .integrity {
          width: 125px;
          position: absolute;
          bottom: 23px;
          right: 20px;
          color: #FFFF00;
          font-size: 40px;
      }
      .integrity2 {
          width: 160px;
          position: absolute;
          bottom: 80px;
          right: 0px;
          color: #FFFF00;
          font-size: 30px;
      }
      .info {
          padding: 18px 34px 16px 30px;
          background-color: #FADBD8;
          color: black;
          border: 2px solid #000;
          width: 330px;
          height:210px;
          position: absolute;
          top: 87px;
          left: 25px;
          font-size: 18px;
          text-align: justify;
      }  
      .info1 {
          padding-bottom: 14px;
          font-weight: bold;
          font-size: 20px;
      }  
      .dialog {
          background-color: #CCCCCC ;
          color: black;
          border:4px solid #000;
          width: 650px;
          height:400px;
          font-size: 23px;  
      }
      .dialog1 {
          text-align: center;
          font-size: 55px;
          top: 0px;
      }
      .dialog2 {
          font-family: Microsoft JhengHei;
          padding-top: 20px;
          padding-left: 35px;
          padding-right: 30px;
          text-align: justify;
      }
      .button {
          text-align: center;
          text-decoration: none;
          font-size: 16px;
          margin: 4px 2px;
          transition-duration: 0.4s;
          cursor: pointer; 
          font-family: Microsoft JhengHei;
      }
      .button1 {
          background-color: white; 
          color: black; 
          border: 2px solid #555555;
          padding: 10px 20px;
          
      }
      .button1:hover {
          background-color: #555555;
          color: white;
      }
      .button3 {
          position: absolute;
          bottom: 8px;
          left: 8px;
      }
      .button4 {
          position: absolute;
          bottom: 8px;
          right: 8px;
      }
      .button2 {
          background-color: white; 
          color: #CB4335; 
          border: 2px solid #E74C3C ;
          padding: 15px;
          position: absolute;
          top: 40px;
          right: 30px;
          border-radius: 50%;
      }
      .button22 {
          background-color: #E74C3C ;
          color: white;
      }
  </style>
</head> 

<body>
    
    <div id="info" class="info" >
        <div id="info_title" class="info1"></div>
        <div id="info_content"></div>
    </div>
    <div class="instructions">
        <div style="padding-top: 20px;">Use the <b>arrow keys</b> to drive the car.</div>
        <table style="width:100%">
            <tr>
                <td style="padding: 8px;border-bottom:1px solid #ddd">&uarr;</td>
                <td style="padding: 8px;border-bottom:1px solid #ddd">speed up</td>
            </tr>
            <tr>
                <td style="padding: 8px;border-bottom:1px solid #ddd">&darr;</td>
                <td style="padding: 8px;border-bottom:1px solid #ddd">slow down</td>
            </tr>
            <tr>
                <td style="padding: 8px;border-bottom:1px solid #ddd">&larr;</td>
                <td style="padding: 8px;border-bottom:1px solid #ddd">turn left</td>
            </tr>
            <tr>
                <td style="padding: 8px">&rarr;</td>
                <td style="padding: 8px">turn right</td>
            </tr>
        </table>
    </div>
    <div id="racer" class="canvas">
        <div id="hud">
            <span id="current_lap_time" class="hud">Time: <span id="current_lap_time_value" class="value">0.0</span></span>
            <span id="last_lap_time" class="hud">Last Lap: <span id="last_lap_time_value" class="value">0.0</span></span>
            <span id="fast_lap_time" class="hud">Fastest Lap: <span id="fast_lap_time_value" class="value">0.0</span></span>
        </div>
        <canvas id="canvas">
            Sorry, this example cannot be run because your browser does not support the &lt;canvas&gt; element
        </canvas>
        Loading...
    </div>
    <button class="button button2" id="dscp" style="display: none;">按空白鍵<br>獲取DSCP<br>優先順位</button>
    <div id="bandwidth" class="bandwidth"></div>
    <div class="integrity2">資訊完整性:</div>
    <div id="integrity" class="integrity"></div>
    <script src="stats.js"></script>
    <script src="common.js"></script>

    <dialog id="endDialog"  class="dialog">
      <form method="dialog" style="font-family:sans-serif;">
        <div class="dialog1">Congratulations! </div>
        <div class="dialog2" style="padding-top: 30px">您已成功將資訊送達終點!</div>
        <div class="dialog2" id="time"></div>
        <div class="dialog2" id="point"></div>
        <div class="dialog2" id="pw"></div>
        <menu>
          <button value="close" class="button button1">Confirm</button>
        </menu>
      </form>
    </dialog>
    <dialog id="startDialog1"  class="dialog">
      <form method="dialog" style="font-family:sans-serif;">
        <div id="s1t" class="dialog1">遊戲說明</div>
        <div id="s1c" class="dialog2" style="padding-top: 30px">這是一個模擬<b>網路封包傳送</b>的賽車遊戲。<br>
            網路資訊的傳遞就像貨物運送流程，而我們裝載貨物的車輛就好比是封包，將資訊切成小片段分批送達目的地。
            在路途中，也可能遇到<b>網路壅塞、防火牆、頻寬改變</b>等等的狀況。
            我們的目標，就是要想辦法用最短的時間將載滿資訊的車輛抵達目的地。
        </div>
        <button value="Confirm" class="button button1 button4" onclick="document.getElementById('startDialog2').showModal()">NEXT</button>
      </form>
    </dialog>
    <dialog id="startDialog2"  class="dialog">
      <form method="dialog" style="font-family:sans-serif;">
        <div id="s1t" class="dialog1">防火牆 Firewall</div>
        <div id="s1c" class="dialog2" style="padding-top: 30px">網路層防火牆可視為一種 IP封包過濾器，
            可以根據防火牆規則來決定要攔截或放行封包。在遊戲中，防火牆就是四種不同顏色的門，只有<b>特定顏色的門</b>才可以讓車輛通過。
            <b>道路旁的廣告看版</b>上也會明確寫出這個防火牆的規則，須特別注意。
        </div>
        <button value="Confirm" class="button button1 button4" onclick="document.getElementById('startDialog3').showModal()">NEXT</button>
        <button value="Confirm" class="button button1 button3" onclick="document.getElementById('startDialog1').showModal()">PREVIOUS</button>
      </form>
    </dialog>
    <dialog id="startDialog3"  class="dialog">
      <form method="dialog" style="font-family:sans-serif;">
        <div id="s1t" class="dialog1">頻寬 Bandwidth</div>
        <div id="s1c" class="dialog2" style="padding-top: 30px">在數位傳輸網路中，
                頻寬代表的是單位時間內傳輸的資料流通量。頻寬越大，便具有越大的資料運送能力。
                在遊戲中，<b>不同車道數量</b>代表不同頻寬大小，而道路上<b>車輛的數目</b>則代表傳輸的資料量。
                頻寬小，車道數少，自然也較容易發生壅塞現象。
        </div>
        <button value="Confirm" class="button button1 button4" onclick="document.getElementById('startDialog4').showModal()">NEXT</button>
        <button value="Confirm" class="button button1 button3" onclick="document.getElementById('startDialog2').showModal()">PREVIOUS</button>
      </form>
    </dialog>
    <dialog id="startDialog4"  class="dialog">
      <form method="dialog" style="font-family:sans-serif;">
        <div id="s1t" class="dialog1">DSCP 分級服務代碼</div>
        <div id="s1c" class="dialog2" style="padding-top: 30px">封包上面通常都有些特殊標記，例如 DSCP代碼就是用來標記封包的優先順序。
                擁有高順位的 DSCP代碼，就像是在遊樂園使用快速通關，不用和其他封包一起排隊等候傳送。如果遇到道路壅塞的時候，效果更加明顯。
                遊戲過程中，<b>右上角不定時會出現DSCP按鈕</b>，按鈕出現的時候只要<b>用游標點選</b>或是<b>按空白鍵</b>就可以暫時擁有高順位的 DSCP代碼，可以直接無視前車、加速通過。
        </div>
        <button value="Confirm" class="button button1 button4" onclick="document.getElementById('startDialog5').showModal()">NEXT</button>
        <button value="Confirm" class="button button1 button3" onclick="document.getElementById('startDialog3').showModal()">PREVIOUS</button>
      </form>
    </dialog>
    <dialog id="startDialog5"  class="dialog">
      <form method="dialog" style="font-family:sans-serif;">
        <div id="s1t" class="dialog1">資料遺失</div>
        <div id="s1c" class="dialog2" style="padding-top: 30px">封包傳送過程中，也可能出現資料毀損、遺失、重複、亂序等狀況，因此每個封包都會有自己的錯誤更正碼，確保接收端收到資訊的正確性。如果發生封包遺失或毀損等情形，接收端便會要求傳送端重新傳送封包。
        遊戲過程中，須注意若是<b>和其他車輛發生碰撞</b>，便有可能發生資料遺失的現象。<b>畫面右下角</b>會註明目前<b>封包保存的完整性</b>，最後抵達終點時的完整性越高，得分也就越高。

        </div>
        <button value="Confirm" class="button button1 button4" onclick="document.getElementById('startDialog6').showModal()">NEXT</button>
        <button value="Confirm" class="button button1 button3" onclick="document.getElementById('startDialog4').showModal()">PREVIOUS</button>
      </form>
    </dialog>
    <dialog id="startDialog6"  class="dialog">
      <form method="dialog" style="font-family:sans-serif;">
        <div class="dialog1" style="font-size: 45px; padding-bottom: 20px;">請選擇車輛的樣式</div>
        <img id="y" src="images/yellow.png" width="150" style="padding-left: 20px;" onclick="chooseColor(0,this.id)">
        <img id="g" src="images/green.png" width="120" onclick="chooseColor(2,this.id)">
        <img id="r" src="images/red.png" width="140" style="padding-left: 8px;" onclick="chooseColor(4,this.id)">
        <img id="b" src="images/blue.png" width="150" onclick="chooseColor(6,this.id)">
        <img id="p" src="images/pink.png" width="135" style="padding-left: 30px;" onclick="chooseColor(1,this.id)">
        <img id="bk" src="images/black.png" width="125" style="padding-left: 7px;" onclick="chooseColor(3,this.id)">
        <img id="w" src="images/white.png" width="150" onclick="chooseColor(5,this.id)">
        <img id="o" src="images/orange.png" width="150" onclick="chooseColor(7,this.id)">
        <script>
            function chooseColor(color,id) {
                window.car_color = color;
                var imgs = document.getElementsByTagName("img");
                for(i of imgs) { i.style.outline = "none"; }
                document.getElementById(id).style.outline = "2px solid #555";
                //console.log(car_color);
            }            
        </script>
        <menu>
          <button value="Confirm" class="button button1 button4" onclick="resetCars()">OK</button>
          <button value="Confirm" class="button button1 button3" onclick="document.getElementById('startDialog5').showModal()">PREVIOUS</button>
        </menu>
      </form>
    </dialog>
    <menu>
        <button id="opener" style="display: none;">open the dialog</button>
        <button id="opener2" style="display: none;">open the dialog</button>
    </menu>
    <script>
        
        const fileUrl = "racecar_password.txt" // provide file location
        var n = <?php echo $n ?>;
        console.log(n);
    </script>
    
    <script>
        Array.prototype.except = function(val) {
            return this.filter(function(x) { return x !== val; });        
        }; 
        document.getElementById('dscp').addEventListener('click', function getdscp() { 
            window.dscp = 1;
            window.last_dscp_position = position;
            document.getElementById('dscp').style.pointerEvents="none";
            document.getElementById('dscp').classList.add("button22");
        });
        document.getElementById('opener').addEventListener('click', function onOpen() {
            document.getElementById('endDialog').showModal()
            document.getElementById('time').innerHTML = "本次所花的時間是"+Math.floor(lastLapTime/60)+"分"+Math.floor(lastLapTime%60)+"秒";
            if (lastLapTime > 210) {
                document.getElementById('point').innerHTML = "您已將資料送達終點！但封包傳送的時間太長了哦 ><";
                document.getElementById('pw').innerHTML = "可以再試一次！加油！按鍵'page up'就可以讓車輛加速~";
            }
            else {
                if(integrity>60){
                  document.getElementById('point').innerHTML = "恭喜您成功將資料送達終點！";
                  document.getElementById('pw').innerHTML = "本關的通關密碼是：1234，<br>把密碼輸入line聊天室中就可以順利破關囉！";
                }
                else{
                  document.getElementById('point').innerHTML = "恭喜您將資料送達終點！但資料的完整性好像太低了喔 ><";
                  document.getElementById('pw').innerHTML = "可以再試一次~ 加油！減少和其他車輛碰撞的次數會大幅提高完整性哦！";
                }
                
            }
            
        });
        document.getElementById('opener2').addEventListener('click', function onOpen() {
            document.getElementById('startDialog1').showModal()
        });
        document.getElementById("opener2").click();
        
        fps = 90;
        segmentLength = 70;               // length of a single segment  
        speedsetup();
        initialsetup();
        function speedsetup() {    

            step = 1 / fps;                    // how long is each frame (in seconds)
            maxSpeed = segmentLength / step;   // top speed (ensure we can't move more than 1 segment in a single frame to make collision detection easier)
            accel = maxSpeed / 5;              // acceleration rate - tuned until it 'felt' right
            breaking = -maxSpeed;              // deceleration rate when braking
            decel = -maxSpeed / 5;             // 'natural' deceleration rate when neither accelerating, nor braking
            offRoadDecel = -maxSpeed / 2;      // off road deceleration is somewhere in between
            offRoadLimit = maxSpeed / 4;       // limit when off road deceleration no longer applies (e.g. you can always go at least this speed even when off road)
        }
        function choose(choices) {
            var index = Math.floor(Math.random() * choices.length);
            return choices[index];
        }
        function shuffle(array) {
              for (let i = array.length - 1; i > 0; i--) {
                let j = Math.floor(Math.random() * (i + 1)); // random index from 0 to i
                [array[i], array[j]] = [array[j], array[i]];
              }
            }
        function initialsetup() {

            window.width = 1024;                      // logical canvas width
            window.height = 768;                      // logical canvas height
            window.centrifugal = 0.4;                 // centrifugal force multiplier when going around curves
            window.offRoadDecel = 0.99;               // speed multiplier when off road (e.g. you lose 2% speed each update frame)
            window.hillSpeed = 0.002;                 // background hill layer scroll speed when going around curve (or up hill)
            window.hillOffset = 0;                    // current hill scroll offset
            window.segments = [];                     // array of road segments
            window.cars = [];                         // array of cars on the road
            window.red_firewalls = null;
            window.blue_firewalls = null;
            window.yellow_firewalls = null;
            window.green_firewalls = null;
            window.boo = null;
            window.car_color = 0;
            window.firewall_color = choose([0,1,2,3]);
            window.canvas = Dom.get('canvas');        // our canvas...
            window.ctx = canvas.getContext('2d');     // ...and its drawing context
            window.background = null;                 // our background image (loaded below)
            window.sprites = null;                    // our spritesheet (loaded below)
            window.roadWidth = 2000;                  // actually half the roads width, easier math if the road spans from -roadWidth to +roadWidth
           
            window.rumbleLength = 4;                  // number of segments per red/white rumble strip
            window.trackLength = null;                // z length of entire track (computed)
            window.lanes = 4;                         // number of lanes
            window.fieldOfView = 100;                 // angle (degrees) for field of view
            window.cameraHeight = 1000;               // z height of camera
            window.cameraDepth = null;                // z distance camera is from screen (computed)
            window.drawDistance = 250;                // number of segments to draw
            window.playerX = 0;                       // player x offset from center of road (-1 to 1 to stay independent of roadWidth)
            window.playerZ = null;                    // player relative z distance from camera (computed)
            //window.fogDensity = 5;                    // exponential fog density
            window.position = 0;                      // current camera Z position (add playerZ to get player's absolute Z position)
            window.speed = 0;                         // current speed
            window.totalCars = 200;                   // total number of cars on the road
            window.currentLapTime = 0;                // current lap time
            window.lastLapTime = null;                // last lap time
            window.end = 0;
            window.keyLeft = false;
            window.keyRight = false;
            window.keyFaster = false;
            window.keySlower = false;
            window.keyDSCP = false;
            window.dscp = 0;
            window.last_dscp_position = 80000;
            window.integrity=105;

            window.hud = {
                current_lap_time: { value: null, dom: Dom.get('current_lap_time_value') },
                last_lap_time: { value: null, dom: Dom.get('last_lap_time_value') },
                fast_lap_time: { value: null, dom: Dom.get('fast_lap_time_value') }
            }
        }

        var seq = [1,2,2,3];
        shuffle(seq);
        seq.push(4);

        //=========================================================================
        // UPDATE THE GAME WORLD
        //=========================================================================

        function update(dt) {

            var n, car, carW, sprite, spriteW;
            var playerSegment = findSegment(position + playerZ);
            var playerW = SPRITES.REDSPORT.w * SPRITES.PLAYER_SCALE;
            var speedPercent = speed / maxSpeed;
            var dx = dt * 2 * speedPercent; // at top speed, should be able to cross from left to right (-1 to 1) in 1 second
            var startPosition = position;

            updateCars(dt, playerSegment, playerW);

            position = Util.increase(position, dt * speed, trackLength);

            if (keyLeft)
                playerX = playerX - dx;
            else if (keyRight)
                playerX = playerX + dx;

            playerX = playerX - (dx * speedPercent * playerSegment.curve * centrifugal);

            if (keyFaster)
                speed = Util.accelerate(speed, accel, dt);
            else if (keySlower)
                speed = Util.accelerate(speed, breaking, dt);
            else
                speed = Util.accelerate(speed, decel, dt);


            if ((playerX < -1) || (playerX > 1)) {

                if (speed > offRoadLimit)
                    speed = Util.accelerate(speed, offRoadDecel, dt);

                for (n = 0; n < playerSegment.sprites.length; n++) {
                    sprite = playerSegment.sprites[n];
                    spriteW = sprite.source.w * SPRITES.SCALE;
                    if (Util.overlap(playerX, playerW, sprite.offset + spriteW / 2 * (sprite.offset > 0 ? 1 : -1), spriteW)) {
                        speed = maxSpeed / 5;
                        position = Util.increase(playerSegment.p1.world.z, -playerZ, trackLength); // stop in front of sprite (at front of segment)
                        intaaa = choose(inta);
                        if(intaaa==0){
                          intaaa = choose([...Array(5).keys()]);
                          if(intaaa==0){integrity-=0.05;console.log(intaaa);}
                        }
                        break;
                    }
                }
            }

            inta = [...Array(40).keys()];
            function normal_bounce_back() {
                for (n = 0; n < playerSegment.cars.length; n++) {
                    car = playerSegment.cars[n];
                    carW = car.sprite.w * SPRITES.PLAYER_SCALE;
                    if (speed > car.speed) {
                        if (Util.overlap(playerX, playerW, car.offset, carW, 0.8)) {
                            speed = car.speed * (car.speed / speed);
                            position = Util.increase(car.z, -playerZ, trackLength); //bounce back when hit the car
                            intaaa = choose(inta);
                            if (car_color==2 && intaaa==0){integrity-=0.15;console.log(intaaa);}
                            else if (((car_color==0)||(car_color==7))&&(intaaa==0)) {integrity-=0.05;console.log(intaaa);}
                            else if(intaaa==0){integrity-=0.1;console.log(intaaa);}
                            break;
                        }
                    }
                }
            }

            //console.log(integrity);

            if (!dscp) {normal_bounce_back(); }
            
            if(firewall_color != 0) {set_red(boo[0]);}
            if(firewall_color != 1) {set_green(boo[1]);}
            if(firewall_color != 2) {set_blue(boo[2]);}
            if(firewall_color != 3) {set_yellow(boo[3]);}



            function set_blue(boo) {
                for (n = 0; n < playerSegment.blue_firewalls.length; n++) {
                    firewall = playerSegment.blue_firewalls[n];
                    firewallW = firewall.source.w * SPRITES.SCALE;
                    if (Util.overlap(playerX, playerW, firewall.offset + boo * firewallW / 2, firewallW, 0.9)) {
                        speed = maxSpeed / 3;
                        position = Util.increase(playerSegment.p1.world.z, -playerZ, trackLength);
                        break;
                    }
                }
            }
            
            function set_green(boo) {
                for (n = 0; n < playerSegment.green_firewalls.length; n++) {
                    firewall = playerSegment.green_firewalls[n];
                    firewallW = firewall.source.w * SPRITES.SCALE;
                    if (Util.overlap(playerX, playerW, firewall.offset + boo * firewallW / 2, firewallW, 0.9)) {
                        speed = maxSpeed / 3;
                        position = Util.increase(playerSegment.p1.world.z, -playerZ, trackLength);
                        break;
                    }
                }
            }
            
            function set_yellow(boo) {
                for (n = 0; n < playerSegment.yellow_firewalls.length; n++) {
                    firewall = playerSegment.yellow_firewalls[n];
                    firewallW = firewall.source.w * SPRITES.SCALE;
                    if (Util.overlap(playerX, playerW, firewall.offset + boo * firewallW / 2, firewallW, 0.9)) {
                        speed = maxSpeed / 3;
                        position = Util.increase(playerSegment.p1.world.z, -playerZ, trackLength);
                        break;
                    }
                }
            }

            function set_red(boo) {
                for (n = 0; n < playerSegment.red_firewalls.length; n++) {
                    firewall = playerSegment.red_firewalls[n];
                    firewallW = firewall.source.w * SPRITES.SCALE;
                    if (Util.overlap(playerX, playerW, firewall.offset + boo * firewallW / 2, firewallW, 0.9)) {
                        speed = maxSpeed / 3;
                        position = Util.increase(playerSegment.p1.world.z, -playerZ, trackLength);
                        break;
                    }
                }
            }

            playerX = Util.limit(playerX, -1.2, 1.2);     // dont ever let it go too far out of bounds
            speed = Util.limit(speed, 0, maxSpeed); // or exceed maxSpeed

            //skyOffset = Util.increase(skyOffset, skySpeed * playerSegment.curve * (position - startPosition) / segmentLength, 1);
            hillOffset = Util.increase(hillOffset, hillSpeed * playerSegment.curve * (position - startPosition) / segmentLength, 1);
            //treeOffset = Util.increase(treeOffset, treeSpeed * playerSegment.curve * (position - startPosition) / segmentLength, 1);

            
            if(integrity>100){document.getElementById("integrity").innerHTML = "100%";}
            else{document.getElementById("integrity").innerHTML = parseFloat(integrity.toFixed(1))+"%";}

            //console.log("postion: "+position);
            if(position < 5000){
                document.getElementById("info_title").innerHTML = "防火牆 Firewall";
                document.getElementById("info_content").innerHTML = "網路層防火牆可視為一種 IP\ 封包過濾器，只允許符合特定規則的封包通過，其餘的一概禁止穿越防火牆。<br><br>\
                    &#10148遊戲說明：只有符合特定顏色的車子可以通過對應顏色的防火牆。";
                document.getElementById("info").style.backgroundColor = "#FADBD8" ;
                document.getElementById("bandwidth").innerHTML = "Bandwidth: 100Mbps";
                window.fps = 70;
                speedsetup();
                window.roadWidth = 2000;
                window.lanes = 4; 
                window.rumbleLength = 4;
                window.cameraHeight = 1000;
                window.playerZ = 850;
                SPRITES.PLAYER_SCALE = 0.35 * (1/SPRITES.REDSPORT.w);
                SPRITES.SCALE = 0.33 * (1/80);
            }

            if(position > 10000+20000*seq.indexOf(3) && position < 30000+20000*seq.indexOf(3)){
                document.getElementById("info_title").innerHTML = "頻寬 Bandwidth";
                document.getElementById("info_content").innerHTML = "在數位傳輸網路中，\
                頻寬代表的是單位時間內傳輸的資料流通量。有較寬的頻寬便具有較大的資料運送能力。<br><br>\
                &#10148遊戲說明：車道的數量代表頻寬，車輛的數目代表傳輸的資料量。";
                document.getElementById("info").style.backgroundColor = "#FCF3CF" ;
                document.getElementById("bandwidth").innerHTML = "Bandwidth:&nbsp;&nbsp;60Mbps";
                window.fps = 55;
                speedsetup();
                window.roadWidth = 650;
                window.lanes = 3; 
                window.rumbleLength = 3;
                window.cameraHeight = 560;
                window.playerZ = 480;
                SPRITES.PLAYER_SCALE = 0.6 * (1/SPRITES.REDSPORT.w);
                SPRITES.SCALE = 0.6 * (1/80);
            }
            if((position > 10000+20000*seq.indexOf(2) && position < 30000+20000*seq.indexOf(2))||(position > 10000+20000*seq.lastIndexOf(2) && position < 30000+20000*seq.lastIndexOf(2))) {         
                document.getElementById("info_title").innerHTML = "DSCP 分級服務代碼";
                document.getElementById("info_content").innerHTML = "DSCP負責標記封包的優先順序。\
                擁有高順位的DSCP代碼相當於在遊樂園使用快速通關，\
                不用和其他封包一起排隊等候。<br><br>\
                &#10148遊戲說明：畫面右上角出現DSCP按鈕時，用滑鼠點擊或是按空白鍵，即可無視前車、加速通過。";
                document.getElementById("info").style.backgroundColor = "#D4EFDF";
                document.getElementById("bandwidth").innerHTML = "Bandwidth:&nbsp;&nbsp;12Mbps";
                window.fps = dscp ? 60 :35;
                speedsetup();
                window.roadWidth = 500;
                window.lanes = 2; 
                window.rumbleLength = 3;
                window.cameraHeight = 555;
                window.playerZ = 490;
                SPRITES.PLAYER_SCALE = 0.75 * (1/SPRITES.REDSPORT.w);
                SPRITES.SCALE = 0.75 * (1/80);
            }
            if(position > 10000+20000*seq.indexOf(1) && position < 30000+20000*seq.indexOf(1)){
                document.getElementById("bandwidth").innerHTML = "Bandwidth:&nbsp;&nbsp;&nbsp;&nbsp;6Mbps";
                window.fps = dscp ? 40 : 20;
                speedsetup();
                window.roadWidth = 250;
                window.lanes = 1; 
                window.rumbleLength = 2;
                window.cameraHeight = 560;
                window.playerZ = 500;
                SPRITES.PLAYER_SCALE = 1.5 * (1/SPRITES.REDSPORT.w);
                SPRITES.SCALE = 1.5 * (1/80);
            }
            if(position > 90000){
                document.getElementById("info_title").innerHTML = "封包 Packet";
                document.getElementById("info_content").innerHTML = "網路資訊的傳遞就像貨物運送流程，\
                而封包就是裝載貨物的車輛，將資訊分批送達目的地。<br><br>\
                &#10148遊戲說明：車輛抵達終點所花的時間越少，得分越多。";
                document.getElementById("info").style.backgroundColor = "#D6EAF8" ;
                document.getElementById("bandwidth").innerHTML = "Bandwidth: 100Mbps";
                window.fps = 90;
                speedsetup();
                window.roadWidth = 2000;
                window.lanes = 4; 
                window.rumbleLength = 4;
                window.cameraHeight = 1000;
                window.playerZ = 860;
                SPRITES.PLAYER_SCALE = 0.35 * (1/SPRITES.REDSPORT.w);
                SPRITES.SCALE = 0.33 * (1/80);
            }

            dscpp = (window.lanes==1)? choose([...Array(450).keys()]) : ((window.lanes==4)? choose([...Array(800).keys()]):choose([...Array(650).keys()]));
            if (dscpp == 0) {
                document.getElementById("dscp").style.display = "block";
                window.last_dscp_position = position;
            }
            if((document.getElementById("dscp").style.display=="block")&&(keyDSCP)&&(document.getElementById('dscp').style.pointerEvents!="none")) {
              document.getElementById("dscp").click();
            }
            
            if(window.lanes==4) {
                if ((position - last_dscp_position > 13000)) {
                    window.dscp = 0;
                    document.getElementById('dscp').style.pointerEvents="auto";
                    document.getElementById('dscp').classList.remove("button22");
                    document.getElementById('dscp').style.display = "none";

                }
            }
            else {
                if ((position - last_dscp_position > 7000)) {
                    window.dscp = 0;
                    document.getElementById('dscp').style.pointerEvents="auto";
                    document.getElementById('dscp').classList.remove("button22");
                    document.getElementById('dscp').style.display = "none";

                }
            }
            //console.log("color"+firewall_color+"     "+position);
            if ((trackLength - position > 130000) && (trackLength - position < 130200)) {
                window.firewall_color = choose([0,1,2,3]);
                segments[2175].sprites=[];
                segments[2175].red_firewalls=[];
                segments[2175].green_firewalls=[];
                segments[2175].blue_firewalls=[];
                segments[2175].yellow_firewalls=[];
                resetFirewalls(2175);
                addSprite(2175, SPRITES.FIREWALLS[firewall_color], -1);
            }

            if ((trackLength - position > 15000) && (trackLength - position < 15200)) {
                window.firewall_color = choose([0,1,2,3]);
                segments[60].sprites=[];
                segments[60].red_firewalls=[];
                segments[60].green_firewalls=[];
                segments[60].blue_firewalls=[];
                segments[60].yellow_firewalls=[];
                resetFirewalls(60);
                addSprite(60, SPRITES.FIREWALLS[firewall_color], -1);
            }
            if (position > playerZ) {
                if (currentLapTime && (startPosition < playerZ)) {
                    lastLapTime = currentLapTime;
                    //console.log(lastLapTime)
                    document.getElementById("opener").click();
                    //console.log(firewall_color);
                    currentLapTime = 0;
                    if (lastLapTime <= Util.toFloat(Dom.storage.fast_lap_time)) {
                        Dom.storage.fast_lap_time = lastLapTime;
                        updateHud('fast_lap_time', formatTime(lastLapTime));
                        Dom.addClassName('fast_lap_time', 'fastest');
                        Dom.addClassName('last_lap_time', 'fastest');
                    }
                    else {
                        Dom.removeClassName('fast_lap_time', 'fastest');
                        Dom.removeClassName('last_lap_time', 'fastest');
                    }
                    updateHud('last_lap_time', formatTime(lastLapTime));
                    Dom.show('last_lap_time');
                }
                else {
                    currentLapTime += dt;
                }
            }

            updateHud('current_lap_time', formatTime(currentLapTime));
        }

        //-------------------------------------------------------------------------

        function updateCars(dt, playerSegment, playerW) {
            var n, car, oldSegment, newSegment;
            for (n = 0; n < cars.length; n++) {
                car = cars[n];
                oldSegment = findSegment(car.z);
                car.offset = car.offset + updateCarOffset(car, oldSegment, playerSegment, playerW);
                car.z = Util.increase(car.z, dt * car.speed, trackLength);
                car.percent = Util.percentRemaining(car.z, segmentLength); // useful for interpolation during rendering phase
                car.speed = Util.limit(car.speed, 0, maxSpeed);
                newSegment = findSegment(car.z);
                if (oldSegment != newSegment) {
                    index = oldSegment.cars.indexOf(car);
                    oldSegment.cars.splice(index, 1);
                    newSegment.cars.push(car);
                }
            }
        }

        function updateCarOffset(car, carSegment, playerSegment, playerW) {

            var i, j, dir, segment, otherCar, otherCarW, lookahead = 50, carW = car.sprite.w * SPRITES.PLAYER_SCALE;

            // optimization, dont bother steering around other cars when 'out of sight' of the player
            if ((carSegment.index - playerSegment.index) > drawDistance)
                return 0;

            for (i = 1; i < lookahead; i++) {
                segment = segments[(carSegment.index + i) % segments.length];

                if ((segment === playerSegment) && (car.speed > speed) && (Util.overlap(playerX, playerW, car.offset, carW, 0.8))) {
                    if (playerX > 0.5)
                        dir = -1;
                    else if (playerX < -0.5)
                        dir = 1;
                    else
                        dir = (car.offset > playerX) ? 1 : -1;
                    return dir * 1 / i * (car.speed - speed) / maxSpeed; // the closer the cars (smaller i) and the greated the speed ratio, the larger the offset
                }

                for (j = 0; j < segment.cars.length; j++) {
                    otherCar = segment.cars[j];
                    otherCarW = otherCar.sprite.w * SPRITES.PLAYER_SCALE;
                    if ((car.speed > otherCar.speed) && Util.overlap(car.offset, carW, otherCar.offset, otherCarW, 0.7)) {
                        if (otherCar.offset > 0.5)
                            dir = -1;
                        else if (otherCar.offset < -0.5)
                            dir = 1;
                        else
                            dir = (car.offset > otherCar.offset) ? 1 : -1;
                        return dir * 1 / i * (car.speed - otherCar.speed) / maxSpeed;
                    }
                }
            }

            // if no cars ahead, but I have somehow ended up off road, then steer back on
            if (car.offset < -0.9)
                return 0.1;
            else if (car.offset > 0.9)
                return -0.1;
            else
                return 0;
        }

        //-------------------------------------------------------------------------

        function updateHud(key, value) { // accessing DOM can be slow, so only do it if value has changed
            if (hud[key].value !== value) {
                hud[key].value = value;
                Dom.set(hud[key].dom, value);
            }
        }

        function formatTime(dt) {
            var minutes = Math.floor(dt / 60);
            var seconds = Math.floor(dt - (minutes * 60));
            var tenths = Math.floor(10 * (dt - Math.floor(dt)));
            if (minutes > 0)
                return minutes + "." + (seconds < 10 ? "0" : "") + seconds + "." + tenths;
            else
                return seconds + "." + tenths;
        }

        //=========================================================================
        // RENDER THE GAME WORLD
        //=========================================================================

        function render() {

            var baseSegment = findSegment(position);
            var basePercent = Util.percentRemaining(position, segmentLength);
            var playerSegment = findSegment(position + playerZ);
            var playerPercent = Util.percentRemaining(position + playerZ, segmentLength);
            var playerY = Util.interpolate(playerSegment.p1.world.y, playerSegment.p2.world.y, playerPercent);
            var maxy = height;

            var x = 0;
            var dx = - (baseSegment.curve * basePercent);

            ctx.clearRect(0, 0, width, height);

            Render.background(ctx, background, width, height, BACKGROUND.HILLS);
            
            var n, i, segment, car, sprite, spriteScale, spriteX, spriteY;

            for (n = 0; n < drawDistance; n++) {

                segment = segments[(baseSegment.index + n) % segments.length];
                segment.looped = segment.index < baseSegment.index;
                //.fog = Util.exponentialFog(n / drawDistance, fogDensity);
                segment.clip = maxy;

                Util.project(segment.p1, (playerX * roadWidth) - x, playerY + cameraHeight, position - (segment.looped ? trackLength : 0), cameraDepth, width, height, roadWidth);
                Util.project(segment.p2, (playerX * roadWidth) - x - dx, playerY + cameraHeight, position - (segment.looped ? trackLength : 0), cameraDepth, width, height, roadWidth);

                x = x + dx;
                dx = dx + segment.curve;

                if ((segment.p1.camera.z <= cameraDepth) ||         // behind us
                    (segment.p2.screen.y >= segment.p1.screen.y) || // back face cull
                    (segment.p2.screen.y >= maxy))                  // clip by (already rendered) hill
                    continue;

                Render.segment(ctx, width, lanes,
                    segment.p1.screen.x,
                    segment.p1.screen.y,
                    segment.p1.screen.w,
                    segment.p2.screen.x,
                    segment.p2.screen.y,
                    segment.p2.screen.w,
                    segment.color);

                maxy = segment.p1.screen.y;
            }

            for (n = (drawDistance - 1); n > 0; n--) {
                segment = segments[(baseSegment.index + n) % segments.length];

                for (i = 0; i < segment.cars.length; i++) {
                    car = segment.cars[i];
                    sprite = car.sprite;
                    spriteScale = Util.interpolate(segment.p1.screen.scale, segment.p2.screen.scale, car.percent);
                    spriteX = Util.interpolate(segment.p1.screen.x, segment.p2.screen.x, car.percent) + (spriteScale * car.offset * roadWidth * width / 2);
                    spriteY = Util.interpolate(segment.p1.screen.y, segment.p2.screen.y, car.percent);
                    Render.cars(ctx, width, height, roadWidth, sprites, car.sprite, spriteScale, spriteX, spriteY, -0.5, -1, segment.clip);
                }

                for (i = 0; i < segment.sprites.length; i++) {
                    sprite = segment.sprites[i];
                    spriteScale = segment.p1.screen.scale;
                    spriteX = segment.p1.screen.x + (spriteScale * sprite.offset * roadWidth * width / 2);
                    spriteY = segment.p1.screen.y;
                    Render.sprite(ctx, width, height, roadWidth, sprites, sprite.source, spriteScale, spriteX, spriteY, (sprite.offset < 0 ? -1 : 0), -1, segment.clip);
                }

                for (i = 0; i < segment.red_firewalls.length; i++) {
                    sprite = segment.red_firewalls[i];
                    spriteScale = segment.p1.screen.scale;
                    spriteX = segment.p1.screen.x + (spriteScale * sprite.offset * roadWidth * width / 2);
                    spriteY = segment.p1.screen.y;
                    Render.sprite(ctx, width, height, roadWidth, sprites, sprite.source, spriteScale, spriteX, spriteY, (sprite.offset < 0 ? -1 : 0), -1, segment.clip);
                }
                for (i = 0; i < segment.blue_firewalls.length; i++) {
                    sprite = segment.blue_firewalls[i];
                    spriteScale = segment.p1.screen.scale;
                    spriteX = segment.p1.screen.x + (spriteScale * sprite.offset * roadWidth * width / 2);
                    spriteY = segment.p1.screen.y;
                    Render.sprite(ctx, width, height, roadWidth, sprites, sprite.source, spriteScale, spriteX, spriteY, (sprite.offset < 0 ? -1 : 0), -1, segment.clip);
                }
                for (i = 0; i < segment.green_firewalls.length; i++) {
                    sprite = segment.green_firewalls[i];
                    spriteScale = segment.p1.screen.scale;
                    spriteX = segment.p1.screen.x + (spriteScale * sprite.offset * roadWidth * width / 2);
                    spriteY = segment.p1.screen.y;
                    Render.sprite(ctx, width, height, roadWidth, sprites, sprite.source, spriteScale, spriteX, spriteY, (sprite.offset < 0 ? -1 : 0), -1, segment.clip);
                }
                for (i = 0; i < segment.yellow_firewalls.length; i++) {
                    sprite = segment.yellow_firewalls[i];
                    spriteScale = segment.p1.screen.scale;
                    spriteX = segment.p1.screen.x + (spriteScale * sprite.offset * roadWidth * width / 2);
                    spriteY = segment.p1.screen.y;
                    Render.sprite(ctx, width, height, roadWidth, sprites, sprite.source, spriteScale, spriteX, spriteY, (sprite.offset < 0 ? -1 : 0), -1, segment.clip);
                }

                if (segment == playerSegment) {
                    Render.player(ctx, width, height, roadWidth, sprites, speed / maxSpeed,
                        cameraDepth / playerZ,
                        width / 2,
                        (height / 2) - (cameraDepth / playerZ * Util.interpolate(playerSegment.p1.camera.y, playerSegment.p2.camera.y, playerPercent) * height / 2),
                        dscp);
                }
            }
        }

        function findSegment(z) {
            return segments[Math.floor(z / segmentLength) % segments.length];
        }

        //=========================================================================
        // BUILD ROAD GEOMETRY
        //=========================================================================

        function lastY() { return (segments.length == 0) ? 0 : segments[segments.length - 1].p2.world.y; }

        function addSegment(curve, y) {
            var n = segments.length;
            segments.push({
                index: n,
                p1: { world: { y: lastY(), z: n * segmentLength }, camera: {}, screen: {} },
                p2: { world: { y: y, z: (n + 1) * segmentLength }, camera: {}, screen: {} },
                curve: curve,
                sprites: [],
                cars: [],
                red_firewalls: [],
                blue_firewalls: [],
                green_firewalls: [],
                yellow_firewalls: [],
                color: Math.floor(n / rumbleLength) % 2 ? COLORS.DARK : COLORS.LIGHT
            });
        }

        function addSprite(n, sprite, offset) {
            segments[n].sprites.push({ source: sprite, offset: offset });
        }

        function addRedFirewall(n, sprite, offset) {
            segments[n].red_firewalls.push({ source: sprite, offset: offset });  
        }
        function addBlueFirewall(n, sprite, offset) {
            segments[n].blue_firewalls.push({ source: sprite, offset: offset });
        }
        function addGreenFirewall(n, sprite, offset) {
            segments[n].green_firewalls.push({ source: sprite, offset: offset });
        }
        function addYellowFirewall(n, sprite, offset) {
            segments[n].yellow_firewalls.push({ source: sprite, offset: offset });
        }

        function addRoad(enter, hold, leave, curve, y) {
            var startY = lastY();
            var endY = startY + (Util.toInt(y, 0) * segmentLength);
            var n, total = enter + hold + leave;
            for (n = 0; n < enter; n++)
                addSegment(Util.easeIn(0, curve, n / enter), Util.easeInOut(startY, endY, n / total));
            for (n = 0; n < hold; n++)
                addSegment(curve, Util.easeInOut(startY, endY, (enter + n) / total));
            for (n = 0; n < leave; n++)
                addSegment(Util.easeInOut(curve, 0, n / leave), Util.easeInOut(startY, endY, (enter + hold + n) / total));
        }

        var ROAD = {
            LENGTH: { NONE: 0, SHORT: 20, MEDIUM: 45, LONG: 80 },
            HILL:  { NONE: 0, LOW: 20, MEDIUM: 40, HIGH: 60 },
            CURVE: { NONE: 0, EASY: 2, MEDIUM: 3, HARD: 4 }
        };

        function addStraight(num) {
            num = num || ROAD.LENGTH.SHORT;
            addRoad(num, num, num, 0, 0);
        }

        function addHill(num, height) {
            num = num || ROAD.LENGTH.MEDIUM;
            height = height || ROAD.HILL.MEDIUM;
            addRoad(num, num, num, 0, height);
        }

        function addCurve(num, curve, height) {
            num = num || ROAD.LENGTH.MEDIUM;
            curve = curve || ROAD.CURVE.MEDIUM;
            height = height || ROAD.HILL.NONE;
            addRoad(num, num, num, curve, height);
        }

        function addLowRollingHills(num, height) {
            num = num || ROAD.LENGTH.SHORT;
            height = height || ROAD.HILL.LOW;
            addRoad(num, num, num, 0, height / 2);
            addRoad(num, num, num, 0, -height);
            addRoad(num, num, num, ROAD.CURVE.EASY, height);
            addRoad(num, num, num, 0, 0);
            addRoad(num, num, num, -ROAD.CURVE.EASY, height / 2);
            addRoad(num, num, num, 0, 0);
        }

        function addSCurves() {
            addRoad(ROAD.LENGTH.MEDIUM, ROAD.LENGTH.MEDIUM, ROAD.LENGTH.MEDIUM, -ROAD.CURVE.EASY, ROAD.HILL.NONE);
            addRoad(ROAD.LENGTH.MEDIUM, ROAD.LENGTH.MEDIUM, ROAD.LENGTH.MEDIUM, ROAD.CURVE.MEDIUM, ROAD.HILL.MEDIUM);
            addRoad(ROAD.LENGTH.MEDIUM, ROAD.LENGTH.MEDIUM, ROAD.LENGTH.MEDIUM, ROAD.CURVE.EASY, -ROAD.HILL.LOW);
            addRoad(ROAD.LENGTH.MEDIUM, ROAD.LENGTH.MEDIUM, ROAD.LENGTH.MEDIUM, -ROAD.CURVE.EASY, ROAD.HILL.MEDIUM);
            addRoad(ROAD.LENGTH.MEDIUM, ROAD.LENGTH.MEDIUM, ROAD.LENGTH.MEDIUM, -ROAD.CURVE.MEDIUM, -ROAD.HILL.MEDIUM);
        }

        function addBumps() {
            addRoad(10, 10, 10, 0, 5);
            addRoad(10, 10, 10, 0, -2);
            addRoad(10, 10, 10, 0, -5);
            addRoad(10, 10, 10, 0, 8);
            addRoad(10, 10, 10, 0, 5);
            addRoad(10, 10, 10, 0, -7);
            addRoad(10, 10, 10, 0, 5);
            addRoad(10, 10, 10, 0, -2);
        }

        function addDownhillToEnd(num) {
            num = num || 200;
            addRoad(num, num, num, -ROAD.CURVE.EASY, -lastY() / segmentLength);
        }

        function resetRoad() {
            segments = [];

            addStraight(ROAD.LENGTH.SHORT);
            addLowRollingHills();
            addCurve(ROAD.LENGTH.LONG, -ROAD.CURVE.MEDIUM, ROAD.HILL.NONE);

            seqq = [1,2,3,4,5,6];
            shuffle(seqq);

            for(s of seqq) {
                if (s==1) {addBumps();}
                else if (s==2) {addHill(ROAD.LENGTH.LONG, ROAD.HILL.HIGH);}
                else if (s==3) {addLowRollingHills();}
                else if (s==4) {addStraight();}
                else if (s==5) {addSCurves();}
                else if (s==6) {addBumps();}
            }

            addStraight();
            addCurve(ROAD.LENGTH.MEDIUM, ROAD.CURVE.MEDIUM, ROAD.HILL.LOW);
            addDownhillToEnd();

            resetSprites();
            //resetCars();
            resetFirewalls(60);

            segments[findSegment(playerZ).index + 2].color = COLORS.START;
            segments[findSegment(playerZ).index + 3].color = COLORS.START;
            for (var n = 0; n < rumbleLength; n++)
                segments[segments.length - 1 - n].color = COLORS.FINISH;

            trackLength = segments.length * segmentLength;
        }

        function resetSprites() {
            var n, i;
          
            addSprite(60, SPRITES.FIREWALLS[firewall_color], -1);
            addSprite(100, SPRITES.BILLBOARD03, -1);
            addSprite(140, SPRITES.BILLBOARD05, -1);
            addSprite(200, SPRITES.BILLBOARD01, -1);
            addSprite(240, SPRITES.BILLBOARD07, -1.2);
            addSprite(240, SPRITES.BILLBOARD08, 1.2);
            addSprite(segments.length - 50, SPRITES.BILLBOARD06, -1.2);
            addSprite(segments.length - 50, SPRITES.BILLBOARD09, 1.2);

            for (n = 10; n < 200; n += 8 + Math.floor(n / 100)) {
                addSprite(n, SPRITES.PALM_TREE, 0.5 + Math.random() * 0.5);
                addSprite(n, SPRITES.PALM_TREE, 1 + Math.random() * 2);
            }

            for (n = 250; n < 1000; n += 10) {
                addSprite(n, SPRITES.COLUMN, 1.1);
                addSprite(n + Util.randomInt(0, 5), SPRITES.TREE1, -1 - (Math.random() * 2));
                addSprite(n + Util.randomInt(0, 5), SPRITES.TREE2, -1 - (Math.random() * 2));
            }


            var side, sprite, offset;
            for(n = 1000 ; n < (segments.length-100) ; n += 100) {
                side      = Util.randomChoice([1, -1]);
                addSprite(n + Util.randomInt(0, 50), Util.randomChoice(SPRITES.BILLBOARDS), -side);
                for(i = 0 ; i < 20 ; i++) {
                  sprite = Util.randomChoice(SPRITES.PLANTS);
                  offset = side * (1.5 + Math.random());
                  addSprite(n + Util.randomInt(0, 50), sprite, offset);
                }
                  
            }

            
        }

        function resetCars() {
            cars = [];
            var n, car, segment, offset, z, sprite, speed;
            for (var n = 0; n < totalCars; n++) {
                offset = Math.random() * Util.randomChoice([-0.8, 0.8]);
                z = Math.floor(Math.random() * segments.length) * segmentLength;
                sprite = Util.randomChoice(SPRITES.CARS.except(SPRITES.CARS[car_color]));
                speed = maxSpeed / 4 + Math.random() * maxSpeed / (sprite == SPRITES.YELLOWTRUCK ? 4 : 2);
                car = { offset: offset, z: z, sprite: sprite, speed: speed };
                segment = findSegment(car.z);
                segment.cars.push(car);
                cars.push(car);
            }
        }
        
        function resetFirewalls(n) {
            red_firewalls = [];
            blue_firewalls = [];
            green_firewalls = [];
            yellow_firewalls = [];


            firewall = [-0.5,-0.001,0,0.5];
            shuffle(firewall);

            boo = [];
            for (f of firewall) {
                if (f >= 0) { boo.push(1); } 
                else       { boo.push(-1); }
            }

            addRedFirewall(n, SPRITES.RED_DOOR, firewall[0]);
            addGreenFirewall(n, SPRITES.GREEN_DOOR, firewall[1]);
            addBlueFirewall(n, SPRITES.BLUE_DOOR, firewall[2]);
            addYellowFirewall(n, SPRITES.YELLOW_DOOR, firewall[3]);
            
        } 

        //=========================================================================
        // THE GAME LOOP
        //=========================================================================
        gamerun();
        function gamerun() {
            Game.run({
                canvas: canvas, render: render, update: update, step: step, end: end,
                images: ["background", "sprites"],
                keys: [
                    { keys: [KEY.LEFT, KEY.A], mode: 'down', action: function () { keyLeft = true; } },
                    { keys: [KEY.RIGHT, KEY.D], mode: 'down', action: function () { keyRight = true; } },
                    { keys: [KEY.UP, KEY.W], mode: 'down', action: function () { keyFaster = true; } },
                    { keys: [KEY.DOWN, KEY.S], mode: 'down', action: function () { keySlower = true; } },
                    { keys: [KEY.LEFT, KEY.A], mode: 'up', action: function () { keyLeft = false; } },
                    { keys: [KEY.RIGHT, KEY.D], mode: 'up', action: function () { keyRight = false; } },
                    { keys: [KEY.UP, KEY.W], mode: 'up', action: function () { keyFaster = false; } },
                    { keys: [KEY.DOWN, KEY.S], mode: 'up', action: function () { keySlower = false; } },
                    { keys: [KEY.SPACE], mode: 'up', action: function () { keyDSCP = false; } },
                    { keys: [KEY.SPACE], mode: 'down', action: function () { keyDSCP = true; } }
                ],
                ready: function (images) {
                    background = images[0];
                    sprites = images[1];
                    reset();
                    Dom.storage.fast_lap_time = Dom.storage.fast_lap_time || 180;
                    updateHud('fast_lap_time', formatTime(Util.toFloat(Dom.storage.fast_lap_time)));
                }
            });
        }

        function reset(options) {
            options = options || {};
            canvas.width = width = Util.toInt(options.width, width);
            canvas.height = height = Util.toInt(options.height, height);
            segmentLength = Util.toInt(options.segmentLength, segmentLength);
            rumbleLength = Util.toInt(options.rumbleLength, rumbleLength);
            cameraDepth = 1 / Math.tan((fieldOfView / 2) * Math.PI / 180);
            playerZ = (cameraHeight * cameraDepth);
            lanes = Util.toInt(options.lanes, lanes);
            roadWidth = Util.toInt(options.roadWidth, roadWidth);
            cameraHeight = Util.toInt(options.cameraHeight, cameraHeight);
            drawDistance = Util.toInt(options.drawDistance, drawDistance);
            fieldOfView = Util.toInt(options.fieldOfView, fieldOfView);

            if ((segments.length == 0) || (options.segmentLength) || (options.rumbleLength))
                resetRoad(); // only rebuild road when necessary
        }
    </script>
</body> 
</html>
