<?php

namespace App\Enums;

enum CurrencyUnit: string
{
    case USD = '$';
    case EUR = '€';
    case GBP = '£';
    case RUB = '₽';

    public function label(): string
    {
        return match($this) {
            self::USD => 'United States Dollar',
            self::EUR => 'Euro',
            self::GBP => 'British Pound',
            self::RUB => 'Russian Ruble',
        };
    }
}

