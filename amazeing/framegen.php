<html>
<head>
<title>Cool labyrinth frame</title>
</head>
<body>
    <div style="font-family:Courier;">
<?php
include 'maze_gen.php';

$w = 40;
$h = 25;
$frame = 4;

$maze = empty_maze($w, $h);
$mw = 2*$w + 1;
$mh = 2*$h + 1;

$minx = 2 * $frame + 1;
$maxx = $mw - 2 * ($frame +1 );
$miny = 2 * $frame + 1;
$maxy = $mh - 2 * ($frame + 1);

for ($x = $minx; $x <= $maxx; ++$x)
{
    for ($y = $miny; $y <= $maxy; ++$y)
    {
        $maze = set_block($maze, $mw, $x, $y, ' ');
    }
}

$maze = depth_first_maze_internal($maze, $w, $h, 1, 1);

dump_maze($maze, $w, $h);

?>
    </div>
</body>
</html>