<?php

require($_SERVER['DOCUMENT_ROOT'] . '/config.php');

foreach ($basecampSetting->todo as $todolist) {
	$bc = new Basecamp($basecampSetting->url, $basecampSetting->user, $basecampSetting->pass);
	$response = $bc->getTodoItemsForList($todolist);

	if ($response['body']) {
		$xml = simplexml_load_string($response['body']);
		$json = json_encode($xml);
		$array = json_decode($json,TRUE);
	}

	$oldTodoItemsForList = $dbFacile->fetchRow("SELECT * FROM crawl_data WHERE todolist_id = ? ORDER BY id DESC", array($todolist));

	if (count($array['todo-item']) > 0) {
		$todoItemsForList = array();

		$oldTodoItems = ($oldTodoItemsForList['todoitems']) ? json_decode($oldTodoItemsForList['todoitems']) : array();

		foreach ($array['todo-item'] as $result) {
			if ($result['completed'] == 'false') {
				if (!in_array($result['id'], $oldTodoItems)) sendHipchatNotification($result);
				$todoItemsForList[] = $result['id'];
			}
		}

		$newEntry = array(
			'todolist_id' => $todolist,
			'todoitem_count' => count($array['todo-item']),
			'todoitems' => json_encode($todoItemsForList)
		);

		$dbFacile->insert($newEntry, 'crawl_data');
	}
}

function sendHipchatNotification($result) {
	global $basecampSetting, $hipchatSetting;
	echo "{$result['id']} - {$result['content']} \n";
	$url = $basecampSetting->qa_url . 'todo_items/' . $result['id'] . '/comments';
	$room_id = $hipchatSetting->room;
	$message = "QA - New Basecamp todo item just created - {$result['content']} click <a href='{$url}'>HERE</a> to read the bug";
	$notify = true;
	$from = "Basecamp Todo";
	
	$hc = new HipChat($hipchatSetting->token);
	$hc->message_room($room_id, $from, $message, $notify);
}