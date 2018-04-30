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
    public function findPerCategory($cid)
    {
      return $this->createQueryBuilder('p')
            ->where('p.category=:cid')
            ->orderBy('p.id', 'ASC')
            ->setParameter('cid',$cid)
            ->getQuery();
    }

    public function count()
    {
      return $this->createQueryBuilder('p')
            ->select('count(p.id) as nbre')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
