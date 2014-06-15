<?php
namespace TimeCapsule;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\level\position;

use TimeCapsule\BackupTask;
use TimeCapsule\RestoreTask;
use TimeCapsule\CompletionChecker;

class TimeCapsule extends PluginBase implements CommandExecutor {
  public function onEnable() {
    $this->getLogger()->info("TimeCapsule loaded!\n");
    $this->backups = array();
    $this->restore = false;
    $this->getServer()->getScheduler()->scheduleRepeatingTask(new CompletionChecker($this), 20);

  }
  public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
    switch($cmd->getName()) {
      case "backup":
        $this->backups[] = new BackupTask();
        $sender->sendMessage("Backup started...");
        return true;
        break;
      case "restore":
        if(isset($args[0])){
          if ($this->restore != false) {
            $this->restore = new RestoreTask($args[0]);
            $sender->sendMessage("Restore started...");
            return true;
          }
          else{
            $sender->sendMessage("Restore currently in progress...");
            return true;
          }
        }
        break;
      default:
        return false;
        break;
    }
  }
  public function onDisable() {    
    $this->getLogger()->info("TimeCapsule unloading...");
  }
}
