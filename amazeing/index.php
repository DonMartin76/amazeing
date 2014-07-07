<html>
<head>
<title>DonMartin76 Caching: GC4YPRT Amazeing</title>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>

<?php
function get_cookie_username()
{
    if (isset($_COOKIE['amazeing_username']))
        return $_COOKIE['amazeing_username'];
    return '';
}
?>
<body>
<!-- <h1>Just a-generate da maze:</h1>

<p>Amazeing input parameters:</p>
<form action="amazeing.php" method=post>
<p>Width: <input type="text" name="width" value="10"/></p>
<p>Height: <input type="text" name="height" value="10"/></p>
<input type="submit" value="Generate"/>
</form>

<h1>Play da maze:</h1>

<p>Amazeing input parameters:</p>
<form action="play.php" method=post>
<p>Width: <input type="text" name="width" value="5"/></p>
<p>Height: <input type="text" name="height" value="5"/></p>
<input type="submit" value="Generate"/> -->
<center>
<div class="container">
<h1>Du willst spielen?</h1>

<p>Das ist sch&ouml;n!</p>

<p>Damit hier auch alles mit rechten Dingen zugeht, hat dieses Spiel eine Bestenliste, wer es am schnellsten schafft,
durch das Labyrinth zu navigieren. Daf&uuml;r kannst du hier gerne einen Namen eingeben. Wenn du deinen Namen nicht angeben
m&ouml;chtest (du kannst einen beliebigen Namen w&auml;hlen), so kannst du nat&uuml;rlich auch anonym spielen. Dann wirst du aber
auch nicht auf der Bestenliste auftauchen.</p>

<blockquote>
    <form action="play.php" method=post>
        <p>Dein Name: <input type="text" name="username" value="<?php print(get_cookie_username()); ?>" style="font-size:large"/></p>
        <p><input type="checkbox" name="play_anon" value="yes" />Ich m&ouml;chte lieber anonym spielen</p>
        <p><input type="submit" value="Spielen" style="font-size:large"/></p>
    </form>
</blockquote>

<p><b>Anmerkung:</b> Diese Webseite benutzt zum Abspeichern gewisser Daten, wie z.B. den Namen des Spielers,
Cookies. Wenn du nicht willst, dass Cookies verwendet werden, so musst du anonym spielen. In diesem Fall wird
die Seite keine Cookies setzen.</p>

<center>
<img src="hiscore.php" alt="Bestenliste"/>
</center>

<p>Wenn du einfach nur Labyrinthe generieren willst, dann kannst du das hier tun: <a href="amazeing.php">Amazeing</a>.</p>
</div>
</center>
</body>
</html>