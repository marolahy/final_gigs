<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use App\Entity\Gigs;
use App\Entity\GigImages;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {

        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $gigsRepository = $this->getDoctrine()->getRepository(Gigs::class);
        $categories = $categoryRepository->findAll();
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'categories' => $categories,
            'featureds' => $gigsRepository->findBy(array('featured'=>1),null,9)
        ]);
    }

    /**
     * @Route("/all-gigs", name="all-gigs")
     */
    public function allGigs(Request $request)
    {
      $qb = $this->getDoctrine()
                 ->getRepository(Gigs::class)
                 ->findAllQueryBuilder();
      $adapter = new DoctrineORMAdapter($qb);
      $pagerfanta = new Pagerfanta($adapter);
      return $this->render('index/allgigs.html.twig', [
      'my_pager' => $pagerfanta,
      ]);
    }
}
