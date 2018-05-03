<?php
namespace App\Controller;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Payment\CoreBundle\Entity\Payment;
use JMS\Payment\CoreBundle\PluginController\Result;
use JMS\Payment\CoreBundle\Plugin\Exception\ActionRequiredException;
use JMS\Payment\CoreBundle\Plugin\Exception\Action\VisitUrl;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Order;

/**
 * @Route("/payments")
 */
class PaymentController
{
    /** @DI\Inject */
    private $request;

    /** @DI\Inject */
    private $router;

    /** @DI\Inject("doctrine.orm.entity_manager") */
    private $em;

    /** @DI\Inject("payment.plugin_controller") */
    private $ppc;

    /**
     * @Route("/{orderNumber}/details", name = "payment_details")
     * @Template
     */
    public function detailsAction(Order $order)
    {
        $form = $this->getFormFactory()->create('jms_choose_payment_method', null, array(
            'amount'   => $order->getAmount(),
            'currency' => 'EUR',
            'default_method' => 'payment_paypal', // Optional
            'predefined_data' => array(
                'paypal_express_checkout' => array(
                    'return_url' => $this->router->generate('payment_complete', array(
                        'orderNumber' => $order->getOrderNumber(),
                    ), true),
                    'cancel_url' => $this->router->generate('payment_cancel', array(
                        'orderNumber' => $order->getOrderNumber(),
                    ), true)
                ),
            ),
        ));

        if ('POST' === $this->request->getMethod()) {
            $form->bindRequest($this->request);

            if ($form->isValid()) {
                $this->ppc->createPaymentInstruction($instruction = $form->getData());

                $order->setPaymentInstruction($instruction);
                $this->em->persist($order);
                $this->em->flush($order);

                return new RedirectResponse($this->router->generate('payment_complete', array(
                    'orderNumber' => $order->getOrderNumber(),
                )));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }



    /**
     * @Route("/{orderNumber}/complete", name = "payment_complete")
     */
    public function completeAction(Order $order)
    {
        $em = $this->getDoctrine()->getManager();
        $instruction = $order->getPaymentInstruction();
        if (null === $pendingTransaction = $instruction->getPendingTransaction()) {
            $payment = $this->ppc->createPayment($instruction->getId(), $instruction->getAmount() - $instruction->getDepositedAmount());
        } else {
            $payment = $pendingTransaction->getPayment();
        }

        $result = $this->ppc->approveAndDeposit($payment->getId(), $payment->getTargetAmount());
        if (Result::STATUS_PENDING === $result->getStatus()) {
            $order->setStatus('PENDING');
            $em->persist($order);
            $em->flush();
            $ex = $result->getPluginException();

            if ($ex instanceof ActionRequiredException) {
                $action = $ex->getAction();

                if ($action instanceof VisitUrl) {
                    return new RedirectResponse($action->getUrl());
                }

                throw $ex;
            }
        } else if (Result::STATUS_SUCCESS !== $result->getStatus()) {
            $order->setStatus('NOT PAYED OR CANCELLED');
            $em->persist($order);
            $em->flush();
            throw new \RuntimeException('Transaction was not successful: '.$result->getReasonCode());
        }
        $order->setStatus('PAYED');
        $em->persist($order);
        $em->flush();
        // payment was successful, do something interesting with the order
    }
}
