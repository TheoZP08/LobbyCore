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
        $this->registerEvents();
    }

    public function onDisable(): void {
        $this->getLogger()->info('LobbyCore disabled');
    }

    public function registerEvents(): void {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }
}
