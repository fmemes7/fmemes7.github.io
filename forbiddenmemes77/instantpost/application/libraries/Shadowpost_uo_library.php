<?php
require_once('vendor/autoload.php');
class Shadowpost_uo_library
{
	public $dbId="";
	public $fb="";
	public $userId="";
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->helper('ambitious_helper');
		$this->CI->load->model('common');
		$this->CI->load->helper('url_helper');
	}
	public function igLogin($username="", $password="", $proxy="")
	{
		$username = $username;
		$password = $password;
		$debug = false;
		$truncatedDebug = false;
		$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
		try {
			if($proxy != "") {
				$ig->setProxy($proxy);
			}
		    $respnse = $ig->login($username, $password);
		} catch (\Exception $e) {
		    $respnse = $e->getMessage();
		    exit(0);
		}
		return $respnse;
	}
	public function videoPost($username="", $password="", $proxy="",$message="",$video_url)
	{
		$username = $username;
		$password = $password;
		$debug = false;
		$truncatedDebug = false;
		/////// MEDIA ////////
		$videoFilename = $video_url;
		$captionText = $message;
		//////////////////////
		$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
		try {
			if($proxy != "") {
				$ig->setProxy($proxy);
			}
		    $ig->login($username, $password);
		} catch (\Exception $e) {
		    $respnse = $e->getMessage();
		    exit(0);
		}
		try {
		    $video = new \InstagramAPI\Media\Video\InstagramVideo($videoFilename);
		    $respnse = $ig->timeline->uploadVideo($video->getFile(), ['caption' => $captionText]);
		} catch (\Exception $e) {
		    $respnse = $e->getMessage();
		}
		return $respnse;
	}
	public function getOwnUserFeed($username="", $password="", $proxy="")
	{
		$username = $username;
		$password = $password;
		$debug = false;
		$truncatedDebug = false;
		$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
		try {
			if($proxy != "") {
				$ig->setProxy($proxy);
			}
		    $login_check = $ig->login($username, $password);
		} catch (\Exception $e) {
		    $respnse = $e->getMessage();
		    exit(0);
		}
		try {
		    $respnse = $ig->timeline->getSelfUserFeed();
		} catch (\Exception $e) {
		    $respnse = $e->getMessage();
		}
		return $respnse;
	}
	public function getOwnInfo($username="", $password="", $proxy="")
	{
		$username = $username;
		$password = $password;
		$debug = false;
		$truncatedDebug = false;
		$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
		try {
			if($proxy != "") {
				$ig->setProxy($proxy);
			}
		    $login_check = $ig->login($username, $password);
		} catch (\Exception $e) {
		    $respnse = $e->getMessage();
		    exit(0);
		}
		try {
		    $respnse = $ig->people->getSelfInfo();
		} catch (\Exception $e) {
		    $respnse = $e->getMessage();
		}
		return $respnse;
	}
	public function imagePost($username="", $password="", $proxy="",$message="",$image_url)
	{
		$username = $username;
		$password = $password;
		$debug = false;
		$truncatedDebug = false;
		/////// MEDIA ////////
		$photoFilename = $image_url;
		$captionText = $message;
		//////////////////////
		$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
		try {
			if($proxy != "") {
				$ig->setProxy($proxy);
			}
		    $login_check = $ig->login($username, $password);
		} catch (\Exception $e) {
		    $respnse = $e->getMessage();
		    exit(0);
		}
		try {
    		$photo = new \InstagramAPI\Media\Photo\InstagramPhoto($photoFilename);
    		$respnse = $ig->timeline->uploadPhoto($photo->getFile(), ['caption' => $captionText]);
		} catch (\Exception $e) {
    		$respnse = $e->getMessage();
		}
		return $respnse;
	}
	public function storyPost($username = "",$password = "",$proxy = "",$message = "",$image_url = "")
	{
		$username = $username;
		$password = $password;
		$debug = false;
		$truncatedDebug = false;
		/////// MEDIA ////////
		$photoFilename = $image_url;
		$captionText = $message;
		//////////////////////
		$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
		try {
			if($proxy != "") {
				$ig->setProxy($proxy);
			}
		    $ig->login($username, $password);
		} catch (\Exception $e) {
		    $respnse = $e->getMessage();
		    exit(0);
		}
		// Now create the metadata array:
		$metadata = [ 'caption'  => $captionText];
		try {
    		$photo = new \InstagramAPI\Media\Photo\InstagramPhoto($photoFilename, ['targetFeed' => \InstagramAPI\Constants::FEED_STORY]);
    		$respnse = $ig->story->uploadPhoto($photo->getFile(), $metadata);
		} catch (\Exception $e) {
    		$respnse = $e->getMessage();
		}
		return $respnse;
	}
	public function storyPollPost($username = "",$password = "",$proxy = "",$image_url = "", $option_one="", $option_two="")
	{
		/////// CONFIG ///////
		$username = $username;
		$password = $password;
		$debug = false;
		$truncatedDebug = false;
		//////////////////////
		/////// MEDIA ////////
		$photoFilename = $image_url;
		//////////////////////
		$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);
		try {
			if($proxy != "") {
				$ig->setProxy($proxy);
			}
		    $ig->login($username, $password);
		} catch (\Exception $e) {
		    $respnse = $e->getMessage();
		    exit(0);
		}
		// Now create the metadata array:
		$metadata = [
		    'story_polls' => [
		        // Note that you can only do one story poll in this array.
		        [
		            'question'         => 'Is this API great?', // Story poll question. You need to manually to draw it on top of your image.
		            'viewer_vote'      => 0, // Don't change this value.
		            'viewer_can_vote'  => true, // Don't change this value.
		            'tallies'          => [
		                [
		                    'text'      => $option_one, // Answer 1.
		                    'count'     => 0, // Don't change this value.
		                    'font_size' => 35.0, // Range: 17.5 - 35.0.
		                ],
		                [
		                    'text'      => $option_two, // Answer 2.
		                    'count'     => 0, // Don't change this value.
		                    'font_size' => 27.5, // Range: 17.5 - 35.0.
		                ],
		            ],
		            'x'                => 0.5, // Range: 0.0 - 1.0. Note that x = 0.5 and y = 0.5 is center of screen.
		            'y'                => 0.5, // Also note that X/Y is setting the position of the CENTER of the clickable area.
		            'width'            => 0.5661107, // Clickable area size, as percentage of image size: 0.0 - 1.0
		            'height'           => 0.10647108, // ...
		            'rotation'         => 0.0,
		            'is_sticker'       => true, // Don't change this value.
		        ],
		    ],
		];
		try {
		    $photo = new \InstagramAPI\Media\Photo\InstagramPhoto($photoFilename, ['targetFeed' => \InstagramAPI\Constants::FEED_STORY]);
		    $respnse =  $ig->story->uploadPhoto($photo->getFile(), $metadata);
		} catch (\Exception $e) {
		    $respnse = $e->getMessage()."\n";
		}
		return $respnse;
	}
}