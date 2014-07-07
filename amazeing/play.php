<?php
require('amazeing_session.php');
session_start();

require_once('../hitcounter/hitcounter.php');
require_once('hiscore_func.php');

// Set the name cookie, if wanted
$username = '';
if (isset($_POST['username']))
    $username = $_POST['username'];
$anonymous = false;
if (isset($_POST['play_anon']))
{
//    print($_POST['play_anon']);
    if ($_POST['play_anon'] == 'yes')
        $anonymous = true;
}

if (!$anonymous && strcmp($username, '') != 0)
{
    setcookie('amazeing_username', $username);
    $_SESSION['anonymous'] = false;
    $_SESSION['username'] = $username;
}
else
{
    $_SESSION['anonymous'] = true;
}

$_SESSION['is_first'] = true;
unset($_SESSION['end_time']);
unset($_SESSION['start_time']);
?>
<html>
<head>
<title>DonMartin76 Caching: GC4YPRT Amazeing</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
<meta http-equiv="cache-control" content="no-cache"> 
</head>
<body>
<?php
include 'maze_gen.php';

$w = 5;
$h = 5;

$maze_width = 2 * $w + 1;
$maze_height = 2 * $h + 1;

$maze = prim_maze($w, $h);
//$maze = mark_hints($maze, $w, $h);
$visited = empty_maze($w, $h);
//$maze = depth_first_maze($w, $h);
//dump_maze($maze, $w, $h);

$start_index = index_of($maze, $maze_width, 'S');
$startx = get_block_x($maze_width, $start_index);
$starty = get_block_y($maze_width, $start_index);

$goal_index = index_of($maze, $maze_width, 'E');
$goalx = get_block_x($maze_width, $goal_index);
$goaly = get_block_y($maze_width, $goal_index);

$_SESSION['width'] = $w;
$_SESSION['height'] = $h;
$_SESSION['maze'] = $maze;
$_SESSION['visited'] = $visited;
$_SESSION['posx'] = $startx;
$_SESSION['posy'] = $starty;
$_SESSION['goalx'] = $goalx;
$_SESSION['goaly'] = $goaly;
$_SESSION['direction'] = 'up';

reset_hiscore_img();

session_write_close();
?>
<center><div class="container">
<h1>Es geht bald los...</h1>

<p>Dies ist Spiel Nummer <?php print(get_hit_count('amazeing_games')); ?>.</p>

<p>Hallo, liebe(r) <?php if ($anonymous) print('Spieler/in'); else print($username); ?>!</p>

<p>Zuerst ein paar Kleinigkeiten zur Bedienung:</p>

<ul>
    <li>Steuerung entweder &uuml;ber die Schaltfl&auml;chen</li>
    <li>... oder &uuml;ber die Tastaturk&uuml;rzel (A, W, D, M)</li>
    <li>... oder &uuml;ber Klicken auf die Bereiche in der 3D-Darstellung (zum Beispiel f&uuml;rs Smartphone)</li>
</ul>

<p></p><img src="howto.png"></p>

<p>Das Bild zeigt die schematische Darstellung f&uuml;r die Bedienung durch Tippen auf das Smartphone,
oder mit Hilfe der Maus. Die Bedienung per Tastatur ist jedoch mit Sicherheit am einfachsten.</p>

<p>Der gr&uuml;ne Pfeil gibt an, in welche Richtung im Labyrinth du gerade schaust, ob nach oben, links,
rechts oder nach unten. Die Zahl darunter gibt an, wie weit vom Ziel weg du gerade bist. Beachte bitte,
dass du am Anfang wahrscheinlich so weit wie nur irgend m&ouml;glich vom Ziel weg bist; das Ziel befindet
sich meistens im entgegengesetzte Eck. Aber das wirst du ja noch merken.</p>

<p>Mache gerne regen Gebrauch der Karten-Funktion; du wirst sie auf jeden Fall brauchen!</p>

<p>Die Tastaturbedienung wurde zumindest mit Internet Explorer 9+ und mit Chrome erfolgreich
getestet.</p>

<p><a href="maze.php"><b>Los geht's!</b></a></p>
</div>
</center>
</body>
