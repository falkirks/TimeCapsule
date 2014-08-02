<?php
namespace UrbanDictionary;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Utils;

class SearchTask extends AsyncTask{
	private $t;
    private $n;
	public function __construct($term, $name){
		$this->t = $term;
        $this->n = $name;
	}
	public function onRun(){
		$this->setResult(json_decode(Utils::getURL("http://api.urbandictionary.com/v0/define?term=" . $this->t))->list[0]->definition);
	}
    public function onCompletion(Server $server){
        $server->getPluginManager()->getPlugin("UrbanDictionary")->taskCallback($this->n);
    }
}
