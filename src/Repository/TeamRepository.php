<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Requêtes DQL/QueryBuilder liées à l’entité Team.
 *
 * @extends ServiceEntityRepository<Team>
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        // Enregistre l’entité auprès de Doctrine (nécessaire pour find(), etc.)
        parent::__construct($registry, Team::class);
    }

    // Toutes les équipes, ordre par défaut (souvent l’id)
    public function findAllTeam()
    {
        $qb = $this->createQueryBuilder('team')
            ->select('team')
            ->getQuery()
            ->getResult();
            

        return $qb;
    }

    // Toutes les équipes triées par nom (Z → A ici, car DESC)
    public function findAllTeamsDesc()
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t')
            ->orderBy('t.name', 'DESC')
            ->getQuery()
            ->getResult();
            

        return $qb;
    }
    
    // Nom contient la lettre (LIKE) et longueur stric. supérieure à $length (LENGTH en DQL)
    public function findTeamByLetterAndLenght(string $letter, $length)
    {
        // LIKE : les % font partie de la valeur ; le 3e paramètre de setParameter = type DBAL, pas un joker
        $qb = $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.name LIKE :letter')
            ->andWhere('LENGTH(t.name) > :length')
            ->setParameter('length', $length)
            ->setParameter('letter', '%' . $letter . '%')
            ->getQuery()
            ->getResult();

        return $qb;
    }

    // Une équipe par clé primaire, ou null si l’id n’existe pas
    public function findTeamById(int $id): ?Team
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.id = :id')
            ->setParameter('id', $id)
            // Une seule ligne attendue : getOneOrNullResult, pas getResult
            ->getQuery()
            ->getOneOrNullResult();

        return $qb;
    }
}
