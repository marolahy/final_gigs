<?php
namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Order;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Controller\DataTablesTrait;
use Doctrine\ORM\QueryBuilder;


/**
 * @Route("/admin")
 */
class OrderController extends Controller
{


  use DataTablesTrait;


  /**
   * @Route("/orders", name="order_list")
   */
  public function orderList(Request $request)
  {

    $table = $this->createDataTable()
            ->add('name', TextColumn::class,['label' => 'Name', 'className' => 'bold'])
            ->add('email', TextColumn::class,['label' => 'Email', 'className' => 'bold'])
            ->add('featured', TextColumn::class,['label' => 'Phone', 'className' => 'bold'])
            ->add('amount', TextColumn::class,['label' => 'Amount', 'className' => 'bold'])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Order::class,
                'query' => function (QueryBuilder $builder) {
                  $builder
                      ->select('e')
                      ->from(Order::class, 'e')
                  ;
              },
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

    return $this->render('admin/order_list.html.twig', [
        'name'=>'Order',
        'class'=>'order',
        'datatable' => $table,
    ]);
  }


  /**
   * @Route("/orders/{$id}", name="order_view")
   */
  public function viewOrder($id)
  {

  }

}
