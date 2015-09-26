<?php
use Ratchet\Server\IOServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use BuoyChat\Chat;

	require dirname(__DIR__) . '/vendor/autoload.php';
	
	$server = IoServer::factory(
		new HttpServer(
			new WsServer(
				new Chat()
			)
		),  
		8080
	);
	
	$server->run();