<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    /**
     * @Route("/company", name="test")
     */
    public function index()
    {
        return $this->render('Company/layoutCompany.html.twig');
    }
}
