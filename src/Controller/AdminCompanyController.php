<?php
/**
 * Created by PhpStorm.
 * User: wilder4
 * Date: 03/12/18
 * Time: 14:22
 */

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminCompanyController extends AbstractController
{
    /**
     * @Route("/companies", name="company_index", methods="GET")
     * @param CompanyRepository $companyRepository
     * @return Response
     */
    public function index(CompanyRepository $companyRepository): Response
    {

        return $this->render('Admin/companyIndex.html.twig', ['companies' => $companyRepository->findAll()]);
    }


    /**
     * @Route("/company/{id}", name="company_admin_show", methods="GET")
     */
    public function show(Company $company): Response
    {
        return $this->render('Admin/companyShow.html.twig', ['company' => $company]);
    }
}
