<?php

namespace XFizzer\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use XFizzer\Main;

class Admin extends Command
{
    private $plugin;

    /**
     * Admin constructor.
     * @param Main $plugin
     */
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("admin", "", "Usage: /admin", [""]);

        /**
         * @param CommandSender $sender
         * @param string $commandLabel
         * @param array $args
         */
        public
        function execute(CommandSender $sender, string $commandLabel, array $args)
        {
            if ($sender instanceof Player) {
                if (isset($args[0])) {
                    switch ($args[0]){
                        case ban:

                            break;
                    }
                }
            }
        }
    }
}