<?php

/**
 * Small to use example for sending simple RF code
 */

require('classes/TimeFrame.php');
require('classes/RFSender.php');

$config = array(
	'lamps' => array(
		'A' => array('on' => '4262927', 'off' => '4262926'),
		'B' => array('on' => '4262925', 'off' => '4262924'),
		'C' => array('on' => '4262923', 'off' => '4262922'),
		'ALL' => array('on' => '4262914', 'off' => '4262913')
	),
	'utils' => 'lamp/send.sh'
);

$sender = new RFSender($config);

// Turn off lamps if sunset time is reached

$timeframe = new TimeFrame();

$sunsetTime = date_sunset(time(), SUNFUNCS_RET_STRING, 52.1, 5, 90, 1);

echo $sunsetTime;

if ($timeframe->isTimeReached($sunsetTime, 40))
{
	$sender->setLamp('ALL', 'off');
}
