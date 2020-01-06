<?php


namespace App\Controller;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * ProfileController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route(path="/profile/{username}" , name="app_profile")
     */
    public function profileAction(string $username)
    {
        $userData = $this->userRepository->getUserByUserName($username);

        return $this->render('profile/index.html.twig', current($userData));
    }
}