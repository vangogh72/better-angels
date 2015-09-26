<?php
namespace BuoyChat;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
	protected $clients;
	
	public function __construct() {
		$this->clients = new \SplObjectStorage; 
	}
	
	public function onOpen(ConnectionInterface $conn) {
		$this->clients->attach($conn);
		
		echo "New connection! {$conn->resourceId}\n";
	}
	
	public function onMessage(ConnectionInterface $from, $msg) {
		if ($msg[0] == '&') {
			$clientInfo = json_decode(substr($msg, 1)); 
			$this->clients[$from] = $clientInfo->room; 
			echo sprintf('Connection %d joined as %s in room %s' . "\n", 
				$from->resourceId, $clientInfo->name, $this->clients[$from]); 
			
		} else {
		
			echo sprintf('Connection %d sending message "%s" to %s' . "\n",
				$from->resourceId, $msg, $this->clients[$from]);
			
			foreach ($this->clients as $client) {
				if ($this->clients[$client] == $this->clients[$from]) {
					$client->send($msg);
				}
			}
		} 
	}
	
	public function onClose(ConnectionInterface $conn) {
		$this->clients->detach($conn);
		
		echo "Connection {$conn->resourceId} has disconnected\n";
	}
	
	public function onError(ConnectionInterface $conn, \Exception $e) {
		echo "An error has occurred: {$e->getMessage()}\n";
		
		$conn->close();
	}
}