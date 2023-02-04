<?php

namespace App\Repository;

use App\Entity\ProfilePicture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProfilePicture>
 *
 * @method ProfilePicture|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfilePicture|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfilePicture[]    findAll()
 * @method ProfilePicture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ProfilePictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfilePicture::class);
    }

    public function save(ProfilePicture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProfilePicture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
