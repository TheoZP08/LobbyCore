<?php

namespace XFizzer\UI;

use jojoe77777\FormAPI\FormAPI;
use pocketmine\Player;
use XFizzer\API;
use XFizzer\Stats\Stats;

class StatsUI {

    public static function statsUI(Player $player) {
        $formapi = API::$main->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createCustomForm(function (Player $player, ?array $data) {
            if (!is_null($data)) {
            }
        });
        $tokens = Stats::getTokens($player);
        $kills = Stats::getKills($player);
        $deaths = Stats::getDeaths($player);
        $form->setTitle("Stats for " . $player->getName());
        $form->addLabel("Tokens: " . $tokens);
        $form->addLabel("Kills: " . $kills);
        $form->addLabel("Deaths: " . $deaths);
        $form->sendToPlayer($player);
    }
}