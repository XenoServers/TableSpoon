<?php

declare(strict_types=1);

namespace Xenophilicy\TableSpoon\entity\mob;

use pocketmine\entity\Monster;
use pocketmine\item\Item;

/**
 * Class Blaze
 * @package Xenophilicy\TableSpoon\entity\mob
 */
class Blaze extends Monster {
    
    public const NETWORK_ID = self::BLAZE;
    
    public $width = 0.6;
    public $height = 1.8;
    
    public function getName(): string{
        return "Blaze";
    }
    
    public function getDrops(): array{
        return [Item::get(Item::BLAZE_ROD, 0, mt_rand(0, 1))];
    }
}