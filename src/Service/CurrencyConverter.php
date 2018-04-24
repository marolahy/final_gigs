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


    private $em;


    public function __construct(EntityManager $entityManager)
    {
        $this->currencyRepository = $entityManager->getRepository(Currency::class);
        $this->em = $entityManager;
    }


    /**
     * @required
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

  public function getCurrentCurrency($value,$cur)
  {
    $byNow = $this->currencyRepository->findBy(array('date'=>new \DateTime("now"),'name'=>$cur));
    if(empty($byNow))
    {
      $current_currency = $this->loadCurrency();
      $currency = new Currency();
      $currency->setName($cur);
      $currency->setValue($current_currency->{$cur});
      $currency->setDate( new \DateTime("now") );
      $this->em->persist($currency);
      $this->em->flush();
      return round($value * $currency->getValue(),2);
    }else{
      return round($value * current($byNow)->getValue(),2);
    }
  }

  private function loadCurrency()
  {
    $url =  'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';
    $all_api_call = simplexml_load_file($url);
    $currency = new \stdClass;
    $time = $all_api_call->Cube->Cube['time'];
    $currency->time = $time;
    $tmp_usd = 0;
    foreach($all_api_call->Cube->Cube->Cube as $cube){
      if($cube['currency'] == 'USD')
        $tmp_usd = (float)$cube['rate'];
    }
    foreach($all_api_call->Cube->Cube->Cube as $cube){
        if($cube['currency'] == 'USD')
          $currency->{$cube['currency']} = 1;
        else{
          $currency->{$cube['currency']} = ( 1/$tmp_usd ) * (float)$cube['rate'];
        }
    }
    $currency->EUR = ( 1/$tmp_usd );
    return $currency;
  }

}
