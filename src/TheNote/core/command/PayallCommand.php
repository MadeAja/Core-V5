<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\command;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;

class PayallCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("payall", $config->get("prefix") . "Verschenke dein Geld an alle Spieler auf dem Server", "/payall", ["paya"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            return $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (isset($args[0])) {
            if (is_numeric($args[0])) {
                $amount = $args[0];
                $anz = count($this->plugin->getServer()->getOnlinePlayers());
                $tanz = $anz - 1;
                $maxpay = $amount * $tanz;
                $mymoney = EconomyAPI::getInstance()->myMoney($sender);
                if ($maxpay <= $mymoney) {
                    foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
                        $name = $player->getName();
                        $iname = strtolower($name);
                        EconomyAPI::getInstance()->addMoney($iname, $amount);
                        EconomyAPI::getInstance()->reduceMoney($sender, $amount);
                    }
                    $this->plugin->getServer()->broadcastMessage($config->get("prefix") . "§e" . $sender->getNameTag() . "§6 hat §c" . $maxpay . "€ §6 an alle Spieler verteilt. Jeder hat: §e" . $amount . "€§6 erhalten.");
                } else {
                    $sender->sendMessage($config->get("error") . "§cTut mir leid du hast zu wenig Geld auf deinem Konto!");
                }
            } else {
                $sender->sendMessage($config->get("error") . "§cDeine Eingabe war falsch bitte versuche es erneut!");
            }
        } else {
            $sender->sendMessage($config->get("info") . "§cBitte gebe eine Summe an");
        }
    }
}
