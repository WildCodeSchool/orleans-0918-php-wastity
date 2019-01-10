<?php

namespace App\Controller;

use App\Entity\Association;
use App\Entity\Offer;
use App\Entity\Status;
use App\Entity\User;
use App\Form\AssociationMemberType;
use App\Form\AssociationType;
use App\Repository\AssociationRepository;
use App\Repository\OfferRepository;
use App\Entity\Schedule;
use App\Form\AssociationScheduleType;
use App\Repository\DaysOfWeekRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use App\Service\DistanceCalculator;
use Knp\Component\Pager\PaginatorInterface;
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

            $this->addFlash('success', "Votre association à bien été enregistrée !");

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

            $this->addFlash('success', "Vos modifications ont été enregistrées !");

            return $this->redirectToRoute('association_show', ['id' => $association->getId()]);
        }

        return $this->render('Visitor/Association/edit.html.twig', [
            'association' => $association,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("associationView", subject="association")
     * @Route("/{id}/showAssociation", name="association_show", methods="GET|POST")
     * @param Association $association
     * @return Response
     */
    public function showAssociation(
        Association $association,
        Request $request,
        UserRepository $userRepository
    ): Response {
        $form = $this->createForm(AssociationMemberType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            if ($userRepository->findOneByEmail($form->getData()['email'])) {
                $em = $this->getDoctrine()->getManager();
                $user = $userRepository->findOneByEmail($form->getData()['email']);
                $association->addMember($user);
                $em->flush();
                $this->addFlash('success', "Cet utilisateur a bien été ajouté");
            } else {
                $this->addFlash('danger', "Cet utilisateur n'existe pas");
            }
            return $this->redirectToRoute('association_show', ['id' => $association->getId()]);
        }
        
        return $this->render('Visitor/Association/show.html.twig', [
            'association' => $association,
            'form' => $form->createView(),
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
            $this->addFlash('success', "Votre association à bien été supprimée !");
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
    public function listOffers(
        Association $association,
        OfferRepository $offerRepository,
        PaginatorInterface $paginator,
        Request $request
    ) {
        $offers = $offerRepository->findAllBeforeEndDateAssociation(new \DateTime());

        $appointments = $paginator->paginate(
            $offers,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            8
        );

        return $this->render('Visitor/Association/listOffers.html.twig', [
            'appointments'=> $appointments,
            'association' => $association,
        ]);
    }

    /**
     * @IsGranted("associationView", subject="association")
     * @param OfferRepository $offerRepository
     * @param Association $association
     * @Route("/{id}/record", name="association_record", methods="GET")
     * @return Response
     * @throws \Exception
     */
    public function record(
        Association $association,
        OfferRepository $offerRepository,
        Request $request,
        PaginatorInterface $paginator
    ) {
        $offers = $offerRepository->findAcceptedByAssociationBeforeEndDate(new \DateTime(), $association);

        $offers = $paginator->paginate(
            $offers,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            20
        );
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
    public function showOneOffer(
        Association $association,
        Offer $offer,
        DistanceCalculator $distanceCalculator
    ): Response {
        $company = $offer->getCompany();
        $distance = $distanceCalculator->calculateDistanceFromAddresses($company, $association);

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
    public function showOffer(
        Association $association,
        Offer $offer,
        DistanceCalculator $distanceCalculator
    ): Response {
        $company = $offer->getCompany();
        $distance = $distanceCalculator->calculateDistanceFromAddresses($company, $association);
        return $this->render('Visitor/Association/showOffer.html.twig', [
            'offer' => $offer,
            'association' => $association,
            'distance' => $distance,
            'company'=>$company->getName(),
        ]);
    }


    /**
     * @Route("/{association}/offer/{offer}/accept", name="association_accept_offer", methods="GET")
     * @param Association $association
     * @param Offer $offer
     * @return Response
     */
    public function acceptOffer(
        Association $association,
        Offer $offer,
        StatusRepository $statusRepository
    ): Response {
        $status = $statusRepository->findOneByConstStatus('FoodHeroResearch');

        $em = $this->getDoctrine()->getManager();
        $offer->setAssociation($association);
        $offer->setStatus($status);
        $em->flush();
        $this->addFlash('success', "L'offre à bien été acceptée !");

        return $this->redirectToRoute('association_list_offers', ['id' => $association->getId()]);
    }

    /**
     * @Route("/{id}/editschedule", name="association_edit_schedule", methods="GET|POST")
     * @param Request $request
     * @param association $association
     * @return Response
     */
    public function editSchedule(
        Request $request,
        Association $association,
        DaysOfWeekRepository $daysOfWeekRepository
    ):Response {
        $form = $this->createForm(AssociationScheduleType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Vos horaires ont bien été modifiés !");

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
     * @IsGranted("associationView", subject="association")
     */
    public function showStatistics(
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
                $companies[] = $offer->getCompany()->getId();
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
    
    /**
     * @Route ("/{id}/removeMember/{user}", name="removeMemberAssociation", methods="POST")
     * @param Association $association
     * @param User $user
     * @return Response
     * @IsGranted("associationAdmin", subject="association")
     */
    public function deleteMember(Association $association, User $user, Request $request) :Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $association->removeMember($user);
            $em->flush();
            $this->addFlash('danger', "Cet utilisateur a bien été supprimé");
        }
        
        return $this->redirectToRoute('association_show', ['id' => $association->getId()]);
    }
}
