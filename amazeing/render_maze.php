<?php
require('amazeing_session.php');
session_start();

include 'maze_gen.php';

class Point3d
{
    public $x;
    public $y;
    public $z;
}

class Point2d
{
    public $x;
    public $y;
}

class Env3d
{
    public $vp_width;
    public $vp_height;
    public $d;
    public $room_height;
}

function project_x(Env3d $env, Point3d $p)
{
    return $env->vp_width * (0.5 + $p->x / (1 - $p->z / $env->d));
}

function project_y(Env3d $env, Point3d $p)
{
    return $env->vp_height * (0.5 + $p->y / (1 - $p->z / $env->d));
}

function svg_line(Env3d $env, Point3d $p1, Point3d $p2, $color)
{
    $x1 = (int)project_x($env, $p1);
    $y1 = (int)project_y($env, $p1);
    
    $x2 = (int)project_x($env, $p2);
    $y2 = (int)project_y($env, $p2);
    
    print('<line x1="'.$x1.'" y1="'.$y1.'" x2="'.$x2.'" y2="'.$y2.'" style="stroke:'.$color.'; stroke-width=2" />');
}

function svg_polyline(Env3d $env, $points, $color)
{
    print('<polygon points="');
    $first = 1;
    for ($i=0; $i<count($points); ++$i)
    {
        $p = $points[$i];
        $x = (int)(project_x($env, $p) + 0.5);
        $y = (int)(project_y($env, $p) + 0.5);
        
        if ($first == 0)
        {
            print(' ');
        }
        print($x.','.$y);
        
        $first = 0;
    }
    print('" style="fill:none" stroke="'.$color.'" stroke-width="1"/>');
}

function svg_poly(Env3d $env, $points, $color)
{
//    print('<!-- ');
//    var_dump($points);
//    print('-->');
    print('<polygon points="');
 //   /*
    $first = 1;
    for ($i=0; $i<count($points); ++$i)
    {
        $p = $points[$i];
        $x = (int)(project_x($env, $p) + 0.5);
        $y = (int)(project_y($env, $p) + 0.5);
        
        if ($first == 0)
        {
            print(' ');
        }
        print($x.','.$y);
        
        $first = 0;
    }
 //   */
    print('" style="fill:'.$color.'" stroke="black" stroke-width="1"/>');
}

function draw_frontal_pane(Env3d $env, $z, $xoffset, $color)
{
    $zz = $z + 0.5;
    $p1 = new Point3d();
    $p1->x = -0.5 + $xoffset;
    $p1->y = -$env->room_height;
    $p1->z = $zz;

    $p2 = new Point3d();
    $p2->x = 0.5 + $xoffset;
    $p2->y = -$env->room_height;
    $p2->z = $zz;

    $p3 = new Point3d();
    $p3->x = 0.5 + $xoffset;
    $p3->y = $env->room_height;
    $p3->z = $zz;

    $p4 = new Point3d();
    $p4->x = -0.5 + $xoffset;
    $p4->y = $env->room_height;
    $p4->z = $zz;
    
    svg_poly($env, array($p1, $p2, $p3, $p4), $color);
}

function draw_front(Env3d $env, $z, $color)
{
    draw_frontal_pane($env, $z, 0, $color);
}

function draw_left_hole(Env3d $env, $z, $color)
{
    draw_frontal_pane($env, $z, -1, $color);
}

function draw_right_hole(Env3d $env, $z, $color)
{
    draw_frontal_pane($env, $z, 1, $color);
}

function draw_wall_pane(Env3d $env, $z, $xoffset, $color)
{
    $zz = $z - 0.5;
    $p1 = new Point3d();
    $p1->x = $xoffset;
    $p1->y = -$env->room_height;
    $p1->z = $zz;

    $p2 = new Point3d();
    $p2->x = $xoffset;
    $p2->y = $env->room_height;
    $p2->z = $zz;

    $p3 = new Point3d();
    $p3->x = $xoffset;
    $p3->y = $env->room_height;
    $p3->z = $zz+1;

    $p4 = new Point3d();
    $p4->x = $xoffset;
    $p4->y = -$env->room_height;
    $p4->z = $zz+1;
    
    svg_poly($env, array($p1, $p2, $p3, $p4), $color);
}

function draw_left_wall(Env3d $env, $z, $color)
{
    draw_wall_pane($env, $z, -0.5, $color);
}

function draw_right_wall(Env3d $env, $z, $color)
{
    draw_wall_pane($env, $z, 0.5, $color);
}

function draw_floor(Env3d $env, $z, $color)
{
    $zz = $z - 0.5;
    $p1 = new Point3d();
    $p1->x = -0.5;
    $p1->y = $env->room_height;
    $p1->z = $zz;

    $p2 = new Point3d();
    $p2->x = -0.5;
    $p2->y = $env->room_height;
    $p2->z = $zz+1;

    $p3 = new Point3d();
    $p3->x = 0.5;
    $p3->y = $env->room_height;
    $p3->z = $zz+1;

    $p4 = new Point3d();
    $p4->x = 0.5;
    $p4->y = $env->room_height;
    $p4->z = $zz;
    
    svg_poly($env, array($p1, $p2, $p3, $p4), $color);
}

function svg_2d_poly($points, $color)
{
    print('<polygon points="');
 //   /*
    $first = 1;
    for ($i=0; $i<count($points); ++$i)
    {
        $p = $points[$i];
        $x = (int)($p->x);
        $y = (int)($p->y);
        
        if ($first == 0)
        {
            print(' ');
        }
        print($x.','.$y);
        
        $first = 0;
    }
 //   */
    print('" style="fill:'.$color.'" stroke="black" stroke-width="1"/>');
}

function draw_direction(Env3d $env, $direction)
{
    $arrow_size = (int)$env->vp_width / 10;
    $mid = (int) ($arrow_size / 2);
    $quart = (int)$arrow_size / 4;
    $p1 = new Point2d();
    $p2 = new Point2d();
    $p3 = new Point2d();
    
    if (strcmp($direction, 'up') == 0)
    {
        $p1->x = $quart + $mid - $quart;
        $p1->y = $quart + $mid + $mid;
        $p2->x = $quart + $mid;
        $p2->y = $quart;
        $p3->x = $quart + $mid + $quart;
        $p3->y = $quart + $mid + $mid;
    }
    else if (strcmp($direction, 'left') == 0)
    {
        $p1->x = $quart;
        $p1->y = $quart + $mid;
        $p2->x = $quart + $mid + $mid;
        $p2->y = $quart + $mid - $quart;
        $p3->x = $quart + $mid + $mid;
        $p3->y = $quart + $mid + $quart;
    }
    else if (strcmp($direction, 'down') == 0)
    {
        $p1->x = $quart + $mid - $quart;
        $p1->y = $quart;
        $p2->x = $quart + $mid + $quart;
        $p2->y = $quart;
        $p3->x = $quart + $mid;
        $p3->y = $quart + $mid + $mid;
    }
    else // right
    {
        $p1->x = $quart;
        $p1->y = $quart + $mid - $quart;
        $p2->x = $quart;
        $p2->y = $quart + $mid + $quart;;
        $p3->x = $quart + $mid + $mid;
        $p3->y = $quart + $mid;
    }
    
    svg_2d_poly(array($p1, $p2, $p3), 'lime');
}

function draw_distance(Env3d $env, $distance)
{
    $arrow_size = (int)$env->vp_width / 10;
    $mid = (int) ($arrow_size / 2);
    $quart = (int)$arrow_size / 4;
    $offset = 2;
    
    $text_size = (int)($arrow_size / 3); // ballpark number
    if ($text_size < 7)
        $text_size = 7;
    
    print('<text x="'.($mid + $quart).'" y="'.($arrow_size + $mid + $quart).'" style="font-size:'.$text_size.'" text-anchor="middle">'.$distance.'m</text>');
    print('<text x="'.($mid + $quart - $offset).'" y="'.($arrow_size + $mid + $quart - $offset).'" style="font-size:'.$text_size.'" fill="white" text-anchor="middle">'.$distance.'m</text>');
}

function draw_text(Env3d $env, $zz, $text)
{
    // Text
    $p = new Point3d();
    $p->x = 0;
    $p->y = 0.3;
    $p->z = $zz;
    
    $texty1 = project_y($env, $p);
    $p->y = 0.4;
    $texty2 = project_y($env, $p);
    $textx = project_x($env, $p);
    $text_size = (int)(($texty2 - $texty1));
    print('<text x="'.$textx.'" y="'.$texty2.'" style="font-size:'.$text_size.'px" fill="black" text-anchor="middle">'.$text.'</text>');
}
function draw_sierpinski(Env3d $env, $z)
{
    $max_depth = 5;
    $size = 0.3;
    $zz = $z + 1;

    $p1 = new Point3d();
    $p1->x = -1.2*$size;
    $p1->y = $size;
    $p1->z = $zz;
    $p2 = new Point3d();
    $p2->x = 1.2*$size;
    $p2->y = $size;
    $p2->z = $zz;
    $p3 = new Point3d();
    $p3->x = 0;
    $p3->y = -$size;
    $p3->z = $zz;
    
    draw_sierpinski_recursive($env, 0, $max_depth, $p1, $p2, $p3);
    draw_text($env, $zz, 'SIERPINSKI');    
}

function draw_sierpinski_recursive(Env3d $env, $depth, $max_depth, Point3d $p1, Point3d $p2, Point3d $p3)
{
    svg_polyline($env, array($p1, $p2, $p3), 'black');
    
    if ($depth == $max_depth)
        return;
    
    $p4 = new Point3d();
    $p4->x = ($p1->x + $p3->x) / 2;
    $p4->y = ($p1->y + $p3->y) / 2;
    $p4->z = $p1->z;
    $p5 = new Point3d();
    $p5->x = ($p2->x + $p3->x) / 2;
    $p5->y = ($p2->y + $p3->y) / 2;
    $p5->z = $p1->z;
    $p6 = new Point3d();
    $p6->x = ($p1->x + $p2->x) / 2;
    $p6->y = $p1->y;
    $p6->z = $p1->z;
    
    draw_sierpinski_recursive($env, $depth+1, $max_depth, $p1, $p6, $p4);
    draw_sierpinski_recursive($env, $depth+1, $max_depth, $p6, $p2, $p5);
    draw_sierpinski_recursive($env, $depth+1, $max_depth, $p4, $p5, $p3);
}

function draw_koch(Env3d $env, $z)
{
    $max_depth = 4;
    $size = 0.4;
    $zz = $z + 1;
    
    $p1 = new Point3d();
    $p1->x = -$size;
    $p1->y = $size/4;
    $p1->z = $zz;
    $p2 = new Point3d();
    $p2->x = $size;
    $p2->y = $size/4;
    $p2->z = $zz;
    
    draw_koch_recursive($env, 0, $max_depth, $p1, $p2, 0);
    draw_text($env, $zz, 'KOCH');    
}

function draw_koch_recursive(Env3d $env, $depth, $max_depth, Point3d $p1, Point3d $p2, $angle)
{
    if ($depth == $max_depth)
    {
        svg_polyline($env, array($p1, $p2), 'black');
    }
    else
    {
        $length = sqrt(($p1->x - $p2->x) * ($p1->x - $p2->x) + ($p1->y - $p2->y) * ($p1->y - $p2->y));
        $new_length = $length / 3;
        $p3 = new Point3d();
        $p3->x = (2 * $p1->x + $p2->x) / 3;
        $p3->y = (2 * $p1->y + $p2->y) / 3;
        $p3->z = $p1->z;
        $p4 = new Point3d();
        $p4->x = $p3->x + $new_length * cos(deg2rad($angle + 60));
        $p4->y = $p3->y - $new_length * sin(deg2rad($angle + 60));
        $p4->z = $p1->z;
        $p5 = new Point3d();
        $p5->x = ($p1->x + 2 * $p2->x) / 3;
        $p5->y = ($p1->y + 2 * $p2->y) / 3;
        $p5->z = $p1->z;
        
        draw_koch_recursive($env, $depth+1, $max_depth, $p1, $p3, $angle);
        draw_koch_recursive($env, $depth+1, $max_depth, $p3, $p4, $angle + 60);
        draw_koch_recursive($env, $depth+1, $max_depth, $p4, $p5, $angle - 60);
        draw_koch_recursive($env, $depth+1, $max_depth, $p5, $p2, $angle);
    }
}

function draw_comment(Env3d $env, $z)
{
    $zz = $z + 1;
    $line1 = "&lt;!-- Dies ist ein";
    $line2 = "Kommentar --&gt;";
    
    // Text
    $p = new Point3d();
    $p->x = 0;
    $p->y = -0.05;
    $p->z = $zz;
    
    $texty1 = project_y($env, $p);
    $p->y = 0.05;
    $texty2 = project_y($env, $p);
    $textx = project_x($env, $p);
    $text_size = (int)(($texty2 - $texty1));
    print('<text x="'.$textx.'" y="'.$texty1.'" style="font-size:'.$text_size.'px" fill="black" text-anchor="middle">'.$line1.'</text>');
    print('<text x="'.$textx.'" y="'.$texty2.'" style="font-size:'.$text_size.'px" fill="black" text-anchor="middle">'.$line2.'</text>');
}

function draw_coordinates(Env3d $env, $direction)
{
    $north = 'N47 53.023';
    $east = 'E007 43.???';
    $link_text = 'Congrats!';

    $holecolor = '#0000ff';
    
    if (strcmp($direction, 'left') == 0
        || strcmp($direction, 'right') == 0)
    {
        $holecolor = '#ff0000';
    }
    
    $text_size = $env->vp_height / 10;
    if ($text_size < 7)
        $text_size = 7;
        
    $midx = (int)($env->vp_width / 2);
    $midy = (int)($env->vp_height / 3);
    
//    print('<text x="'.$midx.'" y="'.($midy).'" text-anchor="middle" fill="white" style="font-size:'.$text_size.'">'.$north);
//    print('<animate attributeName="fill" from="'.$holecolor.'" to="#ffffff" dur="5s"/>');
//    print('</text>');
//    print('<text x="'.$midx.'" y="'.(int)($midy + 1.2 * $text_size).'" text-anchor="middle" fill="white" style="font-size:'.$text_size.'">'.$east);
//    print('<animate attributeName="fill" from="'.$holecolor.'" to="#ffffff" dur="5s"/>');
//    print('</text>');
    print('<a xlink:href="checker.php">');
    print('<text x="'.$midx.'" y="'.(int)($midy + 2.4 * $text_size).'" text-anchor="middle" fill="black" style="font-size:'.$text_size.'">'.$link_text);
    print('</text>');
    print('</a>');
}

function render_maze(Env3d $env, $maze, $w, $h, $posx, $posy, $direction, $visited)
{
    $maze_width = $w * 2 + 1;
    $maze_height = $h * 2 + 1;

    print('<rect x="0" y="0" width="'.$env->vp_width.'" height="'.($env->vp_height/2).'" style="fill:lightgray" />');
    print('<rect x="0" y="'.($env->vp_height/2).'" width="'.$env->vp_width.'" height="'.($env->vp_height/2).'" style="fill:gray" />');
    
    $action = 'none';
    if (isset($_GET['action']))
        $action = $_GET['action'];
    
    if (strcmp($action, 'left') == 0)
    {
        if (strcmp($direction, 'up') == 0)
            $direction = 'left';
        else if (strcmp($direction, 'left') == 0)
            $direction = 'down';
        else if (strcmp($direction, 'down') == 0)
            $direction = 'right';
        else if (strcmp($direction, 'right') == 0)
            $direction = 'up';
    }
    else if (strcmp($action, 'right') == 0)
    {
        if (strcmp($direction, 'up') == 0)
            $direction = 'right';
        else if (strcmp($direction, 'right') == 0)
            $direction = 'down';
        else if (strcmp($direction, 'down') == 0)
            $direction = 'left';
        else if (strcmp($direction, 'left') == 0)
            $direction = 'up';
    }
    
    $_SESSION['direction'] = $direction;
    
    $xdelta = 0;
    $ydelta = 0;
    $xrightdelta = 0;
    $xleftdelta = 0;
    $yrightdelta = 0;
    $yleftdelta = 0;
    
    $wallcolor = 'red';
    $holecolor = 'blue';
    
    if (strcmp($direction, 'up') == 0)
    {
        $ydelta = -1;
        $xleftdelta = -1;
        $xrightdelta = 1;
    }
    else if (strcmp($direction, 'down') == 0)
    {
        $ydelta = 1;
        $xleftdelta = 1;
        $xrightdelta = -1;
    }
    else if (strcmp($direction, 'left') == 0)
    {
        $xdelta = -1;
        $yleftdelta = 1;
        $yrightdelta = -1;
        $wallcolor = 'blue';
        $holecolor = 'red';
    }
    else // right
    {
        $xdelta = 1;
        $yleftdelta = -1;
        $yrightdelta = 1;
        $wallcolor = 'blue';
        $holecolor = 'red';
    }

    $x = $posx;
    $y = $posy;
    
    $newposx = $x;
    $newposy = $y;
    
    if (strcmp($action, 'forward') == 0)
    {
        if (get_block($maze, $maze_width, $x + $xdelta, $y + $ydelta) != 'X')
        {
            $x += 2*$xdelta;
            $y += 2*$ydelta;
            
            $_SESSION['posx'] = $x;
            $_SESSION['posy'] = $y;
            
            $newposx = $x;
            $newposy = $y;
        }
    }
    
    $ready = false;
    $depth = 0;

    $last_block = get_block($maze, $maze_width, $x, $y);
    while (!$ready)
    {
        $this_block = get_block($maze, $maze_width, $x, $y);
        if ($this_block == 'X')
        {
            $ready = true;
        }
        else
        {
            $depth++;
            $x += $xdelta;
            $y += $ydelta;
            $last_block = $this_block;
        }
    }
    
    $goalx = -1;
    $goaly = -1;
    
    if (isset($_SESSION['goalx'])
        && isset($_SESSION['goaly']))
    {
        $goalx = $_SESSION['goalx'];
        $goaly = $_SESSION['goaly'];
    }
    
    $is_on_goal = false;
    if ($goalx == $newposx
        && $goaly == $newposy) // || true)
    {
        $is_on_goal = true;
        $_SESSION['is_on_goal'] = true;
    }
    else
    {
        $_SESSION['is_on_goal'] = false;
    }
    
    $first = true;
    while ($depth >= 0)
    {
        $this_block = get_block($maze, $maze_width, $x, $y);
        $left_block = get_block($maze, $maze_width, $x + $xleftdelta, $y + $yleftdelta);
        $right_block = get_block($maze, $maze_width, $x + $xrightdelta, $y + $yrightdelta);
        $visited_left = get_block($visited, $maze_width, $x + $xleftdelta, $y + $yleftdelta);
        $visited_right = get_block($visited, $maze_width, $x + $xrightdelta, $y + $yrightdelta);
        
        if ($first)
        {
            draw_front($env, $depth-1, $holecolor);
            if ($is_on_goal)
                draw_coordinates($env, $direction);
            else
            {
                if ($last_block == 'a')
                    draw_koch($env, $depth - 1);
                else if ($last_block == 'b')
                    draw_sierpinski($env, $depth-1);
                else if ($last_block === 'c')
                    draw_comment($env, $depth - 1);
            }
            $first = false;
        }
        else
        {
            if ($this_block == 'E')
            {
                draw_floor($env, $depth, 'lime');
                $visited = set_block($visited, $maze_width, $x, $y, 'E');
            }
            else
            {
                $visited = set_block($visited, $maze_width, $x, $y, ' ');
            }
            
            if ($left_block == 'X')
            {
                draw_left_wall($env, $depth, $wallcolor);
            }
            else
            {
                draw_left_hole($env, $depth, $holecolor);
                if ($visited_left == 'X')
                    $visited = set_block($visited, $maze_width, $x + $xleftdelta, $y + $yleftdelta, '?');
            }
            if ($right_block == 'X')
            {
                draw_right_wall($env, $depth, $wallcolor);
            }
            else
            {
                draw_right_hole($env, $depth, $holecolor);
                if ($visited_right == 'X')
                    $visited = set_block($visited, $maze_width, $x + $xrightdelta, $y + $yrightdelta, '?');
            }
        }
        
        $depth--;
        $x -= $xdelta;
        $y -= $ydelta;
    }
    
    $_SESSION['visited'] = $visited;
    
    draw_direction($env, $direction);
        
    $distance = sqrt(($newposx - $goalx) * ($newposx - $goalx) + ($newposy - $goaly) * ($newposy - $goaly));
    draw_distance($env, (int)($distance * 5));
}

function render_2d_maze(Env3d $env, $maze, $w, $h, $posx, $posy)
{
    $maze_width = $w * 2 + 1;
    $maze_height = $h * 2 + 1;
    
    $tile_width = $env->vp_width / $maze_width;
    $tile_height = $env->vp_height / $maze_height;

    print('<rect x="0" y="0" width="'.$env->vp_width.'" height="'.$env->vp_height.'" style="fill:black" />');
    
    for ($x = 0; $x < $maze_width; ++$x)
    {
        for ($y = 0; $y < $maze_height; ++$y)
        {
            $block_pos = $y * $maze_width + $x;
            $block = $maze[$block_pos];
            
            $color = 'white';
            
            if ($block == 'X')
                continue;
            
            if ($block == 'E')
            {
                $color = 'lime';    
            }
            else if ($x == $posx && $y == $posy)
            {
                $color = 'red';
            }
            else if ($block == '?')
            {
                $color = 'gray';        
            }
            $rx = (int)($x * $tile_width + 0.5);
            $ry = (int)($y * $tile_height + 0.5);
            
            print('<rect x="'.$rx.'" y="'.$ry.'" width="'.((int)($tile_width)+1).'" height="'.((int)($tile_height)+1).'" style="fill:'.$color.'"/>');
        }
    }
}

if (!isset($_SESSION['width'])
    || !isset($_SESSION['height'])
    || !isset($_SESSION['maze'])
    || !isset($_SESSION['posx'])
    || !isset($_SESSION['posy'])
    || !isset($_SESSION['visited'])
    || !isset($_SESSION['direction']))
{
?>
<p><b>Fehlerhafte Initialisierung!</b></p>
<?php
}
else
{
    $w = $_SESSION['width'];
    $h = $_SESSION['height'];
    $maze = $_SESSION['maze'];
    $visited = $_SESSION['visited'];
    $posx = $_SESSION['posx'];
    $posy = $_SESSION['posy'];
    $direction = $_SESSION['direction'];
    
    $pixel_width = 490;
    
    if (isset($_GET['size']))
        $pixel_width = $_GET['size'];
    $pixel_height = $pixel_width;
    
    $env = new Env3d();
    $env->vp_height = $pixel_height;
    $env->vp_width = $pixel_width;
    $env->d = -1.5;
    $env->room_height = 0.4;

    $mode = '';
    if (isset($_GET['action']))
        $mode = $_GET['action'];

    print('<svg height="'.$pixel_height.'" width="'.$pixel_height.'">');
    
    if ($mode != 'map')
    {
        render_maze($env, $maze, $w, $h, $posx, $posy, $direction, $visited);
    }
    else
    {
        render_2d_maze($env, $visited, $w, $h, $posx, $posy);
    }
    ?>
    Ihr Browser kann keine Inline-SVG-Bilder darstellen. Bitte verwenden Sie IE9+, Chrome, Opera, Safari oder Firefox.
    </svg>
<?php
}

session_write_close();
?>