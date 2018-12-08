<?php

namespace XFizzer;

use pocketmine\item\Item;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\Player;

class API {
    public static $main;

    /**
     * @return int
     */
    public static function speed() {
        return -2;
    }

    /**
     * @param Player $player
     */
    public static function lobbyItems(Player $player) {
        $inv = $player->getInventory();
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->clearAll();
        $inv->setItem(0, Item::get(Item::COMPASS)->setCustomName("Servers"));
        $inv->setItem(3, Item::get(Item::CHEST)->setCustomName("Stats"));
        $inv->setItem(4, Item::get(Item::FEATHER)->setCustomName("Leap"));
        $inv->setItem(7, Item::get(Item::DIAMOND_SWORD)->setCustomName("PvP"));
        $inv->setItem(8, Item::get(Item::DYE, 10)->setCustomName("Player Visibility: on"));
    }

    /**
     * @param Player $player
     */
    public static function pvpItems(Player $player) {
        $inv = $player->getInventory();
        $player->getInventory()->clearAll();
        $inv->setItem(0, Item::get(Item::DIAMOND_SWORD));
        $inv->setItem(8, Item::get(Item::STICK));

        $player->getArmorInventory()->setHelmet(Item::get(Item::IRON_HELMET));
        $player->getArmorInventory()->setChestplate(Item::get(Item::IRON_CHESTPLATE));
        $player->getArmorInventory()->setLeggings(Item::get(Item::IRON_LEGGINGS));
        $player->getArmorInventory()->setBoots(Item::get(Item::IRON_BOOTS));
    }

    /**
     * @param Player $player
     * @param int $base
     */
    public static function launch(Player $player, $base = 1) {
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