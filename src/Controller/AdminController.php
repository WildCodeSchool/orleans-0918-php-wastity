<?php
/**
 * Created by PhpStorm.
 * User: wilder4
 * Date: 30/11/18
 * Time: 10:02
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index", methods="GET")
     */
    public function index(): Response
    {
        return $this->render('Admin/index.html.twig');
    }
}
