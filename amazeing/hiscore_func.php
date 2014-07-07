<?php
require_once('../connection_string.php');

class HiscoreItem
{
    public $name;
    public $time;
    public $steps;
}

class AmazeingStats
{
    public $playCount;
    public $correctCount;
    public $wrongCount;
}

function reset_hiscore_img()
{
    // Reset hiscore image
    if (file_exists('hiscore.png'))
        unlink('hiscore.png');
}

function cleanup_hiscore()
{
    $db = get_db();
    
    $sql = 'select gametime from amazeing_highscore order by gametime limit 1 offset 250';

    $gametime = '23:59:59';
    $has_records = false;    
    foreach ($db->query($sql) as $row)
    {
        $gametime = $row['gametime'];
        $has_records = true;
        break;
    }
    
    if (!$has_records)
        return;

    $sql = "delete from amazeing_highscore where gametime >= '".$gametime."'";
    
    $db->exec($sql);
}

function enter_hiscore($name, $hours, $minutes, $seconds)
{
    $esc_name = pg_escape_string($name);
    
    if ($hours > 24)
    {
        $hours = 23;
        $minutes = 59;
        $seconds = 59;
    }
    
    $gametime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    $gamedate = date('Y-m-d H:i:s');
    
    $sql = "insert into amazeing_highscore (gametime, gamedate, playername) values ('".$gametime."', '".$gamedate."', '".$esc_name."');";
    
    $db = get_db();
    $db->exec($sql);
    
    cleanup_hiscore();
    reset_hiscore_img();
}

function get_hiscores()
{
    //$sql = 'select playername, gametime from amazeing_highscore  order by gametime limit 5';
    $sql = 'select min(gametime) as min_time, playername from amazeing_highscore group by playername order by min_time limit 5';
    
    $db = get_db();
    
    $hiscoreitems = array();
    
    foreach ($db->query($sql) as $row)
    {
        $gametime = $row['min_time'];
        $playername = $row['playername'];
        
        $item = new HiscoreItem();
        $item->name = $playername;
        $item->time = ''.$gametime;
        
        $hiscoreitems[] = $item;
    }
    
    return $hiscoreitems;
}

function get_stats()
{
    $stats = new AmazeingStats();
    $stats->correctCount = 0;
    $stats->playCount = 0;
    $stats->wrongCount = 0;
    
    $sql = "select page_id, hits from hit_counter where page_id like 'amazeing_%'";
    $db = get_db();
    
    foreach ($db->query($sql) as $row)
    {
        $page_id = $row['page_id'];
        $hits = (int)$row['hits'];
        
        if (strcmp($page_id, 'amazeing_games') == 0)
            $stats->playCount = $hits;
        else if (strcmp($page_id, 'amazeing_wrong') == 0)
            $stats->wrongCount = $hits;
        else if (strcmp($page_id, 'amazeing_correct') == 0)
            $stats->correctCount = $hits;
    }
    
    return $stats;
}

?>