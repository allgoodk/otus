<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;


    /**
     * @var Generator
     */
    private $faker;


    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();

        for ($i = 0; $i < 1000; $i++) {
            $user = new User();
            $user->setBirthday(new DateTime());
            $user->setCity($this->faker->city)->setEmail($this->faker->email)->setFirstName($this->faker->firstName)
                ->setLastName($this->faker->lastName)->setInterests($this->faker->randomLetter)->setSex('male')
                ->setPassword($this->encoder->encodePassword($user, '123456'))->setRoles(['ROLE_USER']);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
