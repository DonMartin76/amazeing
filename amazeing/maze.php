<?php
require('amazeing_session.php');
session_start();

$is_first = false;
if (isset($_SESSION['is_first']))
    $is_first = $_SESSION['is_first'];

if ($is_first)
{
    $_SESSION['start_time'] = time();
    $_SESSION['is_first'] = false;
}
?>
<html>
<head>
<title>DonMartin76 Caching: GC4YPRT Amazeing</title>
<link rel="stylesheet" type="text/css" href="style.css"/>

<script type="text/javascript">
last_was_map = false;
current_size = 490;

isNetscape = (navigator.appName == 'Netscape');

function load_maze(action)
{
    var myWidth = 0, myHeight = 0;
    if( typeof( window.innerWidth ) == 'number' ) {
        //Non-IE
        myWidth = window.innerWidth;
        myHeight = window.innerHeight;
    } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
        //IE 6+ in 'standards compliant mode'
        myWidth = document.documentElement.clientWidth;
        myHeight = document.documentElement.clientHeight;
    } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
        //IE 4 compatible
        myWidth = document.body.clientWidth;
        myHeight = document.body.clientHeight;
    }
    
    size = myHeight;
    if (myWidth < myHeight)
    {
        size = myWidth - 30;
    }
    else
    {
        size = size - 100;
    }
    if (size < 400)
    {
        size = 400;
    }
    current_size = size;
        
    if (window.XMLHttpRequest)
    {
        // AJAX nutzen mit IE7+, Chrome, Firefox, Safari, Opera
        xmlhttp=new XMLHttpRequest();
    }
    else
    {
        // AJAX mit IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (action == 'map')
    {
        if (last_was_map)
        {
            action = 'none';
            last_was_map = false;
        }
        else
        {
            last_was_map = true;
        }
    }
    else
    {
        last_was_map = false;
    }
    
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById("maze").innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET","render_maze.php?action=" + action + "&size=" + size,true);
    xmlhttp.send();
}

function turn_left()
{
    load_maze('left');
}

function turn_right()
{
    load_maze('right');
}

function forward()
{
    load_maze('forward');
}

function onload()
{
    load_maze('none');
}

function show_map()
{
    load_maze('map');
}

function on_keypress(event)
{
    if (!event)
        event = window.event;
    keyCode = event.keyCode;
    if (isNetscape)
        keyCode = event.charCode;
    if (keyCode == 65 || keyCode == 97)
        turn_left();
    else if (keyCode == 68 || keyCode == 100)
        turn_right();
    else if (keyCode == 87 || keyCode == 119)
        forward();
    else if (keyCode == 77 || keyCode == 109)
        show_map();
}

function on_maze_click(event)
{
    if (!event)
        event = window.event;
    
    var posx = 0;
    var posy = 0;
    if (event.pageX || event.pageY)
    {
        posx = event.pageX;
        posy = event.pageY;
    }
    else if (event.clientX || event.clientY)
    {
        posx = event.clientX;
        posy = event.clientY;
    }
    
    if (posx < (current_size / 3) && posy > (current_size / 4))
        turn_left();
    else if (posx > (current_size / 3 * 2) && posy > (current_size / 4))
        turn_right();
    else if (posy < (current_size / 3))
        forward();
    else
        show_map();
}

document.onkeypress = on_keypress;

</script>
</head>
<body onload="javascript:onload()">
<div id="maze" onclick="javascript:on_maze_click(event)">
</div>

<input type="button" onclick="javascript:turn_left()" value="Links (A)"/>
<input type="button" onclick="javascript:forward()" value="Vorw&auml;rts (W)" />
<input type="button" onclick="javascript:turn_right()" value="Rechts (D)" />
<input type="button" onclick="javascript:show_map()" value="Karte (M)" />

</body>
</html>