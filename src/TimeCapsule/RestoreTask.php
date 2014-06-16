<?php
namespace TimeCapsule;
use pocketmine\scheduler\AsyncTask;

class RestoreTask extends AsyncTask{
	public $p;
	public $m;
	public function __construct($path,$main){
		$this->p = $path;
		$this->m = $main;
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
}