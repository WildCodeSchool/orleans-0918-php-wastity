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
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use \Symfony\Component\HttpFoundation\Request;
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
    public function index(CompanyRepository $companyRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $companies = $companyRepository->findAll();

        // Paginate the results of the query
        $appointments = $paginator->paginate(
            $companies,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            20
        );

        return $this->render('Admin/companyIndex.html.twig', ['appointments' => $appointments]);
    }


    /**
     * @Route("/company/{id}", name="company_admin_show", methods="GET")
     */
    public function show(Company $company): Response
    {
        return $this->render('Admin/companyShow.html.twig', ['company' => $company]);
    }
}
