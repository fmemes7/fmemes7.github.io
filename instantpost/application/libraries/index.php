<?php

set_time_limit(0);
date_default_timezone_set('UTC');

require 'vendor/autoload.php';

/////// CONFIG ///////
$username = 'shahrafihossain';
$password = 'c0mm0n01670';
$debug = true;
$truncatedDebug = false;
//////////////////////

/////// MEDIA ////////
$photoFilename = 'man.jpg';
$captionText = '';
//////////////////////

$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);

try {
    $user_id = $ig->login($username, $password);
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
    exit(0);
}
$user_id = json_decode($user_id);
echo "<pre>";
print_r($user_id);
echo "</pre>";
die();

try {
    // The most basic upload command, if you're sure that your photo file is
    // valid on Instagram (that it fits all requirements), is the following:
    // $ig->timeline->uploadPhoto($photoFilename, ['caption' => $captionText]);

    // However, if you want to guarantee that the file is valid (correct format,
    // width, height and aspect ratio), then you can run it through our
    // automatic photo processing class. It is pretty fast, and only does any
    // work when the input file is invalid, so you may want to always use it.
    // You have nothing to worry about, since the class uses temporary files if
    // the input needs processing, and it never overwrites your original file.
    //
    // Also note that it has lots of options, so read its class documentation!
    $photo = new \InstagramAPI\Media\Photo\InstagramPhoto($photoFilename);
    $ig->timeline->uploadPhoto($photo->getFile(), ['caption' => $captionText]);
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
}
