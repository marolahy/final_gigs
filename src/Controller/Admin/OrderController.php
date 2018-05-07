<?php
namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Order;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Filter\TextFilter;
use App\Datatables\OrderDatatable;


/**
 * @Route("/admin")
 */
class OrderController extends Controller
{
  /**
   * @Route("/orders", name="order_list")
   */
  public function orderList(Request $request)
  {
    $isAjax = $request->isXmlHttpRequest();
    $datatable = $this->get('sg_datatables.factory')->create(OrderDatatable::class);
    $datatable->buildDatatable();

    if ($isAjax) {
        $responseService = $this->get('sg_datatables.response');
        $responseService->setDatatable($datatable);
        $responseService->getDatatableQueryBuilder();

        return $responseService->getResponse();
    }

    return $this->render('admin/list.html.twig', array(
        'name'=>'Order',
        'class'=>'order',
        'datatable' => $datatable,
    ));
  }
  /**
   * @Route("/orders/{id}", name="order_view")
   */
  public function viewOrder($id)
  {
      $order = $this->getDoctrine()->getRepository(Order::class)->find($id);
      return $this->render('admin/order_view.html.twig',[
        'name'=>'Order',
        'class'=>'order',
        'order'=>$order
      ]);
  }

}
