<?php
namespace App\Controller\Admin;

use App\Doctrine\ORMAdapter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Category;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Response;
use App\Datatables\CategoryDatatable;

/**
 * @Route("/admin/category")
 */
class CategoryController extends Controller
{
   /**
    * @Route("/", name="category_list")
    */
   public function orderList(Request $request)
   {
     $isAjax = $request->isXmlHttpRequest();
     $datatable = $this->get('sg_datatables.factory')->create(CategoryDatatable::class);
     $datatable->buildDatatable();

     if ($isAjax) {
         $responseService = $this->get('sg_datatables.response');
         $responseService->setDatatable($datatable);
         $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
         //$qb->leftJoin('App\Entity\Gigs', Doctrine\ORM\Query\Lexer::T_WITH, null, 'g.id = category.id', null);

         return $responseService->getResponse();
     }

     return $this->render('admin/category_list.html.twig', array(
         'name'=>'Category',
         'class'=>'category',
         'datatable' => $datatable,
     ));
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
