<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil_index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index():Response
    {
        return $this->render('defineProfil.html.twig');
    }

}