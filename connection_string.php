<?php

function get_connection_string()
{
    $services_json = json_decode(getenv("VCAP_SERVICES"),true);
    $pgsql_config = $services_json["postgresql-9.1"][0]["credentials"];
    $username = $pgsql_config["username"];
    $password = $pgsql_config["password"];
    $hostname = $pgsql_config["hostname"];
    $port = $pgsql_config["port"];
    $db = $pgsql_config["name"];
    
    return 'pgsql:dbname='.$db.';user='.$username.';password='.$password.';host='.$hostname.';port='.$port;
}

global $donmartin76_db;

function get_db()
{
    if (isset($donmartin76_db))
        return $donmartin76_db;
    
    $connection_string = get_connection_string();
    
    $donmartin76_db = new PDO($connection_string);

    check_schema($donmartin76_db);
    
    return $donmartin76_db;
}

function check_schema($db)
{
    $testsql = 'select * from hit_counter limit 5';
    
    $failed = false;
    $needs_setup = false;
    $success = false;
    
    $recs = $db->query($testsql);
    if ($recs == false)
	$needs_setup = true;

    if ($needs_setup)
    {
	try
	{
	    setup_amazeing_schema();
	    $success = true;
	}
	catch (Exception $e2)
	{
	    $failed = true;
	}
    }
    else
    {
	$success = true;
    }
    
    return $success;
}

function setup_amazeing_schema()
{
    $db = get_db();

    $db->exec('CREATE TABLE hit_counter (   id int4 , page_id varchar , hits int4 , last_access timestamp)');
    $db->exec('CREATE INDEX idx_hit_counter ON hit_counter USING btree (page_id)');

    $db->exec('CREATE TABLE amazeing_highscore (   id int4 , gametime interval , gamedate timestamp , stepsneeded int4 , playername varchar)');
    $db->exec('CREATE INDEX idx_date ON amazeing_highscore USING btree (gamedate)');
    $db->exec('CREATE INDEX idx_time ON amazeing_highscore USING btree (gametime)');
    $db->exec('CREATE INDEX idx_steps ON amazeing_highscore USING btree (stepsneeded, gametime)');
  
    $db->exec($sql);  
}

?>