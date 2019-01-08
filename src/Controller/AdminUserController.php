<?php
/**
 * Created by PhpStorm.
 * User: wilder4
 * Date: 03/12/18
 * Time: 15:01
 */

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminUserController extends AbstractController
{
    /**
     * @Route("/users", name="user_admin_index", methods="GET")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('Admin/userIndex.html.twig', ['users' => $userRepository->findAll()]);
    }
}
