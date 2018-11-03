<?php
/**
 * Created by PhpStorm.
 * User: XFizzer
 * Date: 11/3/2018
 * Time: 12:04 PM
 */

namespace XFizzer;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
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
    }

    public function onDisable()
    {
        $this->getLogger()->info('');
    }

    public function Events()
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }
}