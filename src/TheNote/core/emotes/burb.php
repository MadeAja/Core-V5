<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\emotes;

use pocketmine\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class burb extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("burb", $config->get("prefix") . " §6Rülps Emote", "/burb");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $dcsettings = new Config($this->plugin->getDataFolder() . Main::$setup . "discordsettings.yml", Config::YAML);
        if (!$sender instanceof Player) {
            return $this->plugin->getServer()->broadcastMessage("§bDer Server hat gerülpst O_O");
        }
        $nickname = $sender->getNameTag();
        $name = $sender->getName();
        if (!$this->testPermission($sender)) {
            return false;
        }
        $this->plugin->getServer()->broadcastMessage("§b$nickname §bhat gerülpst O_O");
        if ($dcsettings->get("DC") == true) {
            $ar = getdate();
            $time = $ar['hours'] . ":" . $ar['minutes'];
            $format = Main::$dcname . " : {time} : {player} hat gerülpst O_O";
            $msg = str_replace("{time}", $time, str_replace("{player}", $name, $format));
            $this->plugin->sendMessage($name, $msg);
        }
        return false;
    }
}