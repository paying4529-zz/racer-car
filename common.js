//=========================================================================
// minimalist DOM helpers
//=========================================================================

var Dom = {

  get:  function(id)                     { return ((id instanceof HTMLElement) || (id === document)) ? id : document.getElementById(id); },
  set:  function(id, html)               { Dom.get(id).innerHTML = html;                        },
  on:   function(ele, type, fn, capture) { Dom.get(ele).addEventListener(type, fn, capture);    },
  un:   function(ele, type, fn, capture) { Dom.get(ele).removeEventListener(type, fn, capture); },
  show: function(ele, type)              { Dom.get(ele).style.display = (type || 'block');      },
  blur: function(ev)                     { ev.target.blur();                                    },

  addClassName:    function(ele, name)     { Dom.toggleClassName(ele, name, true);  },
  removeClassName: function(ele, name)     { Dom.toggleClassName(ele, name, false); },
  toggleClassName: function(ele, name, on) {
    ele = Dom.get(ele);
    var classes = ele.className.split(' ');
    var n = classes.indexOf(name);
    on = (typeof on == 'undefined') ? (n < 0) : on;
    if (on && (n < 0))
      classes.push(name);
    else if (!on && (n >= 0))
      classes.splice(n, 1);
    ele.className = classes.join(' ');
  },

  storage: window.localStorage || {}

}

//=========================================================================
// general purpose helpers (mostly math)
//=========================================================================

var Util = {

  timestamp:        function()                  { return new Date().getTime();                                    },
  toInt:            function(obj, def)          { if (obj !== null) { var x = parseInt(obj, 10); if (!isNaN(x)) return x; } return Util.toInt(def, 0); },
  toFloat:          function(obj, def)          { if (obj !== null) { var x = parseFloat(obj);   if (!isNaN(x)) return x; } return Util.toFloat(def, 0.0); },
  limit:            function(value, min, max)   { return Math.max(min, Math.min(value, max));                     },
  randomInt:        function(min, max)          { return Math.round(Util.interpolate(min, max, Math.random()));   },
  randomChoice:     function(options)           { return options[Util.randomInt(0, options.length-1)];            },
  percentRemaining: function(n, total)          { return (n%total)/total;                                         },
  accelerate:       function(v, accel, dt)      { return v + (accel * dt);                                        },
  interpolate:      function(a,b,percent)       { return a + (b-a)*percent                                        },
  easeIn:           function(a,b,percent)       { return a + (b-a)*Math.pow(percent,2);                           },
  easeOut:          function(a,b,percent)       { return a + (b-a)*(1-Math.pow(1-percent,2));                     },
  easeInOut:        function(a,b,percent)       { return a + (b-a)*((-Math.cos(percent*Math.PI)/2) + 0.5);        },
  //exponentialFog:   function(distance, density) { return 1 / (Math.pow(Math.E, (distance * distance * density))); },

  increase:  function(start, increment, max) { // with looping
    var result = start + increment;
    while (result >= max)
      result -= max;
    while (result < 0)
      result += max;
    return result;
  },

  project: function(p, cameraX, cameraY, cameraZ, cameraDepth, width, height, roadWidth) {
    p.camera.x     = (p.world.x || 0) - cameraX;
    p.camera.y     = (p.world.y || 0) - cameraY;
    p.camera.z     = (p.world.z || 0) - cameraZ;
    p.screen.scale = cameraDepth/p.camera.z;
    p.screen.x     = Math.round((width/2)  + (p.screen.scale * p.camera.x  * width/2));
    p.screen.y     = Math.round((height/2) - (p.screen.scale * p.camera.y  * height/2));
    p.screen.w     = Math.round(             (p.screen.scale * roadWidth   * width/2));
  },

  overlap: function(x1, w1, x2, w2, percent) {
    var half = (percent || 1)/2;
    var min1 = x1 - (w1*half);
    var max1 = x1 + (w1*half);
    var min2 = x2 - (w2*half);
    var max2 = x2 + (w2*half);
    return ! ((max1 < min2) || (min1 > max2));
  }

}

//=========================================================================
// POLYFILL for requestAnimationFrame
//=========================================================================

if (!window.requestAnimationFrame) { // http://paulirish.com/2011/requestanimationframe-for-smart-animating/
  window.requestAnimationFrame = window.webkitRequestAnimationFrame || 
                                 window.mozRequestAnimationFrame    || 
                                 window.oRequestAnimationFrame      || 
                                 window.msRequestAnimationFrame     || 
                                 function(callback, element) {
                                   window.setTimeout(callback, 1000 / 60);
        }
}

//=========================================================================
// GAME LOOP helpers
//=========================================================================

var Game = {  // a modified version of the game loop from my previous boulderdash game - see http://codeincomplete.com/posts/2011/10/25/javascript_boulderdash/#gameloop

  run: function(options) {
    Game.loadImages(options.images, function(images) {
      
      options.ready(images); // tell caller to initialize itself because images are loaded and we're ready to rumble

      Game.setKeyListener(options.keys);

      var canvas = options.canvas,    // canvas render target is provided by caller
          update = options.update,    // method to update game logic is provided by caller
          render = options.render,    // method to render the game is provided by caller
          step   = options.step,      // fixed frame step (1/fps) is specified by caller
          now    = null,
          last   = Util.timestamp(),
          dt     = 0,
          gdt    = 0;
      function frame() {
        now = Util.timestamp();
        dt  = Math.min(1, (now - last) / 1000); // using requestAnimationFrame have to be able to handle large delta's caused when it 'hibernates' in a background or non-visible tab
        gdt = gdt + dt;
        end = options.end;
        //console.log(end + " in js");
          
        while (gdt > step) {
          gdt = gdt - step;
          update(step);
        }
        render();
        last = now;
        requestID = requestAnimationFrame(frame, canvas); 
      }
 
        frame(); // lets get this party started
        
        //Game.playMusic();
        /*document.getElementById("restart").addEventListener("click", function () {
            console.log(segments.length)
            cancelAnimationFrame(requestID);
            initialsetup();
            gamerun();
            reset();
            //resetRoad();
            requestID = requestAnimationFrame(frame,canvas);
        });*/
    });
  },

  //---------------------------------------------------------------------------

  loadImages: function(names, callback) { // load multiple images and callback when ALL images have loaded
    var result = [];
    var count  = names.length;

    var onload = function() {
      if (--count == 0)
        callback(result);
    };

    for(var n = 0 ; n < names.length ; n++) {
      var name = names[n];
      result[n] = document.createElement('img');
      Dom.on(result[n], 'load', onload);
      result[n].src = "images/" + name + ".png";
    }
  },

  //---------------------------------------------------------------------------

  setKeyListener: function(keys) {
    var onkey = function(keyCode, mode) {
      var n, k;
      for(n = 0 ; n < keys.length ; n++) {
        k = keys[n];
        k.mode = k.mode || 'up';
        if ((k.key == keyCode) || (k.keys && (k.keys.indexOf(keyCode) >= 0))) {
          if (k.mode == mode) {
            k.action.call();
          }
        }
      }
    };
    Dom.on(document, 'keydown', function(ev) { onkey(ev.keyCode, 'down'); } );
    Dom.on(document, 'keyup',   function(ev) { onkey(ev.keyCode, 'up');   } );
  },

  //---------------------------------------------------------------------------
  
  endGame: function () {
      return 1;
  },
  //---------------------------------------------------------------------------

  /*playMusic: function() {
    var music = Dom.get('music');
    music.loop = true;
    music.volume = 0.05; // shhhh! annoying music!
    music.muted = (Dom.storage.muted === "true");
    music.play();
    Dom.toggleClassName('mute', 'on', music.muted);
    Dom.on('mute', 'click', function() {
      Dom.storage.muted = music.muted = !music.muted;
      Dom.toggleClassName('mute', 'on', music.muted);
    });
  }*/

}

//=========================================================================
// canvas rendering helpers
//=========================================================================

var Render = {

  polygon: function(ctx, x1, y1, x2, y2, x3, y3, x4, y4, color) {
    ctx.fillStyle = color;
    ctx.beginPath();
    ctx.moveTo(x1, y1);
    ctx.lineTo(x2, y2);
    ctx.lineTo(x3, y3);
    ctx.lineTo(x4, y4);
    ctx.closePath();
    ctx.fill();
  },

  //---------------------------------------------------------------------------

  segment: function(ctx, width, lanes, x1, y1, w1, x2, y2, w2, color) {

    var r1 = Render.rumbleWidth(w1, lanes),
        r2 = Render.rumbleWidth(w2, lanes),
        l1 = Render.laneMarkerWidth(w1, lanes),
        l2 = Render.laneMarkerWidth(w2, lanes),
        lanew1, lanew2, lanex1, lanex2, lane;
    
    Render.polygon(ctx, x1-w1-r1, y1, x1-w1, y1, x2-w2, y2, x2-w2-r2, y2, color.rumble);
    Render.polygon(ctx, x1+w1+r1, y1, x1+w1, y1, x2+w2, y2, x2+w2+r2, y2, color.rumble);
    Render.polygon(ctx, x1-w1,    y1, x1+w1, y1, x2+w2, y2, x2-w2,    y2, color.road);
    
    if (color.lane) {
      lanew1 = w1*2/lanes;
      lanew2 = w2*2/lanes;
      lanex1 = x1 - w1 + lanew1;
      lanex2 = x2 - w2 + lanew2;
      for(lane = 1 ; lane < lanes ; lanex1 += lanew1, lanex2 += lanew2, lane++)
        Render.polygon(ctx, lanex1 - l1/2, y1, lanex1 + l1/2, y1, lanex2 + l2/2, y2, lanex2 - l2/2, y2, color.lane);
    }
  },

  //---------------------------------------------------------------------------

  background: function(ctx, background, width, height, layer) {

    var imageW = layer.w/2;
    var imageH = layer.h;

    var sourceX = layer.x
    var sourceY = layer.y
    var sourceW = Math.min(imageW, layer.x+layer.w-sourceX);
    var sourceH = imageH;
    
    var destX = 0;
    var destY = 0;
    var destW = 1120;
    var destH = 1150;

    ctx.drawImage(background, sourceX, sourceY, sourceW, sourceH, destX, destY, destW, destH);
    if (sourceW < imageW)
      ctx.drawImage(background, layer.x, sourceY, imageW-sourceW, sourceH, destW-1, destY, width-destW, destH);
  },

  //---------------------------------------------------------------------------

  sprite: function(ctx, width, height,  roadWidth, sprites, sprite, scale, destX, destY, offsetX, offsetY, clipY) {

                    //  scale for projection AND relative to roadWidth (for tweakUI)
    var destW  = (sprite.w * scale * width/2) * (SPRITES.SCALE * roadWidth);
    var destH  = (sprite.h * scale * width/2) * (SPRITES.SCALE * roadWidth);

    destX = destX + (destW * (offsetX || 0));
    destY = destY + (destH * (offsetY || 0));

    var clipH = clipY ? Math.max(0, destY+destH-clipY) : 0;
    if (clipH < destH)
      ctx.drawImage(sprites, sprite.x, sprite.y, sprite.w, sprite.h - (sprite.h*clipH/destH), destX, destY, destW, destH - clipH);

  },

  //---------------------------------------------------------------------------

  cars: function(ctx, width, height,  roadWidth, sprites, sprite, scale, destX, destY, offsetX, offsetY, clipY) {

                    //  scale for projection AND relative to roadWidth (for tweakUI)
    var destW  = (sprite.w * scale * width/2) * (SPRITES.PLAYER_SCALE * roadWidth);
    var destH  = (sprite.h * scale * width/2) * (SPRITES.PLAYER_SCALE * roadWidth);

    destX = destX + (destW * (offsetX || 0));
    destY = destY + (destH * (offsetY || 0));

    var clipH = clipY ? Math.max(0, destY+destH-clipY) : 0;
    if (clipH < destH)
      ctx.drawImage(sprites, sprite.x, sprite.y, sprite.w, sprite.h - (sprite.h*clipH/destH), destX, destY, destW, destH - clipH);

  },

  //---------------------------------------------------------------------------
  player: function(ctx, width, height, roadWidth, sprites, speedPercent, scale, destX, destY, dscp) {

    var bounce = (1.5 * Math.random() * speedPercent * height / 480) * Util.randomChoice([-1,1]);
    if (dscp)
       var sprite = SPRITES.CARS_DSCP[window.car_color];
    else
       var sprite = SPRITES.CARS[window.car_color];

    Render.cars(ctx, width, height,  roadWidth, sprites, sprite, scale, destX, destY + bounce, -0.5, -1);
  },

  //---------------------------------------------------------------------------

 
  rumbleWidth:     function(projectedRoadWidth, lanes) { return projectedRoadWidth/Math.max(6,  2*lanes); },
  laneMarkerWidth: function(projectedRoadWidth, lanes) { return projectedRoadWidth/Math.max(32, 8*lanes); }
  
}

//=============================================================================
// RACING GAME CONSTANTS
//=============================================================================

var KEY = {
  LEFT:  37,
  UP:    38,
  RIGHT: 39,
  DOWN:  40,
  A:     65,
  D:     68,
  S:     83,
  W:     87,
  SPACE: 32
};

var COLORS = {
  SKY:  '#110D2C',
  TREE: '#110D2C',
  //FOG:  '#110D2C',
  LIGHT: { road: '#023459', grass: '#10AA10', rumble: '#1E6461', lane: '#26E8E0'  },
  DARK:  { road: '#0F0F2E', grass: '#009A00', rumble: '#002C2F'                   },
  START:  { road: 'white',   grass: 'white',   rumble: 'white'                     },
  FINISH: { road: 'black',   grass: 'black',   rumble: 'black'                     }
};

var BACKGROUND = {
  HILLS: { x:   5, y:   5, w: 1280, h: 480 },
};

var SPRITES = {
  YELLOWTRUCK:            { x:  756, y: 1067, w:  184, h:  179 },
  PINKCAR:                { x:  756, y: 1274, w:  193, h:  133 },
  GREENBUG:               { x:  973, y: 1068, w:  158, h:  143 },
  BLACKCAR:               { x:  951, y: 1224, w:  173, h:  134 },
  REDSPORT:               { x: 1145, y: 1100, w:  176, h:  111 },
  WHITECAR:               { x: 1142, y: 1220, w:  183, h:  138 },
  BLUEVAN:                { x: 1337, y: 1093, w:  183, h:  151 },
  ORANGETRUCK:            { x: 1335, y: 1249, w:  186, h:  151 },

  YELLOWDSCP:             { x:  538, y: 1172, w:  191, h:  185 },
  PINKDSCP:               { x: 1687, y:  892, w:  203, h:  143 },
  GREENDSCP:              { x: 1704, y: 1045, w:  165, h:  150 },
  BLACKDSCP:              { x: 1681, y: 1207, w:  185, h:  134 },
  REDDSCP:                { x: 1881, y: 1078, w:  171, h:  115 },
  WHITEDSCP:              { x: 1882, y: 1204, w:  170, h:  138 },
  BLUEDSCP:               { x: 2072, y: 1072, w:  179, h:  155 },
  ORANGEDSCP:             { x: 2068, y: 1232, w:  184, h:  152 },
  
  PALM_TREE:              { x:   11, y:   10, w:  228, h:  551 },
  BILLBOARD_RED:          { x:  240, y:    9, w:  404, h:  275 },
  BILLBOARD_BLUE:         { x: 1693, y:   16, w:  391, h:  276 },
  BILLBOARD_YELLOW:       { x: 1692, y:  304, w:  380, h:  284 },
  BILLBOARD_GREEN:        { x: 1691, y:  589, w:  388, h:  278 },
  TREE1:                  { x:  630, y:   10, w:  372, h:  369 },
  DEAD_TREE1:             { x:   10, y:  562, w:  147, h:  341 },
  BILLBOARD09:            { x:  156, y:  561, w:  339, h:  292 }, //NTU
  COLUMN:                 { x: 1001, y:    9, w:  209, h:  326 },
  BILLBOARD01:            { x:  240, y:  297, w:  362, h:  261 }, //AIOT
  BILLBOARD06:            { x:  497, y:  563, w:  296, h:  196 }, //TANET
  BILLBOARD05:            { x:   15, y:  904, w:  300, h:  197 }, //TANET
  BILLBOARD07:            { x:  320, y:  902, w:  297, h:  200 }, //SAFETY
  BILLBOARD08:            { x:  805, y:  608, w:  386, h:  273 }, //5G
  BOULDER2:               { x:  626, y:  896, w:  311, h:  160 },
  TREE2:                  { x: 1206, y:    9, w:  301, h:  305 },
  BILLBOARD04:            { x: 1216, y:  315, w:  274, h:  177 },
  DEAD_TREE2:             { x: 1205, y:  498, w:  168, h:  263 },
  BOULDER1:               { x: 1204, y:  765, w:  189, h:  255 },
  BUSH1:                  { x:   17, y: 1104, w:  246, h:  162 },
  CACTUS:                 { x:  934, y:  898, w:  246, h:  131 },
  BUSH2:                  { x:  265, y: 1100, w:  248, h:  164 },
  BILLBOARD03:            { x:   17, y: 1267, w:  238, h:  228 },
  BILLBOARD02:            { x:  256, y: 1271, w:  219, h:  224 },
  STUMP:                  { x: 1004, y:  336, w:  204, h:  147 },
  RED_DOOR:               { x: 1525, y:  579, w:  130, h:  265 },
  YELLOW_DOOR:            { x: 1525, y:  860, w:  130, h:  265 },
  BLUE_DOOR:              { x: 1525, y:   18, w:  130, h:  265 },
  GREEN_DOOR:             { x: 1525, y:  290, w:  130, h:  267 },
};

SPRITES.SCALE = 0.33 * (1/80) // the reference sprite width should be 1/3rd the (half-)roadWidth
//SPRITES.PLAYER_SCALE = 0.2 * (1/SPRITES.REDSPORT.w);

SPRITES.BILLBOARDS = [SPRITES.BILLBOARD01, SPRITES.BILLBOARD02, SPRITES.BILLBOARD03, SPRITES.BILLBOARD04, SPRITES.BILLBOARD05, SPRITES.BILLBOARD06, SPRITES.BILLBOARD07,SPRITES.BILLBOARD08,SPRITES.BILLBOARD09];
SPRITES.PLANTS     = [SPRITES.TREE1, SPRITES.TREE2, SPRITES.DEAD_TREE1, SPRITES.DEAD_TREE2, SPRITES.PALM_TREE, SPRITES.BUSH1, SPRITES.BUSH2, SPRITES.CACTUS, SPRITES.STUMP, SPRITES.BOULDER1, SPRITES.BOULDER2];
SPRITES.CARS = [SPRITES.YELLOWTRUCK, SPRITES.PINKCAR, SPRITES.GREENBUG, SPRITES.BLACKCAR, SPRITES.REDSPORT, SPRITES.WHITECAR, SPRITES.BLUEVAN,  SPRITES.ORANGETRUCK];
SPRITES.CARS_DSCP = [SPRITES.YELLOWDSCP, SPRITES.PINKDSCP, SPRITES.GREENDSCP, SPRITES.BLACKDSCP, SPRITES.REDDSCP, SPRITES.WHITEDSCP, SPRITES.BLUEDSCP,  SPRITES.ORANGEDSCP];
SPRITES.FIREWALLS = [SPRITES.BILLBOARD_RED, SPRITES.BILLBOARD_GREEN, SPRITES.BILLBOARD_BLUE, SPRITES.BILLBOARD_YELLOW];
