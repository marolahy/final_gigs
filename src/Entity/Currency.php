<?php
declare(strict_types=1);
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="currency")
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyRepository")
 */
class Currency
{
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string", length=100)
   */
  protected $name;


  /**
   * @ORM\Column(type="date")
   */
  protected $date;

  /**
   * @ORM\Column(type="float")
   */
  protected $value;


  public function getName()
  {
    return $this->name;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function getDate()
  {
    return $this->date;
  }
  public function setDate($date)
  {
    $this->date = $date;
  }
  public function getValue()
  {
      return $this->value;
  }
  public function setValue($value)
  {
    $this->value = $value;
  }

  /**
   * Triggered on insert
   * @ORM\PrePersist
   */
  public function onPrePersist()
  {
      $this->date = new \DateTime("now");
  }



}
