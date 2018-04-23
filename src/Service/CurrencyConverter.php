<?php

namespace App\Service;
use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManager;
class CurrencyConverter
{
  /**
     * @var CurrencyRepository
     */
    private $currencyRepository;


    public function __construct(EntityManager $entityManager)
    {
        $this->currencyRepository = $entityManager->getRepository(Currency::class);
    }


    /**
     * @required
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

  public function getCurrentCurrency($value,$currency)
  {
    if($currency != 'EUR')
      $value = $value * 0.15;
    return $value;
  }

  private function loadCurrency()
  {
    $url =  'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';
    $all_api_call = simplexml_load_file($url);
    $currency = new \stdClass;
    $time = $all_api_call->Cube->Cube['time'];
    $currency->time = $time;
    foreach($all_api_call->Cube->Cube->Cube as $cube)
    	$currency->{$cube['currency']} = (float)$cube['rate'];
    return $currency;
  }

}
