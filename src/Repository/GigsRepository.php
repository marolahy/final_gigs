<?php
namespace App\Repository;
use App\Entity\Gigs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
class GigsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Gigs::class);
    }
    public function findAllQueryBuilder()
    {
      return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC')
            ->getQuery();


    }
}
