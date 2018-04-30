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
            ->add('id', TextColumn::class,['label' => 'Action', 'className' => 'bold',
                                           'render'=>function($value,$context){
                                             $html  = "<a class=\"btn btn-info btn-sm\" href=\"/admin/gigs/$value\">View</a>";
                                             $html .= "<a class=\"btn btn-danger btn-sm\" href=\"/admin/gigs/$value/delete\">Delete</a>";
                                             $html .= "<a class=\"btn btn-success btn-sm\" href=\"/admin/gigs/$value/delete\">Update</a>";
                                             return $html;
                                           }
                                          ])
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

    return $this->render('admin/gigs_list.html.twig', [
        'name'=>'Gigs',
        'class'=>'gigs',
        'datatable' => $table,
    ]);
  }


  /**
   * @Route("/gigs/new", name="gigs_new")
   */
  public function newGigs()
  {
    return $this->render('admin/gigs_new.html.twig', [
        'name'=>'Gigs',
        'class'=>'gigs'
    ]);
  }


  /**
   * @Route("/gigs/{id}", name="gigs_view")
   */
  public function viewGigs($id)
  {
    return $this->render('admin/gigs_view.html.twig', [
        'name'=>'Gigs',
        'class'=>'gigs'
    ]);
  }
  /**
   * @Route("/gigs/{id}/delete", name="gigs_delete")
   */
  public function deleteGigs($id)
  {
    return $this->render('admin/gigs_view.html.twig', [
        'name'=>'Gigs',
        'class'=>'gigs'
    ]);
  }
  /**
   * @Route("/gigs/{id}/update", name="gigs_delete")
   */
  public function updateGigs($id)
  {
    return $this->render('admin/gigs_view.html.twig', [
        'name'=>'Gigs',
        'class'=>'gigs'
    ]);
  }

}
