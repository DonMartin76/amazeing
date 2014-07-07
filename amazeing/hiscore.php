<?php
//<html><head><title>Test</title></head>
//    <body>

require('hiscore_func.php');
if (!file_exists('hiscore.png'))
{
    $height = 150;
    $width = 500;
    
    //$img = imagecreatetruecolor($width, $height);
    $base_file = realpath(dirname(__FILE__).'/naked_frame.png');
    //print('base file: '.$base_file.'<br>');
    $img = imagecreatefrompng($base_file);
    
    // White background and blue text
    $bg = imagecolorallocate($img, 255, 255, 255);
    $index_color = imagecolorallocate($img, 0, 0, 255);
    $text_color = imagecolorallocate($img, 255, 0, 0);
    
    $games_color = imagecolorallocate($img, 0, 0, 0);
    $correct_color = imagecolorallocate($img, 0, 192, 0);
    $wrong_color = imagecolorallocate($img, 255, 0, 0);
    
    $names = get_hiscores();
    $stats = get_stats(); // AmazeingStats
    
    $name_count = count($names);
    
    $line_height = 32;
    $index_off = 110;
    $name_off = 140;
    $time_off = 450;
    $y_off = 60;
    
    $correct_off = 275;
    $wrong_off = 450;
    
    //imagefilledrectangle($img, 0, 0, $width, $height, $bg);
    
    //$font = realpath(dirname(__FILE__).'/lsans.ttf');
    
    //print('Path: '.$font.'<br>');
    
    for ($i = 0; $i < 5; ++$i)
    {
        $index = $i + 1;
        
        $name = 'n/a';
        $time = 'n/a';
        
        if ($i < $name_count)
        {
            $name = $names[$i]->name;
            $time = $names[$i]->time;
        }
        $y = $y_off + $index * $line_height;
        
        imagestring($img, 4, $index_off, $y, $index.'.', $index_color);
        imagestring($img, 4, $name_off, $y, $name, $text_color);
        imagestring($img, 4, $time_off, $y, $time, $index_color);
    //    imagettftext($img, 14, 0, $index_off, $y, $index_color, $font, $index.'.');
    //    imagettftext($img, 14, 0, $name_off, $y, $text_color, $font, $name);
    //    imagettftext($img, 14, 0, $time_off, $y, $index_color, $font, $time);
    }
    
    $baseline = $y_off + 6.2 * $line_height;
    
    imagestring($img, 5, $index_off, $baseline, 'Spiele: '.$stats->playCount, $games_color);
    imagestring($img, 5, $correct_off, $baseline, 'Beendet: '.$stats->correctCount, $correct_color);
    //imagestring($img, 5, $wrong_off, $baseline, 'Falsch: '.$stats->wrongCount, $wrong_color);
    
    imagepng($img, 'hiscore.png');
    imagedestroy($img);
}
    
// Output the image
header('Content-type: image/png');

readfile('hiscore.png');
//</body>
//</html>
?>
