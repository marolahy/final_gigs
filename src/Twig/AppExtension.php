<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Service\CurrencyConverter;
use Doctrine\ORM\EntityManagerInterface;
class AppExtension extends AbstractExtension
{


    protected $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    public function getFilters()
    {
        return array(
            new TwigFilter('currency', array($this, 'currencyConverter')),
        );
    }

    public function currencyConverter($number,$currency='USD' )
    {
      $currencyConverter = new CurrencyConverter($this->em);
      $number = $currencyConverter->getCurrentCurrency($number,$currency);
      switch($currency){
        case 'EUR':
            $number = $number. '€';
            break;
        case 'USD':
        default:
            $number = '$ '.$number;
            break;
      }
      return $number;
    }
}
