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
  public $restore, $backups, $config;
  public function onEnable() {
    @mkdir($this->getDataFolder());
    $this->config = new Config($this->getDataFolder()."/data.yml", Config::YAML, array(0,0));
    $this->backups = array();
    $this->restore = false;
    $this->getServer()->getScheduler()->scheduleRepeatingTask(new CompletionChecker($this), 20);
    $this->getLogger()->info("TimeCapsule loaded!");
  }
  public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
    switch($cmd->getName()) {
      case "backup":
        $id = $this->config->get(0)+1;
        if (!is_dir($this->getDataFolder() . "/" . $this->config->get(0)) && $this->config->get(0) != 0) {
          $sender->sendMessage("[TimeCapsule] Previous backup not found, searching for new backup");
          for ($i = $this->config->get(0); $i >= 0; $i--) {
            if ($i == 0) {
              $sender->sendMessage("[TimeCapsule] No backups can be read, starting fresh.");
              $id = 1;
              $this->config->set(0,0);
              $this->config->set(1,0);
              $this->config->save();
            }
            else {
              if (is_dir($this->getDataFolder() . "/" . $i)) {
                console("[TimeCapsule] Found new backup directory at " . $i);
                $id = $i + 1;
                $this->config->set(0,$i);
                $this->config->set(1,filemtime($this->getDataFolder() . "/" . $i . "/."));
              }
            }
          }
        }
        $tsk = new BackupTask($this->getServer()->getDataPath(), $this->getDataFolder() . "/" . $id, $this->getDataFolder() . "/" . $this->config->get(0), $this->config->get(1));
        $this->getServer()->getScheduler()->scheduleAsyncTask($tsk);
        $this->backups[] = array($tsk, $sender);
        $this->config->set(0,$id);
        $this->config->set(1,strtotime("now"));
        $this->config->save();
        $sender->sendMessage("Backup started...");
        return true;
        break;
      case "restore":
        if(isset($args[0])){
          if ($this->restore === false) {
            if(is_dir($this->getDataFolder() . "/" . $args[0])){
              $tsk = new RestoreTask($this->getDataFolder() . "/" . $args[0], $this->getServer()->getDataPath());
              $this->getServer()->getScheduler()->scheduleAsyncTask($tsk);
              $this->restore = array($tsk, $sender);
              $sender->sendMessage("Restore started...");
              return true;
            }
            else{
              $sender->sendMessage("No backup with that ID");
              return true;
            }
          }
          else{
            $sender->sendMessage("Restore currently in progress. Please wait until it finishes.");
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
