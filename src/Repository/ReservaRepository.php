<?php

namespace App\Repository;

use App\Entity\Reserva;
use App\Entity\Tramo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reserva>
 *
 * @method Reserva|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reserva|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reserva[]    findAll()
 * @method Reserva[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reserva::class);
    }

    public function save(Reserva $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reserva $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Devuelve las reservas de una fecha en un determinado tramo de tiempo 
     * 
     *  fecha -> YYYY:MM:DD\
     *  tramos -> HH:MM:SS
     */
    public function findByFechaTramo(string $fecha, string $tramoInicio, string $tramoFin)
    {

        // pasamos la hora a un array para acceder mas facilmente a el
        $horaInicio = explode(':', $tramoInicio);
        $horaFin = explode(':', $tramoFin);

        // creamo el ResultSetMapping diciendo que tendra que mapear el resultado acorde a la clase Reserva
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Reserva::class, 'r');

        // creamos una query nativa ya que el query builder da problemas a la hora de usar la funcion maketime
        // y devolvemos el resultado
        return $this->getEntityManager()->createNativeQuery("select * from reserva as r
        join tramo as t
        on r.tramo_inicio_id = t.id
        where r.fecha = '$fecha' and
        ((t.hora_inicio <= maketime(" . $horaInicio[0] . "," . $horaInicio[1] . "," . $horaInicio[2] . ") 
        and t.hora_fin >= maketime(" . $horaFin[0] . "," . $horaFin[1] . "," . $horaFin[2] . "))
        or
        ((t.hora_inicio < maketime(" . $horaFin[0] . "," . $horaFin[1] . "," . $horaFin[2] . ")) 
        and (t.hora_inicio > maketime(" . $horaInicio[0] . "," . $horaInicio[1] . "," . $horaInicio[2] . "))
        or
        (t.hora_fin > maketime(" . $horaInicio[0] . "," . $horaInicio[1] . "," . $horaInicio[2] . ")) 
        and (t.hora_fin < maketime(" . $horaFin[0] . "," . $horaFin[1] . "," . $horaFin[2] . "))))", $rsm)
            ->getResult();
    }

    //    /**
    //     * @return Reserva[] Returns an array of Reserva objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reserva
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
