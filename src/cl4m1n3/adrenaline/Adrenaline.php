<?php
declare(strict_types=1);

namespace cl4m1n3\adrenaline;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\utils\COnfig;

class Adrenaline extends PluginBase implements Listener
{
    protected function onLoad(): void
    {
        $this->saveResource("settings.yml");
        $this->cfg = new Config($this->getDataFolder() . "settings.yml", Config::YAML);
    }
    protected function onEnable() : void
    {
        Server::getInstance()->getPluginManager()->registerEvents($this, $this);
    }
    public function onDamage(EntityDamageByEntityEvent $event) : void
    {
        $entity = $event->getEntity();
        $damager = $event->getDamager();
        $health = $damager->getHealth();
        $damage = $event->getBaseDamage(); // the base damage
        
        $critical = $this->cfg->get("critical");
        $multiplier = $this->cfg->get("multiplier");
        if($entity instanceof Player && $damager instanceof Player)
        {
            if($health < $critical && $multiplier > ($health / $critical)){
                /**
                * A certain percentage, which was obtained as a result of subtracting the maximum 
                * coefficient and the ratio of the player's HP and the "critical" value from the 
                * configuration, from the base damage is added to the base damage
                */
                $event->setBaseDamage($damage + (($damage * (($multiplier - ($health / $critical)) * 100)) / 100));
            }
        }
    }
}