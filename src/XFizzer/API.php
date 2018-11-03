<?php

namespace XFizzer;

use pocketmine\level\sound\BlazeShootSound;
use pocketmine\Player;

class API
{
    public static $main;

    public static function speed()
    {
        return -2;
    }

    public static function lobbyItems(Player $player)
    {
        $inv = $player->getInventory();
        $player->getInventory()->clearAll();
        $inv->setItem(0, Item::get(Item::COMPASS)->setCustomName("Servers"));
        $inv->setItem(1, Item::get(Item::FEATHER)->setCustomName("Leap"));
        $inv->setItem(8, Item::get(Item::DYE, 10)->setCustomName("Player Visibility: on"));
    }

    public static function launch(Player $player, $base = 1)
    {
        $player->getLevel()->addSound(new BlazeShootSound($player));
        switch ($player->getDirection()) {
            case 0:
                $player->knockBack($player, 0, 1, 0, $base);
                break;
            case 1:
                $player->knockBack($player, 0, 0, 1, $base);
                break;
            case 2:
                $player->knockBack($player, 0, -1, 0, $base);
                break;
            case 3:
                $player->knockBack($player, 0, 0, -1, $base);
                break;
        }
    }
}