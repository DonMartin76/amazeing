<?php
require('amazeing_session.php');
session_start();

$username = '';
if (isset($_SESSION['username']))
    $username = $_SESSION['username'];
$anonymous = true;
if (isset($_SESSION['anonymous']))
    $anonymous = $_SESSION['anonymous'];
    
$is_on_goal = false;
if (isset($_SESSION['is_on_goal']))
    $is_on_goal = $_SESSION['is_on_goal'];
?>
<html>
<head>
<title>DonMartin76 Caching: GC4YPRT Amazeing</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
    <center><div class="container">
        <h1>So, liebe(r) <?php if ($anonymous) print('Spieler/in'); else print($username); ?>...</h1>
        
<?php
if (!$is_on_goal)
{
    ?>
    <p>Netter Versuch. Aber wer den Ausgang nicht gefunden hat und wirklich drauf steht, der kann
    den Checker nat&uuml;rlich nicht aufrufen.</p>
    
    <p><a href="maze.php">Zur&uuml;ck ins Labyrinth mit dir!</a></p>
    <?php
}
else
{
?>
    <p>Herzlichen Gl&uuml;ckwunsch!</p>
    
    <p>Du hast den Ausgang gefunden!</p>
    
<!--    <p>Dann gib doch hier bitte die drei letzten Ziffern der Ost-Koordinate ein:</p>
    <blockquote>
    <form action="docheck.php" method=post>
        <p>N47° 53.023</p>
        <p>E007° 43.<input type="text" name="coords" value="" style="width:70px; font-size:large"/></p>
        <p><input type="submit" value="Check!" style="font-size:large"/></p>
    </form>
    </blockquote> 
    
    <p><a href="maze.php">Ich will doch lieber zur&uuml;ck ins Labyrinth.</a></p> -->
    
    <p>Klicke hier, um dich in die Bestenliste einzutragen:</p>
    
    <form action="docheck.php" method=post>
        <input type="submit" value"Ja, ich will!" style="font-size:large"/>
    </form>
<?php
}
?>
</div></center>
</body>
</html>