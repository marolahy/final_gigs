<?php
namespace App\Controller\Admin;

use App\Doctrine\ORMAdapter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Category;
use App\Entity\Gigs;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\BoolColumn;
use Omines\DataTablesBundle\Controller\DataTablesTrait;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/category")
 */
class CategoryController extends Controller
{
   use DataTablesTrait;
   /**
    * @Route("/", name="category_list")
    */
   public function orderList(Request $request)
   {
     $table = $this->createDataTable()
             ->add('name', TextColumn::class,['label' => 'Name', 'className' => 'bold'])
             ->add('countGigs', BoolColumn::class,['label' => 'Gigs',
                                                    'className' => 'bold',
                                                    'render'=>function($value,$context){
                                                      return $context->countGigs;
                                                    },])
             ->add('id', TextColumn::class,[
                          'label' => 'Action',
                          'className' => 'bold',
                          'render'=>function($value,$context){
                                  $html  = "<a class=\"btn btn-info btn-sm\" href=\"".$this->generateUrl('category_show',['id'=>$value])."\">View</a>";
                                  $html .= "<a class=\"btn btn-danger btn-sm\" href=\"".$this->generateUrl('category_show',['id'=>$value])."\">Delete</a>";
                                  $html .= "<a class=\"btn btn-success btn-sm\" href=\"".$this->generateUrl('category_edit',['id'=>$value])."\">Update</a>";
                                  return $html;
                                }
                          ])
             ->createAdapter(ORMAdapter::class, [
                 'entity' => Category::class,
                 'hydrate'=>\Doctrine\ORM\Query::HYDRATE_OBJECT,
                 'query' => function (QueryBuilder $builder) {
                   $builder
                  ->select('entity.id, entity.name, COUNT(gigs.id) as countGigs ')
                  ->from(Category::class, 'entity')
                  ->distinct()->leftJoin('entity.gigs', 'gigs','entity.id = gigs.category')
                  ->addGroupBy('entity.id');
               },
             ])
             ->handleRequest($request);

         if ($table->isCallback()) {
             return $table->getResponse();
         }

     return $this->render('admin/category_list.html.twig', [
         'name'=>'Category',
         'class'=>'category',
         'datatable' => $table,
     ]);
   }
   /**
    * @Route("/new", name="category_new", methods="GET|POST")
    */
   public function new(Request $request): Response
   {
       $category = new Category();
       $form = $this->createForm(CategoryType::class, $category);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           $em = $this->getDoctrine()->getManager();
           $em->persist($category);
           $em->flush();

           return $this->redirectToRoute('category_list');
       }

       return $this->render('category/new.html.twig', [
           'name'=>'Category',
           'class'=>'category',
           'category' => $category,
           'form' => $form->createView(),
       ]);
   }

   /**
    * @Route("/{id}", name="category_show", methods="GET")
    */
   public function show(Category $category): Response
   {
       return $this->render('category/show.html.twig', [
         'name'=>'Category',
         'class'=>'category',
         'category' => $category
       ]);
   }

   /**
    * @Route("/{id}/update", name="category_edit", methods="GET|POST")
    */
   public function edit(Request $request, Category $category): Response
   {
       $form = $this->createForm(CategoryType::class, $category);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           $this->getDoctrine()->getManager()->flush();

           return $this->redirectToRoute('category_edit', ['id' => $category->getId()]);
       }

       return $this->render('category/edit.html.twig', [
           'name'=>'Category',
           'class'=>'category',
           'category' => $category,
           'form' => $form->createView(),
       ]);
   }

   /**
    * @Route("/{id}/delete", name="category_delete")
    */
   public function delete($id, Request $request): Response
   {
       $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(array('id'=>$id));
       $em = $this->getDoctrine()->getManager();
       $em->remove($category);
       $em->flush();
       return $this->redirectToRoute('category_list');
   }

}
