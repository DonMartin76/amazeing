<?php
global $redis_connection;
global $use_redis;

if (!isset($use_redis))
{
    $services_json = json_decode(getenv("VCAP_SERVICES"),true);
    if (isset($services_json["redis-2.6"]))
    {
        $redis_config = $services_json["redis-2.6"][0]["credentials"];
        $name = $redis_config["name"];
        $password = $redis_config["password"];
        $hostname = $redis_config["hostname"];
        $port = $redis_config["port"];
        
        $redis_connection = 'tcp://'.$hostname.':'.$port.'?auth='.$password;
        $use_redis = true;
    }
    else
    {
        $user_redis = false;
    }
}

if ($use_redis)
{
    ini_set('session.save_handler', 'redis');
    ini_set('session.save_path',    $redis_connection);
}
?>
