<?php

namespace App\Controller\Admin;


use App\Entity\Gigs;
use App\Entity\GigImages;
use App\Form\GigImagesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/gig/images")
 */
class GigImagesController extends Controller
{

    /**
     * @Route("/{id}/new", name="gig_images_new", methods="GET|POST")
     */
    public function new($id, Request $request): Response
    {
        $gigs = $this->getDoctrine()->getRepository(Gigs::class)->findOneBy(array('id'=>$id));
        $gigImage = new GigImages();
        $gigImage->setGig($gigs);
        $form = $this->createForm(GigImagesType::class, $gigImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gigImage);
            $em->flush();

            return $this->redirectToRoute('gigs_show',['id'=>$gigImage->getGig()->getId()]);
        }

        return $this->render('gig_images/new.html.twig', [
            'name'=>'Gigs',
            'class'=>'gigs',
            'gig_image' => $gigImage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="gig_images_show", methods="GET")
     */
    public function show(GigImages $gigImage): Response
    {
        return $this->render('gig_images/show.html.twig', [
                      'name'=>'Gigs',
                      'class'=>'gigs',
                      'gig_image' => $gigImage
            ]);
    }

    /**
     * @Route("/{id}/edit", name="gig_images_edit", methods="GET|POST")
     */
    public function edit(Request $request, GigImages $gigImage): Response
    {
        $form = $this->createForm(GigImagesType::class, $gigImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gig_images_edit', ['id' => $gigImage->getId()]);
        }

        return $this->render('gig_images/edit.html.twig', [
            'name'=>'Gigs',
            'class'=>'gigs',
            'gig_image' => $gigImage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="gig_images_delete", methods="DELETE")
     */
    public function delete(Request $request, GigImages $gigImage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gigImage->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($gigImage);
            $em->flush();
        }

        return $this->redirectToRoute('gig_images_index');
    }
}
