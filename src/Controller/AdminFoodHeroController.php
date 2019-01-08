<?php
/**
 * Created by PhpStorm.
 * User: wilder4
 * Date: 03/12/18
 * Time: 15:01
 */

namespace App\Controller;

use App\Repository\FoodHeroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminFoodHeroController extends Controller
{
    /**
     * @Route("/foodheroes", name="foodhero_index", methods="GET")
     */
    public function index(FoodHeroRepository $foodHeroRepository, Request $request): Response
    {
        $foodheroes = $foodHeroRepository->findAll();

        $paginator  = $this->get('knp_paginator');
        // Paginate the results of the query
        $appointments = $paginator->paginate(
            $foodheroes,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            20
        );

        return $this->render('Admin/foodHeroIndex.html.twig', ['appointments' => $appointments]);
    }
}
