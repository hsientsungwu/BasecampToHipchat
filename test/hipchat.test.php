<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$hipchatSetting = new HipchatSetting();

$hc = new HipChat($hipchatSetting->token);

// list rooms
foreach ($hc->get_rooms() as $room) {
  echo " - $room->room_id = $room->name\n";
}

$room_id = $hipchatSetting->room;
$message = "Testing message from Steve";
$notify = true;
$from = "Basecamp Todo";

$hc->message_room($room_id, $from, $message, $notify);
