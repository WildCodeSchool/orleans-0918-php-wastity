<?php

namespace App\Controller;

use App\Entity\Association;

use App\Entity\Offer;
use App\Entity\Status;
use App\Form\AssociationType;
use App\Repository\AssociationRepository;
use App\Repository\OfferRepository;
use App\Entity\Schedule;
use App\Form\AssociationScheduleType;
use App\Repository\DaysOfWeekRepository;
use App\Repository\StatusRepository;
use App\Service\DistanceCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

/**
 * @Route("/association")
 */
class AssociationController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/new", name="association_new", methods="GET|POST")
     */
    public function new(Request $request, DaysOfWeekRepository $daysOfWeekRepository): Response
    {
        $association = new Association();
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $days = $daysOfWeekRepository->findAll();
        foreach ($days as $day) {
            $schedule = new Schedule();
            $schedule->setDay($day);
            $association->addSchedule($schedule);
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $association->setUser($user);
            $em->persist($association);
            $em->flush();

            return $this->redirectToRoute('association_list_offers', ['id' => $association->getId()]);
        }

        return $this->render('Visitor/Association/new.html.twig', [
            'association' => $association,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="association_show", methods="GET")
     */
    public function show(Association $association): Response
    {
        return $this->render('Visitor/Association/show.html.twig', [
            'association' => $association,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="association_edit", methods="GET|POST")
     */
    public function edit(Request $request, Association $association): Response
    {
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('association_show', ['id' => $association->getId()]);
        }

        return $this->render('Visitor/Association/edit.html.twig', [
            'association' => $association,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("view", subject="association")
     * @Route("/{id}/showAssociation", name="association_show", methods="GET")
     * @param Association $association
     * @return Response
     */
    public function showAssociation(Association $association): Response
    {

        return $this->render('Visitor/Association/show.html.twig', [
            'association' => $association,
        ]);
    }

    /**
     * @param Request $request
     * @param Association $association
     * @return Response
     * @Route("/{id}", name="association_delete", methods="DELETE")
     */
    public function delete(Request $request, Association $association): Response
    {
        if ($this->isCsrfTokenValid('delete' . $association->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($association);
            $em->flush();
        }

        return $this->redirectToRoute('association_index');
    }

    /**
     * @param OfferRepository $offerRepository
     * @param Association $association
     * @Route("/{id}/offers", name="association_list_offers", methods="GET")
     * @return Response
     * @throws \Exception
     */
    public function listOffers(Association $association, OfferRepository $offerRepository)
    {
        $offers = $offerRepository->findAllBeforeEndDateAssociation(new \DateTime());

        return $this->render('Visitor/Association/listOffers.html.twig', [
            'offers' => $offers,
            'association' => $association,
        ]);
    }

    /**
     * @IsGranted("view", subject="association")
     * @param OfferRepository $offerRepository
     * @param Association $association
     * @Route("/{id}/record", name="association_record", methods="GET")
     * @return Response
     * @throws \Exception
     */
    public function record(Association $association, OfferRepository $offerRepository)
    {
        $offers = $offerRepository->findAcceptedByAssociationBeforeEndDate(new \DateTime(), $association);

        return $this->render('Visitor/Association/record.html.twig', [
            'offers' => $offers,
            'association' => $association,
        ]);
    }

    /**
     * @Route("/{association}/oneOffer/{offer}", name="association_offer_card")
     * @return Response
     * @throws \Exception
     */
    public function showOneOffer(Association $association, Offer $offer, DistanceCalculator $distanceCalculator)
    {
        $company = $offer->getCompany();
        $distance = $distanceCalculator->calculateDistanceFromAdresses($company, $association);

        return $this->render('Visitor/Association/showCard.html.twig', [
            'association' => $association,
            'distance' => $distance,
            'offer' => $offer,
        ]);
    }

    /**
     * @Route("/{association}/offer/{offer}", name="association_show_offer", methods="GET")
     * @param Association $association
     * @param Offer $offer
     * @return Response
     */
    public function showOffer(Association $association, Offer $offer, DistanceCalculator $distanceCalculator
    {
        $company = $offer->getCompany();
        $distance = $distanceCalculator->calculateDistance($company, $association);
        return $this->render('Visitor/Association/showOffer.html.twig', [
            'offer' => $offer,
            'association' => $association,
            'distance' => $distance,
        ]);
    }


    /**
     * @Route("/{association}/offer/{offer}/accept", name="association_accept_offer", methods="GET")
     * @param Association $association
     * @param Offer $offer
     * @return Response
     */
    public function acceptOffer(Association $association, Offer $offer, StatusRepository $statusRepository)
    {
        $status = $statusRepository->findOneByConstStatus('FoodHeroResearch');

        $em = $this->getDoctrine()->getManager();
        $offer->setAssociation($association);
        $offer->setStatus($status);
        $em->flush();

        return $this->redirectToRoute('association_list_offers', ['id' => $association->getId()]);
    }

    /**
     * @Route("/{id}/editschedule", name="association_edit_schedule", methods="GET|POST")
     * @param Request $request
     * @param association $association
     * @return Response
     */
    public function editSchedule(Request $request, Association $association, DaysOfWeekRepository $daysOfWeekRepository)
    {
        $form = $this->createForm(AssociationScheduleType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('association_show', ['id' => $association->getId()]);
        }

        return $this->render('Visitor/Association/editSchedule.html.twig', [
            'association' => $association,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/statistics", name="association_show_statistics", methods="GET|POST")
     * @param association $association
     * @return Response
     * @IsGranted("view", subject="association")
     */
    public function showStat(
        Association $association,
        OfferRepository $offerRepository,
        StatusRepository $statusRepository
    ): Response {
        $deliveredStatus = $statusRepository->findOneByConstStatus('Delivered');
        $offers = $offerRepository->findBy(['association' => $association, 'status' => $deliveredStatus]);
        $weightTotal = 0;
        $companies = [];
        foreach ($offers as $offer) {
            if ($offer->getAssociation()) {
                $weight = $offer->getWeight();
                $weightTotal += $weight;
                $companies[] = $offer->getassociation();
            }
        }
        $countCompany = count(array_unique($companies));
        return $this->render('Visitor/Association/showStatistics.html.twig', [
            'association' => $association,
            'offers' => $offers,
            'weightTotal' => $weightTotal,
            'countCompany' => $countCompany,
        ]);
    }
}
