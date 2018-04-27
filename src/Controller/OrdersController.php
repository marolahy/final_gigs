<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Payment\CoreBundle\Form\ChoosePaymentMethodType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use JMS\Payment\CoreBundle\PluginController\Result;
use App\Entity\Order;
use App\Entity\Gigs;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use JMS\Payment\CoreBundle\Plugin\Exception\Action\VisitUrl;
use JMS\Payment\CoreBundle\Plugin\Exception\ActionRequiredException;

/**
 * @Route("/orders")
 */
class OrdersController extends Controller
{

/**
 * @Route("/new/{id}")
 */
  public function newAction($id,Request $request)
  {
      $em = $this->getDoctrine()->getManager();
      $gig = $this->getDoctrine()->getRepository(Gigs::class)->find($id);
      $order = new Order($gig);
      $order->setName($request->query->get('name'));
      $order->setPhone($request->query->get('phone'));
      $order->setMessage($request->query->get('message'));
      $em->persist($order);
      $em->flush();

      return $this->redirect($this->generateUrl('app_orders_show', [
          'id' => $order->getId(),
      ]));
  }
  /**
   * @Route("/{id}/show")
   * @Template
   */
  public function showAction(Request $request, Order $order)
  {
    $form = $this->createForm(ChoosePaymentMethodType::class, null, [
        'amount'   => $order->getAmount(),
        'currency' => 'USD',
        'default_method'  => 'paypal_express_checkout',
        'predefined_data' => [
            'paypal_express_checkout' => [
              'return_url' => $this->generateUrl('payment_complete',['orderNumber' => $order->getId()],UrlGeneratorInterface::ABSOLUTE_URL),
              'cancel_url' => $this->generateUrl('payment_cancel',['orderNumber' => $order->getId()],UrlGeneratorInterface::ABSOLUTE_URL),
            ]
        ],
    ]);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $ppc = $this->get('payment.plugin_controller');
          $ppc->createPaymentInstruction($instruction = $form->getData());

          $order->setPaymentInstruction($instruction);

          $em = $this->getDoctrine()->getManager();
          $em->persist($order);
          $em->flush($order);

          return $this->redirect($this->generateUrl('app_orders_paymentcreate', [
              'id' => $order->getId(),
          ]));
      }

      return [
          'order' => $order,
          'form'  => $form->createView(),
      ];
  }
  private function createPayment($order)
  {
      $instruction = $order->getPaymentInstruction();
      $pendingTransaction = $instruction->getPendingTransaction();

      if ($pendingTransaction !== null) {
          return $pendingTransaction->getPayment();
      }

      $ppc = $this->get('payment.plugin_controller');
      $amount = $instruction->getAmount() - $instruction->getDepositedAmount();

      return $ppc->createPayment($instruction->getId(), $amount);
  }
/**
 * @Route("/{id}/payment/create")
 */
  public function paymentCreateAction(Order $order)
  {
      $payment = $this->createPayment($order);

      $ppc = $this->get('payment.plugin_controller');
      $result = $ppc->approveAndDeposit($payment->getId(), $payment->getTargetAmount());

      if ($result->getStatus() === Result::STATUS_PENDING)
      {
        $ex = $result->getPluginException();

        if ($ex instanceof ActionRequiredException)
        {
            $action = $ex->getAction();

            if ($action instanceof VisitUrl)
            {
                return $this->redirect($action->getUrl());
            }
        }
    }

  throw $result->getPluginException();

      // In a real-world application you wouldn't throw the exception. You would,
      // for example, redirect to the showAction with a flash message informing
      // the user that the payment was not successful.
  }
/**
 * @Route("/{id}/payment/complete")
 */
  public function paymentCompleteAction(Order $order)
  {
      return new Response('Payment complete');
  }

}
