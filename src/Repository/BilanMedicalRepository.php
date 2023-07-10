<?php

namespace App\Repository;

use App\Entity\BilanMedical;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BilanMedical>
 *
 * @method BilanMedical|null find($id, $lockMode = null, $lockVersion = null)
 * @method BilanMedical|null findOneBy(array $criteria, array $orderBy = null)
 * @method BilanMedical[]    findAll()
 * @method BilanMedical[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BilanMedicalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BilanMedical::class);
    }

    public function save(BilanMedical $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BilanMedical $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function getPatientt($id)  {
        $qb= $this->createQueryBuilder('s') 
            ->join('s.user','c')
            ->addSelect('c')
            ->where('c.id=:id')
            ->setParameter('id',$id);
        return $qb->getQuery()
            ->getResult();
    }
    public function getPatienttt($id)  {
        $qb= $this->createQueryBuilder('s') 
            ->join('s.dossierMedical','c')
            ->addSelect('c')
            ->where('c.id=:id')
            ->setParameter('id',$id);
        return $qb->getQuery()
            ->getResult();
    }
    public function search($id) {
        $qb=  $this->createQueryBuilder('s')
            ->where('s.id LIKE :x')
            ->setParameter('x',$id);
        return $qb->getQuery()
            ->getResult();
    }
    public function sortBymoyenne()
    {   //creation d'une requete / x->alias de student 
        $qb=$this->createQueryBuilder('x')->orderBy('x.Poids','ASC'); 
        return $qb->getQuery()->getResult() ; 
    }
  

//    /**
//     * @return BilanMedical[] Returns an array of BilanMedical objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BilanMedical
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
