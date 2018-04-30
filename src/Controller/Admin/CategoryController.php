<?php
namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Category;
use App\Entity\Gigs;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Controller\DataTablesTrait;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @Route("/admin")
 */
class CategoryController extends Controller
{
   use DataTablesTrait;
   /**
    * @Route("/category", name="category_list")
    */
   public function orderList(Request $request)
   {
     $table = $this->createDataTable()
             ->add('name', TextColumn::class,['label' => 'Name', 'className' => 'bold'])
             ->add('countGigs', BoolColumn::class,['label' => 'Gigs', 'className' => 'bold'])
             ->add('id', TextColumn::class,['label' => 'Action', 'className' => 'bold',
                                            'render'=>function($value,$context){
                                              $html  = "<a class=\"btn btn-info btn-sm\" href=\"/admin/category/$value\">View</a>";
                                              $html .= "<a class=\"btn btn-danger btn-sm\" href=\"/admin/category/$value/delete\">Delete</a>";
                                              $html .= "<a class=\"btn btn-success btn-sm\" href=\"/admin/category/$value/delete\">Update</a>";
                                              return $html;
                                            }
                                           ])
             ->createAdapter(ORMAdapter::class, [
                 'entity' => Category::class,
                 'query' => function (QueryBuilder $builder) {
                   $builder
                  ->select('c')
                  ->from(Category::class, 'c')
                  ->leftJoin('c.gigs', 'g')
                  //->groupBy('c.id')
                  //->addGroupBy('c.name')
                   ;
               },
             ])
             ->handleRequest($request);

         if ($table->isCallback()) {
             return $table->getResponse();
         }

     return $this->render('admin/list.html.twig', [
         'name'=>'Category',
         'class'=>'category',
         'datatable' => $table,
     ]);
   }

}
