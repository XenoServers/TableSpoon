<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Animal;
use pocketmine\event\entity\{EntityDamageByEntityEvent, EntityDamageEvent};
use pocketmine\item\Item;
use pocketmine\nbt\tag\{IntTag};
use pocketmine\Player;
use Xenophilicy\TableSpoon\item\enchantment\Enchantment;

/**
 * Class Rabbit
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Rabbit extends Animal {
    
    public const NETWORK_ID = self::RABBIT;
    
    /** @var int */
    public const
      DATA_RABBIT_TYPE = 18, DATA_JUMP_TYPE = 19;
    
    /** @var int */
    public const
      TYPE_BROWN = 0, TYPE_WHITE = 1, TYPE_BLACK = 2, TYPE_BLACK_WHITE = 3, TYPE_GOLD = 4, TYPE_SALT_PEPPER = 5, TYPE_KILLER_BUNNY = 99;
    public const TAG_RABBIT_TYPE = "RabbitType";
    public $width = 0.4;
    public $height = 0.5;
    
    public function initEntity(): void{
        $type = $this->getRandomRabbitType();
        if(!$this->namedtag->hasTag(self::TAG_RABBIT_TYPE, IntTag::class)){
            $this->namedtag->setInt(self::TAG_RABBIT_TYPE, $type);
        }
        
        $this->setMaxHealth(3);
        $this->getDataPropertyManager()->setByte(self::DATA_RABBIT_TYPE, $type);
        parent::initEntity();
    }
    
    public function getRandomRabbitType(): int{
        $arr = [0, 1, 2, 3, 4, 5, 99];
        return $arr[array_rand($arr)];
    }
    
    public function getRabbitType(): int{
        return $this->namedtag->getInt(self::TAG_RABBIT_TYPE);
    }
    
    public function getName(): string{
        return "Rabbit";
    }
    
    /**
     * @param int $type
     */
    public function setRabbitType(int $type){
        $this->namedtag->setInt(self::TAG_RABBIT_TYPE, $type);
    }
    
    public function getDrops(): array{
        $lootingL = 0;
        $cause = $this->lastDamageCause;
        if($cause instanceof EntityDamageByEntityEvent){
            $damager = $cause->getDamager();
            if($damager instanceof Player){
                $looting = $damager->getInventory()->getItemInHand()->getEnchantment(Enchantment::LOOTING);
                if($looting !== null){
                    $lootingL = $looting->getLevel();
                }else{
                    $lootingL = 0;
                }
            }
        }
        $drops = [Item::get(Item::RABBIT_HIDE, 0, mt_rand(0, 1))];
        if($this->getLastDamageCause() === EntityDamageEvent::CAUSE_FIRE){
            $drops[] = Item::get(Item::COOKED_RABBIT, 0, mt_rand(0, 1));
        }else{
            $drops[] = Item::get(Item::RAW_RABBIT, 0, mt_rand(0, 1));
        }
        if(mt_rand(1, 200) <= (5 + 2 * $lootingL)){
            $drops[] = Item::get(Item::RABBIT_FOOT, 0, 1);
        }
        return $drops;
    }
}