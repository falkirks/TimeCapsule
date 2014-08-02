<?php
namespace UrbanDictionary;
use pocketmine\scheduler\PluginTask;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\level\position;

class CompletionChecker extends PluginTask{
	public function onRun($tick){
        $tick->hey();
		foreach ($this->getOwner()->s as $id => $v) {
			if($v[0]->hasResult()){
				$v[1]->sendMessage($v[0]->getResult());
				unset($this->getOwner()->s[$id]);
			}
		}
	}
}