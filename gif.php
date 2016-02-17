<?php

// Settings & includes

include 'GIFEncoder.class.php';
include 'php52-fix.php';


// $time = $_GET['time']; // Future Time
// $future_date = new DateTime(date('r',strtotime($time))); // future time formatted
// $time_now = time(); // Current time
// $now = new DateTime(date('r', $time_now)); // Current time formated


$frames = array();
$delays = array();

/* =========                    =========
========= Deal with the data =========
=========                    ========= */

function _fetch(){ // For if you want to use an API, uncomment and modify URL
    $ch = curl_init();
    // curl_setopt($ch, CURLOPT_URL, 'http://api-url');
    // curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // $response = curl_exec($ch);
    // curl_close($ch);
    // if (FALSE === $response) {
    //     $response = array();
    // }
    // return json_decode($response, true);
}


// Calc numbers for total and current based on the hour
// $data = _fetch(); Uncomment if using API
$data = [ // Sample data, for if not using API
  "total" => "40000",
  "current" => [100,200,300,400,500,600,700,800,900,1000,1100,1200,1300,1400,1500,1600,1700,1800,1900,20000,21000,22000,23000,24000]
 ]

$yesterday    = $data['total']['value'];
$hours        = $data['current']['values']; // hourly data
$current_hour = intval(date('H')); // What time is it

$next_hour  = ($current_hour == 23) ? 0 : $current_hour + 1; // Reset at midnight
$seconds    = date('s', time()) + (date('i', time()) * 60); // Seconds elapsed
$hourlydata = $hours[$next_hour] - $hours[$current_hour]; // Calc the data left for each hour

// Your image link
$image = imagecreatefrompng('images/background-3.png');
$delay = 100; // milliseconds

$font = array(
    'size' => 55, // Font size, in pts usually.
    'angle' => 0, // Angle of the text
    'x-offset' => 5, // The larger the number the further the distance from the left hand side, 0 to align to the left.
    'y-offset' => 53, // The vertical alignment, trial and error between 20 and 60.
    'file' => './helvetican-bold.ttf', // Font path upload your own to make file function
    'color' => imagecolorallocate($image, 255, 255, 255)
);


/* =========                 =========
=========   Position Text =========
=========                 ========= */
$text       = number_format($total - $hours[$current_hour]); // approxomate calc for sizing
$dimensions = imagettfbbox($font['size'], $font['angle'], $font['file'], $text);
$textWidth  = abs($dimensions[4] - $dimensions[0]); // calc text width
$imgsize    = imagesx($image); // calc image width
//  $x = ($imgsize - $textWidth) - $font['x-offset']; // align right, and offset
$x          = ($imgsize - $textWidth) / 2;


/* =========                 =========
========= Apply the Data  =========
=========                 ========= */
for ($i = 0; $i <= 60; $i++) {
    $image = imagecreatefrompng('images/background-3.png'); // refresh the image at each step
    $font['color'] == imagecolorallocate($image, 255, 255, 255); // Reset the text color too

    $progress     = $seconds / 3600; // Seconds in an hour
    $rollingtotal = $hourlydata * $progress;
    $interval     = abs($total - ($rollingtotal + $hours[$current_hour])); // incase we beat the total, make the number positive
    $text         = number_format($interval); // Format the interval

    if ($total < $rollingtotal) { // we beat the total
        imagettftext($image, $font['size'], $font['angle'], $x, $font['y-offset'], $font['color'], $font['file'], $text);
        ob_start();
        imagegif($image);
        $frames[] = ob_get_contents();
        $delays[] = $delay;
        $loops    = 1;
        ob_end_clean();
        break;
    } else { // still beating it
        imagettftext($image, $font['size'], $font['angle'], $x, $font['y-offset'], $font['color'], $font['file'], $text);
        ob_start();
        imagegif($image); // let's gif this
        $frames[] = ob_get_contents();
        $delays[] = $delay;
        $loops    = 0;
        ob_end_clean();
    }
    $seconds++;
}

// //expire this image instantly
// header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
// header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
// header( 'Cache-Control: no-store, no-cache, must-revalidate' );
// header( 'Cache-Control: post-check=0, pre-check=0', false );
// header( 'Pragma: no-cache' );
$gif = new AnimatedGif($frames, $delays, $loops);
$img = 'download/countdown.gif';
// var_dump($gif);


file_put_contents($img, $gif->getAnimation());

// Use this if you just want to use the script
// $gif->display();
