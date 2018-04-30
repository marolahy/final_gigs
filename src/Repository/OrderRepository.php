<?php
namespace App\Repository;
use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
class OrderRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
      parent::__construct($registry, Order::class);
  }

  public function count()
  {
    return $this->createQueryBuilder('p')
          ->select('count(p.id) as nbre')
          ->getQuery()
          ->getOneOrNullResult();
  }
}
