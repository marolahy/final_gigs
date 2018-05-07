<?php
namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Gigs;
use Doctrine\ORM\QueryBuilder;
use App\Form\GigImagesType;
use App\Form\GigsType;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\GigImages;
use App\Datatables\GigsDatatable;

/**
 * @Route("/admin/gigs")
 */
class GigsController extends Controller
{


  /**
   * @Route("/", name="gigs_list")
   */
  public function orderList(Request $request)
  {

    $isAjax = $request->isXmlHttpRequest();
    $datatable = $this->get('sg_datatables.factory')->create(GigsDatatable::class);
    $datatable->buildDatatable();

    if ($isAjax) {
        $responseService = $this->get('sg_datatables.response');
        $responseService->setDatatable($datatable);
        $responseService->getDatatableQueryBuilder();

        return $responseService->getResponse();
    }


    return $this->render('admin/gigs_list.html.twig', [
        'name'=>'Gigs',
        'class'=>'gigs',
        'datatable' => $datatable,
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
    $gigImages = $this->getDoctrine()
        ->getRepository(GigImages::class)
        ->findBy(array('gig'=>$gig->getId()));
      return $this->render('gigs/show.html.twig', [
        'name'=>'Gigs',
        'class'=>'gigs',
        'gig' => $gig,
        'gig_images'=>$gigImages
      ]);
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
          'name'=>'Gigs',
          'class'=>'gigs',
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
