<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Offer;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/company")
 */
class CompanyController extends AbstractController
{
    /**
     * @Route("/", name="company_index", methods="GET")
     * @param CompanyRepository $companyRepository
     * @return Response
     */
    public function index(CompanyRepository $companyRepository): Response
    {

        return $this->render('Visitor/Company/index.html.twig', ['companies' => $companyRepository->findAll()]);
    }

    /**
     * @Route("/new", name="company_new", methods="GET|POST")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('company_index');
        }

        return $this->render('Visitor/Company/new.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="company_show", methods="GET")
     * @param Company $company
     * @return Response
     */
    public function show(Company $company): Response
    {
        return $this->render('Visitor/Offer/show.html.twig', ['company' => $company, "offers"=>$company->getOffers()]);
    }

    /**
     * @Route("/{id}/edit", name="company_edit", methods="GET|POST")
     * @param Request $request
     * @param company $company
     * @return Response
     */
    public function edit(Request $request, Company $company): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('company_index', ['id' => $company->getId()]);
        }

        return $this->render('Visitor/Company/edit.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="offer_delete", methods="DELETE")
     * @param Request $request
     * @param Offer $offer
     * @return Response
     */
    public function delete(Request $request, Offer $offer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offer->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($offer);
            $em->flush();
        }

        return $this->redirectToRoute('offer_index');
    }
}
