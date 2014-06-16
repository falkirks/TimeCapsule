<?php
namespace TimeCapsule;
use pocketmine\scheduler\PluginTask;


class CompletionChecker extends PluginTask{
	public function onRun($tick){
		/* Cleanup Backups */
		foreach ($this->getOwner()->backups as $key => $backup) { 
			if($backup[0]->isFinished() === true){
				$backup[1]->sendMessage("Backup completed.");
				unset($this->getOwner()->backups[$key]);
			}
		}
		/* Cleanup Restore */
		if($this->getOwner()->restore !== false){
			if($this->getOwner()->restore[0]->isFinished() === true){
				$this->getOwner()->restore[1]->sendMessage("Restore complete...You may need to restart or reload");
				$this->getOwner()->restore = false;
			}
		}
	}
}