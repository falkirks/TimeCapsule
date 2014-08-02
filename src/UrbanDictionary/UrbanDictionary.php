<?php
namespace UrbanDictionary;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\level\position;
use pocketmine\scheduler\AsyncTask;

class UrbanDictionary extends PluginBase implements CommandExecutor {
  public $s;
  public function onEnable() {
    $this->getLogger()->info("UrbanDictionary loaded!\n");
    $this->s = [];
    $this->getServer()->getScheduler()->scheduleRepeatingTask(new CompletionChecker($this), 40);
  }
  public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
    if(isset($args[0])){
        if(!isset($this->s[$sender->getName()])){
            $sender->sendMessage("--Definition for " . implode(" ", $args) . "--");
            $tsk = new SearchTask(implode("%20", $args), $sender->getName());
            $s = array($tsk, $sender);
            $this->s[$sender->getName()] = $s;
            $this->getServer()->getScheduler()->scheduleAsyncTask($tsk);
            return true;
        }
        else{
            $sender->sendMessage("You already have a search pending.");
        }
    }
  }
  public function taskCallback($n){
      if(isset($this->s[$n])){
          $this->s[$n][1]->sendMessage($this->s[$n][0]->getResult());
          unset($this->s[$n]);
      }
  }
}
