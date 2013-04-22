<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$hipchatSetting = new HipchatSetting();

$hc = new HipChat($hipchatSetting->token);

// list rooms
foreach ($hc->get_rooms() as $room) {
  echo " - $room->room_id = $room->name\n";
}