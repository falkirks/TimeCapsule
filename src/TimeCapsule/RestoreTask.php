<?php
namespace TimeCapsule;
use pocketmine\command\CommandSender;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class RestoreTask extends AsyncTask{
	public $p;
	public $m;
    public $user;
	public function __construct($path, $main, CommandSender $user){
		$this->p = $path;
		$this->m = $main;
        $this->user = $user;
	}
	public function onRun(){
		$this->copyDir($this->p, $this->m);
	}
	public function copyDir($src,$dst){
		$dir = opendir($src); 
    	@mkdir($dst,0755,true); 
    	while(false !== ( $file = readdir($dir)) ) { 
        	if (( $file != '.' ) && ( $file != '..' )) { 
            	if ( is_dir($src . '/' . $file) ) { 
                	$this->copyDir($src . '/' . $file,$dst . '/' . $file);
            	} 
            	else { 
                	copy($src . '/' . $file,$dst . '/' . $file); 
            	} 
        	} 
    	} 
    	closedir($dir); 
	}
    public function onCompletion(Server $server){
        $server->getPluginManager()->getPlugin("TimeCapsule")->restoreCallback();
    }
}