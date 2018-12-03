<?php
/**
 * Created by PhpStorm.
 * User: wilder4
 * Date: 03/12/18
 * Time: 15:01
 */

namespace App\Controller;

use App\Repository\FoodHeroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminFoodHeroController extends AbstractController
{
    /**
     * @Route("/foodheroes", name="foodhero_index", methods="GET")
     */
    public function index(FoodHeroRepository $foodHeroRepository): Response
    {
        return $this->render('Admin/foodHeroIndex.html.twig', ['food_heroes' => $foodHeroRepository->findAll()]);
    }
}