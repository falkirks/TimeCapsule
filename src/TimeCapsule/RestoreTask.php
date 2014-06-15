<?php
namespace TimeCapsule;
use pocketmine\scheduler\AsyncTask;

class RestoreTask extends AsyncTask{
	private $id;
	public function __construct($id){
		$this->id = $i;
		$this->run();
	}
	public function onRun(){
		//Copy Directories back into place
	}
}