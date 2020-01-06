<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;
use PDO;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use function get_class;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function createNewUser(User $user)
    {
        $connection = $this->_em->getConnection();
        $sql = 'INSERT INTO user (
                                    city, 
                                    email, 
                                    first_name, 
                                    last_name,
                                    birthday,
                                    password,
                                    interests,
                                    sex  
                                      ) VALUES (
                                    :city,
                                    :email,
                                    :first_name,
                                    :last_name,
                                    :birthday,
                                    :password,
                                    :interests,
                                    :sex
                ) ';

        $stmt = $connection->prepare($sql);
        $stmt->execute([
            'city' => $user->getCity(),
            'email' => $user->getEmail(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'birthday' => $user->getBirthday()->format('Y-m-d H:i:s'),
            'password' => $user->getPassword(),
            'interests' => $user->getInterests(),
            'sex' => $user->getSex()
        ]);
    }

    /**
     * Get array  of render data for user
     *
     * @param string $username
     * @return mixed[]
     * @throws DBALException
     */
    public function getUserByUserName(string $username): array
    {
        $conn = $this->_em->getConnection();

        $sql = 'SELECT first_name, last_name, birthday, city, interests, sex FROM user WHERE email = :username';

        $stmt = $conn->prepare($sql);

        $stmt->execute(['username' => $username]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
