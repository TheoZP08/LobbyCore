<?php

namespace XFizzer\Events;

use pocketmine\entity\Attribute;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\Item;
use XFizzer\API;

class EventListener implements Listener
{
    /**
     * @param PlayerJoinEvent $event
     */
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

    /**
     * @param PlayerInteractEvent $event
     */
    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $inv = $player->getInventory();
        $hand = $inv->getItemInHand();
        switch ($hand->getId()) {
            case Item::COMPASS:
                $player->sendMessage("coming soon");
                break;
            case Item::FEATHER:
                API::launch($player);
                break;
            case Item::DYE:
                if ($hand->getDamage() === 10) {
                    foreach (API::$main->getServer()->getOnlinePLayers() as $pl) {
                        $player->hidePlayer($pl);
                    }
                    $inv->setItem(8, Item::get(Item::DYE, 8)->setCustomName("Player Visibility: off"));
                } elseif ($hand->getDamage() === 8) {
                    foreach (API::$main->getServer()->getOnlinePLayers() as $pl) {
                        $player->showPlayer($pl);
                    }
                    $inv->setItem(8, Item::get(Item::DYE, 10)->setCustomName("Player Visibility: on"));
                }
                break;
        }
    }

    /**
     * @param BlockPlaceEvent $event
     */
    public function onBlockPlace(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();
        if (!$player->isOp()) {
            $event->setCancelled(true);
        }
    }

    /**
     * @param BlockBreakEvent $event
     */
    public function onBlockBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        if (!$player->isOp()) {
            $event->setCancelled(true);
        }
    }

    /**
     * @param PlayerExhaustEvent $event
     */
    public function onExhaustEvent(PlayerExhaustEvent $event)
    {
        $event->setCancelled(true);
    }

    /**
     * @param EntityDamageEvent $event
     */
    public function onDamage(EntityDamageEvent $event)
    {
        if ($event->getCause() == EntityDamageEvent::CAUSE_FALL) {
            $event->setCancelled(true);
        }
    }
}