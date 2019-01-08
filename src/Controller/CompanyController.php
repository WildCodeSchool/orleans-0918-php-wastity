<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Offer;
use App\Entity\DaysOfWeek;
use App\Entity\Schedule;
use App\Form\CompanyScheduleType;
use App\Form\CompanyType;
use App\Form\OfferType;
use App\Repository\CompanyRepository;
use App\Repository\DaysOfWeekRepository;
use App\Repository\OfferRepository;
use App\Repository\StatusRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/company")
 */
class CompanyController extends Controller
{
    /**
     * @Route("/new", name="company_new", methods="GET|POST")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, DaysOfWeekRepository $daysOfWeekRepository): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        $days = $daysOfWeekRepository->findAll();
        foreach ($days as $day) {
            $schedule = new Schedule();
            $schedule->setDay($day);
            $company->addSchedule($schedule);
        }

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $company->setUser($user);
            $em->persist($company);
            $em->flush();
            $this->addFlash('success', "Votre entreprise à bien été enregistrée !");

            return $this->redirectToRoute('company_show_offers', ['id' => $company->getId()]);
        }

        return $this->render('Visitor/Company/new.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/offers", name="company_show_offers", methods="GET")
     * @param Company $company
     * @param OfferRepository $offerRepository
     * @return Response
     * @IsGranted("view", subject="company")
     */
    public function listOffers(Company $company, Request $request)
    {
        $offers = $company->getOffers();

        $paginator  = $this->get('knp_paginator');

        $appointments = $paginator->paginate(
            $offers,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            8
        );

        return $this->render('Visitor/Company/listOffers.html.twig', [
            'company' => $company,
            'appointments' => $appointments,
        ]);
    }

    /**
     * @Route("/{company}/oneOffer/{offer}", name="company_offer_card")
     * @return Response
     * @throws \Exception
     */
    public function showOneOffer(
        Company $company,
        Offer $offer
    ): Response {

        return $this->render('Visitor/Company/showCard.html.twig', [
            'company' => $company,
            'offer' => $offer,
        ]);
    }

    /**
     * @Route("/{id}/record", name="company_record", methods="GET")
     * @param Company $company
     * @return Response
     * @IsGranted("view", subject="company")
     */
    public function record(Company $company): Response
    {

        return $this->render('Visitor/Company/record.html.twig', [
            'company' => $company,
        ]);
    }

    /**
     * @Route("/{id}/showCompany", name="company_show", methods="GET")
     * @param Company $company
     * @return Response
     * @IsGranted("view", subject="company")
     */
    public function showCompany(Company $company): Response
    {

        return $this->render('Visitor/Company/show.html.twig', [
            'company' => $company,
        ]);
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

            $this->addFlash('success', "Vos modifications ont été enregistrées !");

            return $this->redirectToRoute('company_show', ['id' => $company->getId()]);
        }
        return $this->render('Visitor/Company/edit.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{company}/offer/{offer}/edit", name="company_offer_edit", methods="GET|POST")
     * @param Request $request
     * @param Offer $offer
     * @return Response
     */
    public function editOffer(Request $request, Company $company, Offer $offer): Response
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'L\'offre à bien été modifiée !');
            return $this->redirectToRoute('company_show_offers', ['id' => $offer->getCompany()->getId()]);
        }

        return $this->render('Visitor/Offer/edit.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
            'company' => $company,
        ]);
    }

    /**
     * @Route("/{id}", name="company_delete", methods="DELETE")
     * @param Request $request
     * @param Company $company
     * @return Response
     */
    public function delete(Request $request, Company $company): Response
    {
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($company);
            $em->flush();
        }

        $this->addFlash('success', 'Votre entreprise à bien été supprimée !');

        return $this->redirectToRoute('company_index');
    }


    /**
     * @Route("/{id}/editschedule", name="company_edit_shedule", methods="GET|POST")
     * @param Request $request
     * @param company $company
     * @return Response
     */
    public function editSchedule(
        Request $request,
        Company $company,
        DaysOfWeekRepository $daysOfWeekRepository
    ): Response {
        $form = $this->createForm(CompanyScheduleType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Les nouveaux horaires ont bien été enregistrés !");

            return $this->redirectToRoute('company_show', ['id' => $company->getId()]);
        }

        return $this->render('Visitor/Company/editSchedule.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/statistics", name="company_show_statistics", methods="GET|POST")
     * @param company $company
     * @return Response
     * @IsGranted("view", subject="company")
     */
    public function showStatistics(
        Company $company,
        OfferRepository $offerRepository,
        StatusRepository $statusRepository
    ): Response {
        $deliveredStatus = $statusRepository->findOneByConstStatus('Delivered');
        $offers = $offerRepository->findBy(['company'=>$company, 'status'=>$deliveredStatus]);
        $weightTotal = 0;
        $associations = [];
        foreach ($offers as $offer) {
            if ($offer->getAssociation()) {
                $weight = $offer->getWeight();
                $weightTotal += $weight;
                $associations[] = $offer->getAssociation()->getId();
            }
        }
        $countAssociation = count(array_unique($associations));
        return $this->render('Visitor/Company/showStatistics.html.twig', [
            'company' => $company,
            'offers' => $offers,
            'weightTotal' => $weightTotal,
            'countAssociation'=>$countAssociation,
        ]);
    }
}
