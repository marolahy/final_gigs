<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Payment\CoreBundle\Entity\PaymentInstruction;

/**
 * @ORM\Table(name="orders")
 * @ORM\Entity
 */
class Order
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;



    /**
     * The name of the customer.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;


    /**
     * The name of the customer.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $email;


    /**
     * The phone number of the customer.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $phone;


    /**
     * message from the customer.
     *
     * @var string
     * @ORM\Column(type="text")
     */
    protected $message;


    /**
     * @ORM\ManyToOne(targetEntity="Gigs", inversedBy="images")
     * @ORM\JoinColumn(name="gig_id", referencedColumnName="id")
     */
    protected $gig;






    /** @ORM\OneToOne(targetEntity="JMS\Payment\CoreBundle\Entity\PaymentInstruction") */
    private $paymentInstruction;

    /** @ORM\Column(type="decimal", precision=10, scale=5) */
    private $amount;

    public function __construct(Gigs $gig)
    {
        $this->gigs = $gig;
        $this->amount = $gig->getPrice();
    }

    public function getId()
    {
        return $this->id;
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

    /**
     * @return mixed
     */
    public function getGig()
    {
        return $this->gig;
    }

    /**
     * @param mixed $gig
     */
    public function setGig(Gigs $gig)
    {
        $this->gig = $gig;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
      $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
      $this->email = $email;
    }

    public function getPhone()
    {
      return $this->phone;
    }
    public function setPhone($phone)
    {
      $this->phone = $phone;
    }
    public function getMessage()
    {
      return $this->message;
    }
    public function setMessage($message)
    {
        $this->message = $message;
    }
}
