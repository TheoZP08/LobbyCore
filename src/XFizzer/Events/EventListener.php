<?php

namespace XFizzer\Events;

use Miste\scoreboardspe\API\{Scoreboard, ScoreboardAction, ScoreboardDisplaySlot, ScoreboardSort};
use pocketmine\entity\Attribute;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;
use XFizzer\API;
use XFizzer\Main;
use XFizzer\Stats\Stats;
use XFizzer\Task\UpdateScoreboardTask;
use XFizzer\UI\StatsUI;

class EventListener implements Listener {

    private $plugin;
    private $pvp = [];

    /**
     * EventListener constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param PlayerJoinEvent $event
     */
    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();
        $event->setJoinMessage("");
        API::lobbyItems($player);
        $this->scoreboard($player);
        //Player Speed
        $speed = $player->getAttributeMap()->getAttribute(Attribute::MOVEMENT_SPEED);
        $speed->setValue($speed->getValue() / (1 + 0.2 * API::speed()));

        if (!$player->hasPlayedBefore()) {
            $player->sendMessage('Welcome ' . $name);
            $this->plugin->getServer()->broadcastMessage($name . " has joined for the first time!!!");
        }
    }

    public function onQuit(PlayerQuitEvent $event) {
        $player = $event->getPlayer();
        $event->setQuitMessage("");
    }

    /**
     * @param PlayerInteractEvent $event
     */
    public function onInteract(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $inv = $player->getInventory();
        $hand = $inv->getItemInHand();
        switch ($hand->getId()) {
            case Item::COMPASS:
                break;
            case Item::FEATHER:
                API::launch($player);
                break;
            case Item::DYE:
                if ($hand->getDamage() === 10) {
                    foreach ($this->plugin->getServer()->getOnlinePLayers() as $pl) {
                        $player->hidePlayer($pl);
                    }
                    $player->sendMessage(TF::RED . TF::BOLD . "(!) " . TF::RESET . TF::GRAY . "Players are now invisible.");
                    $inv->setItem(8, Item::get(Item::DYE, 8)->setCustomName("Player Visibility: off"));
                } elseif ($hand->getDamage() === 8) {
                    foreach ($this->plugin->getServer()->getOnlinePLayers() as $pl) {
                        $player->showPlayer($pl);
                    }
                    $player->sendMessage(TF::RED . TF::BOLD . "(!) " . TF::RESET . TF::GRAY . "Players are now visible.");
                    $inv->setItem(8, Item::get(Item::DYE, 10)->setCustomName("Player Visibility: on"));
                }
                break;
            case Item::DIAMOND_SWORD:
                API::pvpItems($player);
                $player->sendMessage("(!) You are now pvping!");
                $this->pvp[] = $player->getName();
                break;
            case Item::STICK:
                API::lobbyItems($player);
                $player->sendMessage("(!) You are no longer pvping!");
                unset($this->pvp[array_search($player->getName(), $this->pvp)]);
                break;
            case Item::CHEST:
                StatsUI::statsUI($player);
                break;
        }
    }

    /**
     * @param BlockPlaceEvent $event
     */
    public function onBlockPlace(BlockPlaceEvent $event) {
        $player = $event->getPlayer();
        if (!$player->isOp()) {
            $event->setCancelled(true);
        }
    }

    /**
     * @param BlockBreakEvent $event
     */
    public function onBlockBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        if (!$player->isOp()) {
            $event->setCancelled(true);
        }
    }

    /**
     * @param PlayerExhaustEvent $event
     */
    public function onExhaustEvent(PlayerExhaustEvent $event) {
        $event->setCancelled(true);
    }

    /**
     * @param PlayerDropItemEvent $event
     */
    public function onDrop(PlayerDropItemEvent $event) {
        $player = $event->getPlayer();
        if (!$player->isOp()) {
            $event->setCancelled(true);
        }
    }

    /**
     * @param PlayerRespawnEvent $event
     */
    public function onRespawn(PlayerRespawnEvent $event) {
        $player = $event->getPlayer();
        API::lobbyItems($player);
    }

    /**
     * @param EntityDeathEvent $event
     */
    public function onDeath(PlayerDeathEvent $event) {
        $event->setDrops([]);
        Stats::addDeaths($event->getEntity(), 1);
        unset($this->pvp[array_search($event->getEntity()->getName(), $this->pvp)]);
        if ($event->getEntity()->getLastDamageCause() instanceof EntityDamageByEntityEvent) {
            $killer = $event->getEntity()->getLastDamageCause()->getDamager();
            if ($killer instanceof Player) {
                Stats::addKills($killer, 1);
            }
        }
    }

    /**
     * @param EntityDamageEvent $event
     */
    public function onDamage(EntityDamageEvent $event) {
        if ($event->getCause() == EntityDamageEvent::CAUSE_FALL) {
            $event->setCancelled(true);
        }

        if ($event instanceof EntityDamageByEntityEvent) {
            $killer = $event->getDamager();
            if (!in_array($event->getEntity()->getName(), $this->pvp)) {
                $killer->sendMessage("(!) You can't pvp with this person.");
                $event->setCancelled(true);
            }
        }
    }

    /**
     * @param Player $player
     * @var Scoreboard $scoreboard
     */
    public function scoreBoard(Player $player) {
        $scoreboard = new Scoreboard($this->plugin->getServer()->getPluginManager()->getPlugin("ScoreboardsPE")->getPlugin(), TF::GREEN . "- Unnamed -", ScoreboardAction::CREATE);
        $scoreboard->create(ScoreboardDisplaySlot::SIDEBAR, ScoreboardSort::DESCENDING);
        $scoreboard->addDisplay($player, ScoreboardDisplaySlot::SIDEBAR, ScoreboardSort::ASCENDING);
        $this->plugin->getScheduler()->scheduleRepeatingTask(new UpdateScoreboardTask($scoreboard, $player), 20);
    }
}