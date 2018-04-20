<?php

namespace App\Service;
class CurrencyConverter
{
  public function getCurrentCurrency($value,$currency)
  {
    if($currency != 'USD')
      $value = $value * 0.15;
    return $value;

  }

}
