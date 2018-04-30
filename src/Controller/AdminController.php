<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Category;
use App\Entity\Gigs;
use App\Entity\GigImages;
use App\Entity\Order;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{

  /**
   * @Route("/", name="dashboard")
   */
  public function dashboard()
  {
    $gigs = $this->getDoctrine()->getRepository(Gigs::class)->count();
    $orders = $this->getDoctrine()->getRepository(Order::class)->count();

    return $this->render('admin/dashboard.html.twig', [
        'name'=>'Dashboard',
        'class'=>'dashboard',
        'gigs'=> $gigs['nbre'],
        'orders'=>$orders['nbre'],
    ]);

  }



}
