<?php
namespace TimeCapsule;
use pocketmine\scheduler\AsyncTask;

class RestoreTask extends AsyncTask{
	private $id;
	public function __construct($id){
		$this->id = $i;
	}
	public function onRun(){
		//Copy Directories back into place
	}
}