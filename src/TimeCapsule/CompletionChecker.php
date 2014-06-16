<?php
namespace TimeCapsule;
use pocketmine\scheduler\PluginTask;


class CompletionChecker extends PluginTask{
	public function onRun($tick){
		/* Cleanup Backups */
		for ($i = 0; $i < count($this->p->backups); $i++) { 
			if($this->getOwner()->backups[$i][0]->isCompleted() === true){
				$this->getOwner()->backups[$i][1]->sendMessage("Backup completed.")
				unset($this->p->backups[$i]);
			}
		}
		/* Cleanup Restore */
		if($this->getOwner()->restore !== false){
			if($this->getOwner()->restore->isCompleted() === true){
				$this->getOwner()->restore[1]->sendMessage("Restore and reload complete...You may need to run /stop");
				$this->getOwner()->restore = false;
			}
		}
	}
}