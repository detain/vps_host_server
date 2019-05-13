<?php
use Workerman\Connection\AsyncTcpConnection;

return function ($stdObject) {
	/**
	* @var \GlobalData\Client
	*/
	global $global;
	//echo 'Update Info Timer Startup'.PHP_EOL;
	if ($global->cas('busy', 0, 1)) {
		$task_connection = new AsyncTcpConnection('Text://127.0.0.1:55552');
		$task_connection->send(json_encode(array('type' => 'vps_update_info', 'args' => array('type' => $stdObject->type))));
		$conn = $stdObject->conn;
		$task_connection->onMessage = function ($task_connection, $task_result) use ($conn) {
			/**
			* @var \GlobalData\Client
			*/
			global $global;
			//Worker::safeEcho(var_dump($task_result,true));
			$task_connection->close();
			//Worker::safeEcho('Update Info Got Result, Forwarding It'.PHP_EOL);
			$conn->send($task_result);
			$global->busy = 0;
			//Worker::safeEcho('Update Info Timer End'.PHP_EOL);
		};
		$task_connection->connect();
	} else {
		Worker::safeEcho('Update Info Timer Startup Failed To Get Lock'.PHP_EOL);
	}
};
