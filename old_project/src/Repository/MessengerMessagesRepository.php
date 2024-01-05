<?php

namespace App\Repository;

use App\Entity\MessengerMessages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MessengerMessages|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessengerMessages|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessengerMessages[]    findAll()
 * @method MessengerMessages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessengerMessagesRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessengerMessages::class);
    }
}
