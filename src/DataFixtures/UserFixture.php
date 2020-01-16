<?php

namespace App\DataFixtures;

use App\Constant\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * UserFixture constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('administrator@2ca-sdis.com')
            ->setLastname('2CA')
            ->setFirstname('Administrator')
            ->setPassword($this->userPasswordEncoder->encodePassword($user, 'Administrator'))
            ->addRole(Role::ROLE_ADMIN);

        $manager->persist($user);
        $manager->flush();
    }
}
