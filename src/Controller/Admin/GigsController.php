<?php
namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Gigs;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Controller\DataTablesTrait;
use Doctrine\ORM\QueryBuilder;


/**
 * @Route("/admin")
 */
class GigsController extends Controller
{

  use DataTablesTrait;

  /**
   * @Route("/gigs", name="gigs_list")
   */
  public function orderList(Request $request)
  {

    $table = $this->createDataTable()
            ->add('name', TextColumn::class,['label' => 'Name', 'className' => 'bold'])
            ->add('price', TextColumn::class,['label' => 'Price', 'className' => 'bold'])
            ->add('featured', BoolColumn::class,['label' => 'Featured', 'className' => 'bold'])
            ->add('amount', TextColumn::class,['label' => 'Amount', 'className' => 'bold'])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Gigs::class,
                'query' => function (QueryBuilder $builder) {
                  $builder
                      ->select('e')
                      ->from(Gigs::class, 'e')
                  ;
              },
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

    return $this->render('admin/order_list.html.twig', [
        'name'=>'Gigs',
        'class'=>'gigs',
        'datatable' => $table,
    ]);
  }

}
