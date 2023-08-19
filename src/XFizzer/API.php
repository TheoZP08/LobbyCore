<?php

namespace XFizzer;

use pocketmine\item\Item;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\player\Player;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;

class API {
    public static $main;

    public static function speed(): int 
    {
        return -2;
    }

    public static function lobbyItems(Player $player): void 
    {
        $inv = $player->getInventory();
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->clearAll();
        $inv->setItem(0, Item::get(Item::COMPASS)->setCustomName("Servers"));
        $inv->setItem(3, Item::get(Item::CHEST)->setCustomName("Stats"));
        $inv->setItem(4, Item::get(Item::FEATHER)->setCustomName("Leap"));
        $inv->setItem(7, Item::get(Item::DIAMOND_SWORD)->setCustomName("PvP"));
        $inv->setItem(8, Item::get(Item::DYE, 10)->setCustomName("Player Visibility: on"));
    }

    public static function pvpItems(Player $player): void 
    {
        $inv = $player->getInventory();
        $player->getInventory()->clearAll();
        $inv->setItem(0, Item::get(Item::DIAMOND_SWORD));
        $inv->setItem(8, Item::get(Item::STICK));

        $player->getArmorInventory()->setHelmet(Item::get(Item::IRON_HELMET));
        $player->getArmorInventory()->setChestplate(Item::get(Item::IRON_CHESTPLATE));
        $player->getArmorInventory()->setLeggings(Item::get(Item::IRON_LEGGINGS));
        $player->getArmorInventory()->setBoots(Item::get(Item::IRON_BOOTS));
    }

    public static function launch(Player $player, int $base = 1): void 
    {
        $player->getLevel()->addSound(new BlazeShootSound($player));
        $knockback = Entity::createEntity("minecraft:marker", $player->getLevel(), Entity::createBaseNBT($player));
        switch ($player->getDirection()) {
            case Player::DIRECTION_NORTH:
                $knockback->setMotion(new Vector3(0, $base, -$base));
                break;
            case Player::DIRECTION_SOUTH:
                $knockback->setMotion(new Vector3(0, $base, $base));
                break;
            case Player::DIRECTION_WEST:
                $knockback->setMotion(new Vector3(-$base, $base, 0));
                break;
            case Player::DIRECTION_EAST:
                $knockback->setMotion(new Vector3($base, $base, 0));
                break;
        }
        $knockback->spawnToAll();
    }
}
