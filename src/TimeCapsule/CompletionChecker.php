<?php
namespace TimeCapsule;
use pocketmine\scheduler\Task;


class CompletionChecker extends Task{
	public function __construct($h){
		$this->p = $h;
	}
	public function onRun($tick){
		/* Cleanup Backups */
		for ($i = 0; $i < count($this->p->backups); $i++) { 
			if($this->p->backups[$i]->isCompleted() === true){
				//$this->p->backups[$i]->o->sendMessage("Backup completed.")
				unset($this->p->backups[$i]);
			}
		}
		/* Cleanup Restore */
		if($this->p->restore !== false){
			if($this->p->restore->isCompleted() === true){
				$this->p->restore = false;
			}
		}
	}
}