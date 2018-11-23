<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/foodhero", name="foodhero_")
 */
class FoodHeroController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index():Response
    {
        return $this->render('foodHero/layout.html.twig');
    }
}
