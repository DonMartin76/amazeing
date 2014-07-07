<?php
require('amazeing_session.php');
session_start();

require('../hitcounter/hitcounter.php');
require_once('hiscore_func.php');

$username = '';
if (isset($_SESSION['username']))
    $username = $_SESSION['username'];
$anonymous = true;
if (isset($_SESSION['anonymous']))
    $anonymous = $_SESSION['anonymous'];
    
$is_on_goal = false;
if (isset($_SESSION['is_on_goal']))
    $is_on_goal = $_SESSION['is_on_goal'];
    
$coords = '699';
//if (isset($_POST['coords']))
//    $coords = trim($_POST['coords']);
    
$correct = false;
if (strcmp($coords, '699') == 0)
{
    $correct = true;
}
$current_time = time();
$too_early = false;
if (isset($_SESSION['last_check']))
{
    $last_check = (int)$_SESSION['last_check'];
    if ($current_time - $last_check < 20)
        $too_early = true;
}
$_SESSION['last_check'] = $current_time;

reset_hiscore_img();
?>
<html>
<head>
<title>DonMartin76 Caching: GC4YPRT Amazeing</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
    <center><div class="container">
<?php
if (!$is_on_goal)
{
    ?>
    <h1>Na, so geht das aber nicht</h1>
    
    <p>Netter Versuch. Aber wer den Ausgang nicht gefunden hat und wirklich drauf steht, der kann
    den Checker nat&uuml;rlich nicht aufrufen.</p>
    
    <p><a href="maze.php">Zur&uuml;ck ins Labyrinth mit dir!</a></p>
    <?php
}
else if (!$correct && $too_early)
{
?>
    <h1 style="color:red">Sachte, sachte!</h1>
    
    <p>Du sollst die Koordinaten nicht per Brute-Force erraten, du sollst sie herausfinden.</p>
    
    <p>Daher m&uuml;ssen zwischen den Checks mindestens 20 Sekunden liegen; auch jetzt musst du wieder
    20 Sekunden warten, bis du wieder checken kannst. Das reicht auch. Wenn
    dir nicht klar ist, wie die letzten drei Ziffern der Ost-Koordinate lauten...</p>
    
    <p><a href="maze.php">... gehe doch zur&uuml;ck ins Labyrinth.</a></p>
    
    <p>Nicht verzagen :-)</p>
<?php
}
else if (!$correct)
{
?>
    <h1>Schade!</h1>
    
    <p>Die Koordinaten stimmen nicht.</p>
    
    <p>Da musst du dich wohl noch ein wenig im Labyrinth umschauen, bis die richtigen Koordinaten
    auftauchen.</p>
    
    <p>Du wirst es erkennen, wenn du es siehst, aber so richtig einfach ist es nicht.</p>
    
    <p>Nur Mut. Erkunde das Labyrinth. Die Koordinaten sind da. Sozusagen.</p>
    
    <p>Dies war der <?php print(get_hit_count('amazeing_wrong')); ?>. fehlerhafte Versuch insgesamt.</p>
    
    <p><a href="maze.php">Lasse mich zur&uuml;ck ins Labyrinth.</a></p>
<?php
}
else
{
    // Correct!
    // Calculate the time
    $start_time = 0;
    if (isset($_SESSION['start_time']))
        $start_time = (int)$_SESSION['start_time'];
    $end_time = time();
    $second_call = false;
    if (isset($_SESSION['end_time']))
    {
        $end_time = $_SESSION['end_time'];
        $second_call = true;
    }
    else
    {
        $_SESSION['end_time'] = $end_time;
    }
    
    $diff_time = $end_time - $start_time;
    $hours = (int)($diff_time / 3600);
    $rest = $diff_time - $hours * 3600;
    $minutes = (int)($rest / 60);
    $seconds = $rest - $minutes * 60;
?>
    <h1 style="color:green">Liebe(r) <?php if ($anonymous) print('Spieler/in'); else print($username); ?>, herzlichen Gl&uuml;ckwunsch!</h1>
    
   <!-- <p style="font-size:xx-large; text-align:center">N47° 53.023<br>E007° 43.699</p> -->
    
    <p>Du hast f&uuml;r die L&ouml;sung <?php print($hours); ?> Stunden, <?php print($minutes); ?> Minuten und
    <?php print($seconds); ?> Sekunden ben&ouml;tigt.</p>
    
    <?php if (!$second_call) { ?>
    <p>Du bist die Nummer <?php print(get_hit_count('amazeing_correct')); ?>, das R&auml;tsel zu l&ouml;sen!</p>
    <?php } ?>
<!--    <p><b>Achtung:</b> Wenn du den Cache hebst, lasse bitte die B&uuml;sche in Ruhe. Da ist der Cache nicht. Beachte den
    Hint auf der <a href="http://coord.info/GC4YPRT" target="_blank">Cache-Seite bei geocaching.com</a>.
    Es ist nicht sehr schwer zu finden.</p>
    <p>&nbsp;</p> -->
    <p><a href="index.php">Nochmal spielen.</a></p>
<?php

    if (!$anonymous && !$second_call)
    {
        enter_hiscore($username, $hours, $minutes, $seconds);
    }
}
?>
</div></center>
</body>
</html>