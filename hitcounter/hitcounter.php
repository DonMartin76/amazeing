<?php

require_once('../connection_string.php');

function get_hit_count($page_id)
{
    $db = get_db();
    
    $query = 'select * from hit_counter where page_id=\''.$page_id.'\'';
    // print($query);
    $has_records = false;
    $hit_count = 0;
    foreach ($db->query($query) as $row)
    {
        $hit_count = (int)($row['hits']);
        $has_records = true;
        break;
    }
    
    $hit_count++;
    
    $upsert = '';
    $last_access = date('Y-m-d G:i:s');
    if ($has_records)
    {
        $upsert = 'update hit_counter set hits='.$hit_count.', last_access=\''.$last_access.'\' where page_id=\''.$page_id.'\'';
    }
    else
    {
        $upsert = 'insert into hit_counter (page_id, hits, last_access) values (\''.$page_id.'\', '.$hit_count.', \''.$last_access.'\')';
    }
    //print($upsert);
    $db->exec($upsert);
    
    return $hit_count;
}

?>