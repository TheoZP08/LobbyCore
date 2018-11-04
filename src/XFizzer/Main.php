<?php

namespace XFizzer;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use XFizzer\Commands\Hub;
use XFizzer\Events\EventListener;

class Main extends PluginBase implements Listener
{
    public function onLoad(): void
    {
        API::$main = $this;
    }

    public function onEnable(): void
    {
        $this->getLogger()->info('LobbyCore by XFizzer loaded');
        $this->Events();
        $this->Commands();
    }

    public function onDisable()
    {
        $this->getLogger()->info('LobbyCore disabled');
    }

    public function Events()
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }

    public function Commands()
    {
        $this->getServer()->getCommandMap()->register("hub", new Hub($this));
    }
}