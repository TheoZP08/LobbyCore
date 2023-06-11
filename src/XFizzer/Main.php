<?php

namespace XFizzer;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {

    public function onLoad(): void {
        API::$main = $this;
    }

    public function onEnable(): void {
        $this->getLogger()->info('LobbyCore by XFizzer loaded');
        $this->Events();
    }

    public function onDisable() {
        $this->getLogger()->info('LobbyCore disabled');
    }

    public function Events() {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }
}