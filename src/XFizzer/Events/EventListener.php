<?php

namespace XFizzer\Events;

use pocketmine\entity\Attribute;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerJoinEvent;
use XFizzer\API;

class EventListener implements Listener
{
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $event->setJoinMessage("");
        API::lobbyItems($player);
        //Player Speed
        $speed = $player->getAttributeMap()->getAttribute(Attribute::MOVEMENT_SPEED);
        $speed->setValue($speed->getValue() / (1 + 0.2 * API::speed()));

        if (!$player->hasPlayedBefore()) {
            $player->sendMessage('Welcome ' . $name);
            API::$main->getServer()->broadcastMessage($name . " has joined for the first time!!!");
        }
    }

    public function onBlockPlace(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();
        if (!$player->isOp()) {
            $event->setCancelled(true)
        }
    }

    public function onBlockBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        if (!$player->isOp()) {
            $event->setCancelled(true)
        }
    }

    public function onExhaustEvent(PlayerExhaustEvent $event)
    {
        $event->setCancelled(true);
    }

    public function onDamage(EntityDamageEvent $event)
    {
        if ($event->getCause() == EntityDamageEvent::CAUSE_FALL) {
            $event->setCancelled(true);
        }
    }
}