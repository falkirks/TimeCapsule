<?php
namespace TimeCapsule;
use pocketmine\scheduler\AsyncTask;

class BackupTask extends AsyncTask{
	public function __construct(){
		$this->run();
	}
	public function onRun(){
		//Copy Directories
	}
}
