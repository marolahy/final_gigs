<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManager;
use JMS\Payment\CoreBundle\PluginController\Event\PaymentStateChangeEvent;
use JMS\Payment\CoreBundle\Model\PaymentInterface;
use App\Entity\Order;

class PaymentListener
{

    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onPaymentStateChange(PaymentStateChangeEvent $event)
    {
        if (PaymentInterface::STATE_DEPOSITED === $event->getNewState()) {
            $order = $this
                ->entityManager
                ->getRepository(Order::class)
                ->findOneBy(array('paymentInstruction' => $event->getPaymentInstruction()));

            $order->setPayedAt(new \DateTime());

            $this->entityManager->persist($order);
            $this->entityManager->flush();
        }
    }
}
