<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Service\CurrencyConverter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('currency', array($this, 'currencyConverter')),
        );
    }

    public function currencyConverter($number,$currency='USD' )
    {
      $currencyConverter = new CurrencyConverter();
      $number = $currencyConverter->getCurrentCurrency(number,$currency);
      return $number;
    }
}
