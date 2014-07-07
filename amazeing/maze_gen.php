<?php

class MazePos
{
    public $x;
    public $y;
}


function is_vertical_wall($x, $y)
{
    return ($x % 2 == 1);
}

function is_horizontal_wall($x, $y)
{
    return ($x % 2 == 0);
}

function get_block_index($maze_width, $blockx, $blocky)
{
    $result = (int)($maze_width * $blocky + $blockx);
//    print('get_block_index(w='.$maze_width.', x='.$blockx.', y='.$blocky.') = '.$result.'<br/>');
    return $result;
}

function get_block_x($maze_width, $index)
{
    return (int)$index % $maze_width;
}

function get_block_y($maze_width, $index)
{
    return floor($index / $maze_width);
}

function get_block($maze, $maze_width, $x, $y)
{
//    print('get_block(w='.$maze_width.', x='.$x.', y='.$y.')');
    $result = substr($maze, get_block_index($maze_width, $x, $y), 1);
//    print(' = '.$result.'<br/>');
    return $result;
}

function get_cell($maze, $maze_width, $cellx, $celly)
{
    $x = $cellx * 2 + 1;
    $y = $celly * 2 + 1;

    return get_block($maze, $maze_width, $x, $y);
}

function get_unchecked_walls($maze, $maze_width_cells, $maze_height_cells, $cellx, $celly)
{
    $result = array();
    $blockx = $cellx * 2 + 1;
    $blocky = $celly * 2 + 1;
    $maze_width = $maze_width_cells * 2 + 1;

//    print('get_unchecked_walls('.$cellx.', '.$celly.'): ');    
    if ($cellx < $maze_width_cells - 1)
    {
        // Check wall to the right
        if (get_cell($maze, $maze_width, $cellx + 1, $celly) == 'X')
        {
            $result[] = get_block_index($maze_width, $blockx + 1, $blocky);
//            print('right ');
        }
    }
    
    if ($cellx > 0)
    {
        // Check wall to the left
        if (get_cell($maze, $maze_width, $cellx - 1, $celly) == 'X')
        {
            $result[] = get_block_index($maze_width, $blockx - 1, $blocky);
//            print('left ');
        }
    }
    
    if ($celly < $maze_height_cells - 1)
    {
        // Check wall below
        if (get_cell($maze, $maze_width, $cellx, $celly + 1) == 'X')
        {
            $result[] = get_block_index($maze_width, $blockx, $blocky + 1);
//            print('below ');
        }
    }
    
    if ($celly > 0)
    {
        // Check wall above
        if (get_cell($maze, $maze_width, $cellx, $celly - 1) == 'X')
        {
            $result[] = get_block_index($maze_width, $blockx, $blocky - 1);
//            print('top');
        }
    }
//    print('<br/>');
    return $result;
}

function set_block($maze, $maze_width, $x, $y, $content)
{
    $block_index = get_block_index($maze_width, $x, $y);

    $new_maze = $maze;
    $new_maze[$block_index] = $content;
    //print('new maze: '.$new_maze.'<br>');
    return $new_maze;
}

function dump_maze($maze, $maze_width, $maze_height)
{
    $w = $maze_width * 2 + 1;
    $h = $maze_height * 2 + 1;
    for ($i=0; $i<$h; $i++)
    {
        $line = str_replace('b', '&nbsp;&nbsp;', substr($maze, $i*$w, $w));
        $line = str_replace('a', '&nbsp;&nbsp;', $line);
        $line = str_replace('c', '&nbsp;&nbsp;', $line);
        $line = str_replace(' ', '&nbsp;&nbsp;', $line);
        $line = str_replace('P', '&nbsp;&nbsp;', $line);
        $line = str_replace('X', '<span style="background-color:black;">&nbsp;&nbsp;</span>', $line);
        $line = str_replace('E', '<span style="background-color:green;">&nbsp;&nbsp;</span>', $line);
        $line = str_replace('S', '<span style="background-color:red;">&nbsp;&nbsp;</span>', $line);
        print($line.'<br/>');
    }
    print('<br/>');
}

function index_of($maze, $maze_width, $what)
{
    $where = strpos($maze, $what);
    if ($where === false)
    {
        return $maze_width + 1;   
    }
    return (int)$where;
}


function depth_first_maze($w, $h)
{
    $mw = $w * 2 + 1;
    $mh = $h * 2 + 1;
    
    $startx = rand(0, $w - 1);
    $starty = rand(0, $h - 1);
    $start_blockx = $startx * 2 + 1;
    $start_blocky = $starty * 2 + 1;

    unset($maze);
    // Mark the start block
    $maze = set_block(str_repeat("X", $mw * $mh), $mw, $start_blockx, $start_blocky, 'S');
    
    return depth_first_maze_internal($maze, $w, $h, $startx, $starty);
}

function depth_first_maze_internal($maze, $w, $h, $startx, $starty)
{
    $mw = $w * 2 + 1;
    $mh = $h * 2 + 1;
    
    $start_blockx = $startx * 2 + 1;
    $start_blocky = $starty * 2 + 1;
    
    
    $stack_item = implode(';', array(get_block_index($mw, $start_blockx, $start_blocky), 1));
    $cellstack = array($stack_item);
    
    $max_depth = 0;
    $max_cellx = 0;
    $max_celly = 0;
    
    while (count($cellstack) > 0)
    {
        // Pop last
        $cell_stack_index = count($cellstack) - 1;
        $cell_data = $cellstack[$cell_stack_index];
        
//        var_dump($cell_data);
        $cell_data_array = explode(';', $cell_data);
//        var_dump($cell_data_array);
        $cell_index = (int)$cell_data_array[0];
        $depth = (int)$cell_data_array[1];
        
        unset($cellstack[$cell_stack_index]);
        $cellstack = array_values($cellstack);
        
        $cell_blockx = get_block_x($mw, $cell_index);
        $cell_blocky = get_block_y($mw, $cell_index);
        
        $cellx = (int)floor($cell_blockx / 2);
        $celly = (int)floor($cell_blocky / 2);
        
        $possible_moves = get_unchecked_walls($maze, $w, $h, $cellx, $celly);
        
        $move_count = count($possible_moves);
        if ($move_count > 0)
        {
            // Pick a random move
            $move_index = rand(0, $move_count - 1);
            $move_block_index = (int) $possible_moves[$move_index];
            
            // Did we have more than one possible move?
            if ($move_count > 1)
            {
                // Then remember this cell for later
                $stack_item = implode(';', array($cell_index, $depth));
                $cellstack[] = $stack_item;
            }
            
            $wallx = get_block_x($mw, $move_block_index);
            $wally = get_block_y($mw, $move_block_index);
            
            $next_cell_blockx = -1;
            $next_cell_blocky = -1;
            
            $maze = set_block($maze, $mw, $wallx, $wally, ' ');
            
            if ($wallx > $cell_blockx)
            {
                // Move right
                $next_cell_blockx = $wallx + 1;
                $next_cell_blocky = $wally;
            }
            else if ($wallx < $cell_blockx)
            {
                // Move left
                $next_cell_blockx = $wallx - 1;
                $next_cell_blocky = $wally;
            }
            else if ($wally < $cell_blocky)
            {
                // Move up
                $next_cell_blockx = $wallx;
                $next_cell_blocky = $wally - 1;
            }
            else if ($wally > $cell_blocky)
            {
                // Move down
                $next_cell_blockx = $wallx;
                $next_cell_blocky = $wally + 1;
            }
            
            $maze = set_block($maze, $mw, $next_cell_blockx, $next_cell_blocky, ' ');

            $depth++;
            
            if ($depth > $max_depth)
            {
                $max_depth = $depth;
                $max_cellx = $next_cell_blockx;
                $max_celly = $next_cell_blocky;
            }
            
            $stack_item = implode(';', array(get_block_index($mw, $next_cell_blockx, $next_cell_blocky), $depth));
            $cellstack[] = $stack_item;
        }
        else
        {
            // Nothing to do for this cell
        }
    }
    
    // Mark the exit block
    $maze = set_block($maze, $mw, $max_cellx, $max_celly, 'E');
    
    return $maze;
}

function prim_maze($w, $h)
{
    $mw = $w * 2 + 1;
    $mh = $h * 2 + 1;

    unset($maze);
    //$maze = set_block(str_repeat("X", $mw * $mh), $mw, $start_blockx, $start_blocky, 'S');
    $maze = str_repeat("X", $mw * $mh);
    //$maze = tattoo_with_coords("E699", $maze, $w, $h);
    
    $startx = 0;
    $starty = 0;
    $start_blockx = 0;
    $start_blocky = 0;
    
    do
    {
        $startx = rand(0, $w - 1);
        $starty = rand(0, $h - 1);
        $start_blockx = $startx * 2 + 1;
        $start_blocky = $starty * 2 + 1;
    }
    while (get_block($maze, $mw, $start_blockx, $start_blocky) != 'X');
    
    $maze = set_block($maze, $mw, $start_blockx, $start_blocky, 'S');

    $maze = prim_maze_internal($maze, $w, $h, $startx, $starty);
    
    $entrance_to_coords = index_of($maze, $mw, 'P');
    $entr_x = get_block_x($mw, $entrance_to_coords);
    $entr_y = get_block_y($mw, $entrance_to_coords);
    
    //print('Entrance: '.$entr_x.', '.$entr_y.'<br>');
    
    $maze = set_block($maze, $mw, $entr_x, $entr_y, ' ');
    $maze = set_block($maze, $mw, $entr_x, $entr_y + 1, ' ');
    
    return $maze;
}

function prim_maze_internal($maze, $w, $h, $startx, $starty)
{
    $mw = $w * 2 + 1;
    $mh = $h * 2 + 1;
    
    unset($wallstack);
    $initial_walls = get_unchecked_walls($maze, $w, $h, $startx, $starty);
    $wallstack = array();
    for($i=0; $i<count($initial_walls); ++$i)
    {
        $wall = (int)$initial_walls[$i];
        $stack_item = implode(';', array($wall, 1));
        $wallstack[] = $stack_item;
    }

    $max_depth = 0;
    $max_cellx = 0;
    $max_celly = 0;
    
    while (count($wallstack) > 0)
    {
                 
    //    var_dump($wallstack);
    //    print('<br/>');
    //    dump_maze($maze, $mw, $mh);
        
        $random_wall_index = rand(1, count($wallstack)) - 1;
        $random_wall_data = $wallstack[$random_wall_index];
        
        $wall_data_array = explode(';', $random_wall_data);
        $random_wall = (int)$wall_data_array[0];
        $depth = (int)$wall_data_array[1];
        
        unset($wallstack[$random_wall_index]);
        $wallstack = array_values($wallstack);
        
        $wallx = get_block_x($mw, $random_wall);
        $wally = get_block_y($mw, $random_wall);
        
    //    print('Wall: '.$random_wall.' ==> ('.$wallx.', '.$wally.')<br/>');
        
        $cell_blockx = -1;
        $cell_blocky = -1;
        
        if (is_horizontal_wall($wallx, $wally))
        {
            // Check to the left
            if (get_block($maze, $mw, $wallx - 1, $wally) == 'X')
            {
                // Unvisited
                $cell_blockx = $wallx - 1;
                $cell_blocky = $wally;
            }
            else if (get_block($maze, $mw, $wallx + 1, $wally) == 'X')
            {
                // Unvisited to the right
                $cell_blockx = $wallx + 1;
                $cell_blocky = $wally;
            }
        }
        else // if (is_vertical_wall($wallx, $wally))
        {
            // Check top
            if (get_block($maze, $mw, $wallx, $wally - 1) == 'X')
            {
                // Unvisited
                $cell_blockx = $wallx;
                $cell_blocky = $wally - 1;
            }
            else if (get_block($maze, $mw, $wallx, $wally + 1) == 'X')
            {
                // Unvisited below
                $cell_blockx = $wallx;
                $cell_blocky = $wally + 1;
            }
        }
        
        if ($cell_blockx != -1)
        {
    //        print('Found cell block ('.$cell_blockx.', '.$cell_blocky.')<br/>');
    //        print('Remove wall:<br>');
            $maze = set_block($maze, $mw, $wallx, $wally, ' ');
    //        dump_maze($maze, $mw, $mh);
            $maze = set_block($maze, $mw, $cell_blockx, $cell_blocky, ' ');
    //        print('Remove cell:<br>');
    //        dump_maze($maze, $mw, $mh);
            
            $cellx = floor($cell_blockx / 2);
            $celly = floor($cell_blocky / 2);
            
    //        print('Next cell: ('.$cellx.', '.$celly.')<br/>');
            
            $depth++;
            
            if ($depth > $max_depth)
            {
                $max_depth = $depth;
                $max_cellx = $cell_blockx;
                $max_celly = $cell_blocky;
            }
            
            $unchecked_walls = get_unchecked_walls($maze, $w, $h, $cellx, $celly);
            for($i=0; $i<count($unchecked_walls); ++$i)
            {
                $wall = (int)$unchecked_walls[$i];
                $stack_item = implode(';', array($wall, $depth));
                $wallstack[] = $stack_item;
            }
            
            //$wallstack = array_merge($wallstack, get_unchecked_walls($maze, $w, $h, $cellx, $celly));
            //$wallstack[] = $stack_item;
        }
        else
        {
    //        print('Skipped wall.<br/>');
        }
    }
    
    // Mark the start block
    $maze = set_block($maze, $mw, $max_cellx, $max_celly, 'E');
    
    return $maze;
}

function mark_hints($maze, $w, $h)
{
    $mw = 2 * $w + 1;
    $mh = 2 * $h + 1;
    
    $candidates = array();
    
    for ($x = 0; $x < $w; ++$x)
    {
        for ($y = 0; $y < $h; ++$y)
        {
            $xx = 2 * $x + 1;
            $yy = 2 * $y + 1;
            
            if (get_block($maze, $mw, $xx, $yy) != ' ')
                continue;
            
            $left = get_block($maze, $mw, $xx - 1, $yy);
            $right = get_block($maze, $mw, $xx + 1, $yy);
            $top = get_block($maze, $mw, $xx, $yy - 1);
            $bottom = get_block($maze, $mw, $xx, $yy + 1);
            
            $wallcount = 0;
            if ($left == 'X')
                $wallcount++;
            if ($right == 'X')
                $wallcount++;
            if ($top == 'X')
                $wallcount++;
            if ($bottom == 'X')
                $wallcount++;
                
            if ($wallcount == 3)
            {
                $pos = new MazePos();
                $pos->x = $xx;
                $pos->y = $yy;
                $candidates[] = $pos;
            }
        }
    }
    
    //foreach ($candidates as $cand)
    //{
    //   print('Candidate: ('.$cand->x.', '.$cand->y.')<br>');
    //}
    
    $things = array('a', 'b', 'c');
    $thing_count = 2;
    
    $size = count($things);
    $cand_size = count($candidates);
    
    while ($cand_size < ($thing_count * $size))
        $thing_count--;

    //print('Thing count: '.$size);
    
    $new_maze = $maze;
    
    foreach ($things as $thing)
    {
        for ($i = 0; $i < $thing_count; ++$i)
        {
            $found = false;
            
            while (!$found)
            {
                $pos = $candidates[rand(0, $cand_size - 1)];
                
                //print('Checking '.$pos->x.', '.$pos->y.'<br>');
                
                if (get_block($new_maze, $mw, $pos->x, $pos->y) != ' ')
                    continue;
                
                $found = true;
                
                $new_maze = set_block($new_maze, $mw, $pos->x, $pos->y, $thing);
            }        
        }
    }
    return $new_maze;
}

function tattoo_with_coords($coords, $maze, $w, $h)
{
    $length = strlen($coords);
    
    $mw = 2 * $w + 1;
    $mh = 2 * $h + 1;
    
    $needed_width = 4 * $length + 2;
    $needed_height = 7; // 5 + 2
    
    if ($needed_width > ($mw + 4)
        || $needed_height > ($mh + 4))
        return $maze;
    
    $startx = rand(1, $mw - $needed_width - 2);
    if ($startx % 2 == 0)
        $startx--;
    $starty = rand(1, $mh - $needed_height - 4);
    if ($starty % 2 == 0)
        $starty--;
    
    $figures = array(
        "E" => array("   ", " XX", "   ", " XX", "   ", "XX "),
        "0" => array("   ", " X ", " X ", " X ", "   ", "XX "),
        "1" => array("XX ", "XX ", "XX ", "XX ", "XX ", "XX "),
        "2" => array("   ", "XX ", "   ", " XX", "   ", "XX "),
        "3" => array("   ", "XX ", "   ", "XX ", "   ", "XX "),
        "4" => array(" X ", " X ", "   ", "XX ", "XX ", "XX "),
        "5" => array("   ", " XX", "   ", "XX ", "   ", "XX "),
        "6" => array("   ", " XX", "   ", " X ", "   ", "XX "),
        "7" => array("   ", "XX ", "XX ", "XX ", "XX ", "XX "),
        "8" => array("   ", " X ", "   ", " X ", "   ", "XX "),
        "9" => array("   ", " X ", "   ", "XX ", "   ", "XX ")
    );
        
    for ($i = 0; $i < $length; ++$i)
    {
        $innerx = $startx + 2 + $i * 4;
        $innery = $starty + 2;
        
        $char_arr = $figures[$coords[$i]];
        for ($j = 0; $j < count($char_arr); ++$j)
        {
            $maze = set_block($maze, $mw, $innerx, $innery + $j, $char_arr[$j][0]);
            $maze = set_block($maze, $mw, $innerx + 1, $innery + $j, $char_arr[$j][1]);
            $maze = set_block($maze, $mw, $innerx + 2, $innery + $j, $char_arr[$j][2]);
        }
    }
    
    for ($x = $startx; $x < $startx + $needed_width + 1; ++$x)
    {
        $maze = set_block($maze, $mw, $x, $starty, ' ');
        $maze = set_block($maze, $mw, $x, $starty + $needed_height + 1, ' ');
    }
    
    for ($y = $starty; $y < $starty + $needed_height + 1; ++$y)
    {
        $maze = set_block($maze, $mw, $startx, $y, ' ');
        $maze = set_block($maze, $mw, $startx + $needed_width, $y, ' ');
    }
    
    // Mark lower center
    $maze = set_block($maze, $mw, $startx + 2 * ((int)($needed_width / 4)), $starty + $needed_height + 1, 'P');
    
    return $maze;
}

function empty_maze($w, $h)
{
    $mw = $w * 2 + 1;
    $mh = $h * 2 + 1;

    $maze = str_repeat("X", $mw * $mh);
    return $maze;
}
?>