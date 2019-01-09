<?php
/**
 * Created by PhpStorm.
 * User: wilder4
 * Date: 03/12/18
 * Time: 15:01
 */

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(UserRepository $userRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $users = $userRepository->findAll();
        dump($users);

        $users = $paginator->paginate(
            $users,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            20
        );

        return $this->render('Admin/userIndex.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/users/{id}/activate", name="user_admin_activate", methods="GET")
     */
    public function userActivate(User $user): Response
    {
        $active = $user->getActivate();
        $user->setActivate(!$active);
        if ($active == true) {
            $this->addFlash('success', "L'utilisateur à bien été activée !");
        } else {
            $this->addFlash('danger', "L'utilisateur à bien été désactivée !");
        }
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('user_admin_index');
    }
}
