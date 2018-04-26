<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Payment\CoreBundle\Entity\PaymentInstruction;

/**
 * @ORM\Table(name="order")
 */
class Order
{

  /**
  * @ORM\Id
  * @ORM\Column(type="integer")
  * @ORM\GeneratedValue(strategy="AUTO")
  */
    private $id;
    /** @ORM\OneToOne(targetEntity="JMSPaymentCore:PaymentInstruction") */
    private $paymentInstruction;

    /** @ORM\Column(type="string", unique = true) */
    private $orderNumber;

    /** @ORM\Column(type="decimal", precision = 2) */
    private $amount;

    /**
    * @ORM\Column(type="datetime", name="payed_at", nullable=true)
    */
   private $payedAt;


   public function getId()
  {
      return $this->id;
  }

    public function __construct($amount, $orderNumber)
    {
        $this->amount = $amount;
        $this->orderNumber = $orderNumber;
    }

    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getPaymentInstruction()
    {
        return $this->paymentInstruction;
    }

    public function setPaymentInstruction(PaymentInstruction $instruction)
    {
        $this->paymentInstruction = $instruction;
    }
    public function getPayedAt()
    {
        return $this->payedAt;
    }

    public function setPayedAt($payedAt)
    {
        $this->payedAt = $payedAt;

        return $this;
    }
}
