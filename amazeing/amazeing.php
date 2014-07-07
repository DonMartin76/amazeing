<html>
<head>
<title>Amazeing</title>
</head>
<body>
 <?php
//session_start();

include 'maze_gen.php';

$w = 20;
$h = 20;

//$post_width = $_POST['width'];
//$post_height = $_POST['height'];

$width = 20;
$height = 20;
if (isset($_GET['width']))
    $width = (int)$_GET['width'];
if (isset($_GET['height']))
    $height = (int)$_GET['height'];

if ($width > 0 && $width <= 50 && $height > 0 && $height <= 50)
{
    $w = $width;
    $h = $height;
}
else
{
    print('Width and height not set or invalid (must be between 1 and 50). Using 20x20<br>');
}

?>
<div style="font-family:Courier">
<h1>Depth-First Algorithm</h1>

<p>If you want to generate a different size, use this link and alter the get parameters:</p>
<p><a href="amazeing.php?width=20&height=20">Generate with parameters</a>.</p>

<?php
$maze = depth_first_maze($w, $h);
dump_maze($maze, $w, $h);
?>

<!--<h1>Prim's Randomized Algorithm</h1>-->

<?php

//$maze = prim_maze($w, $h);
//$maze = depth_first_maze($w, $h);
//dump_maze($maze, $w, $h);

?>
</div>    
</body>
