<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FoodHeroController extends AbstractController
{
    /**
     * @Route("/foodHero", name="foodHero_index")
     */
    public function index():Response
    {
        return $this->render('foodHero/index.html.twig');
    }
}
