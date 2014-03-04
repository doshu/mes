<?php

	define('USERNAME', 'root');
	define('PASSWORD', 'shodan');

	class Main {
		
		public $connection;
		
		public function __construct() {
			$this->connection = new PDO('mysql://host=localhost;dbname=pthread_test', USERNAME, PASSWORD);
		}
		
		public function start() {
			$activity = new Activity();
			$this->connection->beginTransaction();
			echo "Starting transaction\n";
			$activity->start();
			echo "Calling wait() from main\n";
			$activity->synchronized(function($thread){
				$thread->wait();
			}, $activity);
			echo "wait() unlocked. Update the table from main\n";
			$statement = $this->connection->prepare('UPDATE posts SET status=? WHERE id=?');
			$statement->execute(array(1, 1));
			echo "Calling commit() from main\n";
			$this->connection->commit();
		}
	}
	
	
	class Activity extends Thread {
	
		public $connection;
		
	
		public function run() {
			$connection = new PDO('mysql://host=localhost;dbname=pthread_test', USERNAME, PASSWORD);
			//make something
			$statement = $connection->prepare('SELECT * FROM posts');
			$statement->execute();
			$statement->fetchAll();
			while(!$this->isWaiting());
			echo "Calling notify() from thread\n";
			$this->synchronized(function($thread){
				$thread->notify();
			}, $this);
		}
	}
	
	
	$main = new Main();
	$main->start();

?>
