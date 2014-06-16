<?php
namespace TimeCapsule;
use pocketmine\scheduler\AsyncTask;

class BackupTask extends AsyncTask{
	public $m;
	public $p;
	public $past;
	public $t;
	public function __construct($main, $path, $ps, $time){
		$this->m = $main;
		$this->p = $path;
		$this->past = $ps;
		$this->t = $time;
	}
	public function onRun(){
		$this->copyDir($this->m,$this->p, $this->past, $this->t);
	}
	public function copyDir($src,$dst,$past,$time){
	    $dir = opendir($src); 
	    @mkdir($dst,0755,true); 
	    while(false !== ($file = readdir($dir))){ 
	        if (( $file != '.' ) && ( $file != '..' ) && ($file != 'TimeCapsule')) { 
	            if (is_dir($src . '/' . $file)) { 
	                $this->copyDir($src . '/' . $file,$dst . '/' . $file,$past . '/' . $file,$time);
	            } 
	            else { 
	            if (filemtime($src . '/' . $file) > $time || !is_file($past . '/' . $file)) {
	                copy($src . '/' . $file,$dst . '/' . $file);
	                }
	                else {
	                	link($past . '/' . $file, $dst . '/' . $file);
	                }
	            } 
	        } 
	    } 
	    closedir($dir); 	
	}	
}
