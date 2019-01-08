<?php
/**
 * Created by PhpStorm.
 * User: wilder4
 * Date: 03/12/18
 * Time: 14:19
 */

namespace App\Controller;

use App\Entity\Association;
use App\Repository\AssociationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminAssociationController extends Controller
{
    /**
     * @Route("/associations", name="association_index", methods="GET")
     */
    public function index(AssociationRepository $associationRepository, Request $request): Response
    {
        $associations = $associationRepository->findAll();

        $paginator  = $this->get('knp_paginator');
        // Paginate the results of the query
        $appointments = $paginator->paginate(
            $associations,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            20
        );

        return $this->render('Admin/associationIndex.html.twig', [
            'appointments' => $appointments
        ]);
    }

    /**
     * @Route("/association/{id}", name="association_admin_show", methods="GET")
     */
    public function show(Association $association): Response
    {
        return $this->render('Admin/associationShow.html.twig', ['association' => $association]);
    }
}
