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
use App\Form\GigImagesType;
use App\Form\GigsType;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/admin/gigs")
 */
class GigsController extends Controller
{

  use DataTablesTrait;

  /**
   * @Route("/", name="gigs_list")
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
   * @Route("/new", name="gigs_new", methods="GET|POST")
   */
  public function new(Request $request): Response
  {
      $gig = new Gigs();
      $form = $this->createForm(GigsType::class, $gig);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $em->persist($gig);
          $em->flush();

          return $this->redirectToRoute('gigs_list');
      }

      return $this->render('gigs/new.html.twig', [
          'name'=>'Gigs',
          'class'=>'gigs',
          'gig' => $gig,
          'form' => $form->createView(),
      ]);
  }

  /**
   * @Route("/{id}", name="gigs_show", methods="GET")
   */
  public function show(Gigs $gig): Response
  {
      return $this->render('gigs/show.html.twig', ['gig' => $gig]);
  }

  /**
   * @Route("/{id}/edit", name="gigs_edit", methods="GET|POST")
   */
  public function edit(Request $request, Gigs $gig): Response
  {
      $form = $this->createForm(GigsType::class, $gig);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $this->getDoctrine()->getManager()->flush();

          return $this->redirectToRoute('gigs_edit', ['id' => $gig->getId()]);
      }

      return $this->render('gigs/edit.html.twig', [
          'gig' => $gig,
          'form' => $form->createView(),
      ]);
  }

  /**
   * @Route("/{id}", name="gigs_delete")
   */
  public function delete(Request $request, Gigs $gig): Response
  {
      if ($this->isCsrfTokenValid('delete'.$gig->getId(), $request->request->get('_token'))) {
          $em = $this->getDoctrine()->getManager();
          $em->remove($gig);
          $em->flush();
      }

      return $this->redirectToRoute('gigs_index');
  }

}
