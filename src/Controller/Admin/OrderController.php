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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Omines\DataTablesBundle\Filter\TextFilter;
use App\Datatables\OrderDatatable;


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
    $textFilter = new TextFilter();
    $textFilter->set([
      'template_html' => '@DataTables/Filter/text.html.twig',
      'template_js' => '@DataTables/Filter/text.js.twig',
      'placeholder' => null,
    ]);
    $table = $this->createDataTable(['searching'=>true])
            ->add('name', TextColumn::class,[
                        'label' => 'Name',
                        'className' => 'select-filter',
                        'globalSearchable'=>true,
                        'searchable'=>true,
                      ])
            ->add('email', TextColumn::class,['label' => 'Email', 'className' => 'select-filter'])
            ->add('phone', TextColumn::class,['label' => 'Phone', 'className' => 'select-filter'])
            ->add('status', TextColumn::class,['label' => 'Status', 'className' => 'select-filter'])
            ->add('amount', TextColumn::class,['label' => 'Amount', 'className' => 'select-filter'])
            ->add('id', TextColumn::class,['label' => 'Action', 'className' => 'bold',
                                           'render'=>function($value,$context){
                                             return "<a class=\"btn btn-info btn-sm\" href=\"".$this->generateUrl('order_view',['id'=>$value])."\">View</a>";
                                           }
                                          ])

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

    return $this->render('admin/list.html.twig', [
        'name'=>'Order',
        'class'=>'order',
        'datatable' => $table,
    ]);
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

  /**
 * Lists all Post entities.
 *
 * @param Request $request
 *
 * @Route("/orders_sg", name="post_index")
 *
 * @return Response
 */
public function indexAction(Request $request)
{
    $isAjax = $request->isXmlHttpRequest();

    // Get your Datatable ...
    //$datatable = $this->get('app.datatable.post');
    //$datatable->buildDatatable();

    // or use the DatatableFactory
    /** @var DatatableInterface $datatable */
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

}
