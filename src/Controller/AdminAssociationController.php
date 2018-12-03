<?php
/**
 * Created by PhpStorm.
 * User: wilder4
 * Date: 03/12/18
 * Time: 14:19
 */

namespace App\Controller;

use App\Repository\AssociationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminAssociationController extends AbstractController
{
    /**
     * @Route("/associations", name="association_index", methods="GET")
     */
    public function index(AssociationRepository $associationRepository): Response
    {
        return $this->render('Admin/associationIndex.html.twig', [
            'associations' => $associationRepository->findAll()
        ]);
    }
}
