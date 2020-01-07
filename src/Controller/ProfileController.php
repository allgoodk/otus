<?php


namespace App\Controller;


use App\Repository\UserRepository;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\DatabaseObjectNotFoundException;
use Exception;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route(path="/profile/{username}", name="app_profile")
     * @param string $username
     *
     * @return Response
     */
    public function profileAction(string $username): Response
    {
        try {
            $userData = $this->userRepository->getUserByUsername($username);
        } catch (Exception $e) {
            throw $this->createNotFoundException($e->getMessage());
        }

        return $this->render('profile/index.html.twig', $userData);
    }
}