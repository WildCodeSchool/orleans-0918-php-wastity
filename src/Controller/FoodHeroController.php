<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\FoodHero;
use App\Entity\Offer;
use App\Form\FoodHeroType;
use App\Repository\OfferRepository;
use App\Service\DistanceCalculator;
use App\Repository\StatusRepository;
use App\Service\FindCoordinates;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/foodhero")
 */
class FoodHeroController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/new", name="foodhero_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $foodHero = new FoodHero();
        $form = $this->createForm(FoodHeroType::class, $foodHero);
        $form->handleRequest($request);

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $foodHero->setUser($user);
            $em->persist($foodHero);
            $em->flush();
            $this->addFlash('success', "Votre profil Food-Hero a bien été crée !");

            return $this->redirectToRoute('foodhero_edit', ['id' => $foodHero->getId()]);
        }

        return $this->render('Visitor/FoodHero/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="foodhero_show", methods="GET")
     * @IsGranted("view", subject="foodHero")
     */
    public function show(FoodHero $foodHero): Response
    {
        return $this->render('Visitor/FoodHero/show.html.twig', ['foodhero' => $foodHero]);
    }

    /**
     * @Route("/{id}/edit", name="foodhero_edit", methods="GET|POST")
     * @IsGranted("view", subject="foodHero")
     */
    public function edit(Request $request, FoodHero $foodHero): Response
    {
        $form = $this->createForm(FoodHeroType::class, $foodHero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Vos modifications ont bien été appliquées !");

            return $this->redirectToRoute('foodhero_edit', ['id' => $foodHero->getId()]);
        }

        return $this->render('Visitor/FoodHero/edit.html.twig', [
            'foodhero' => $foodHero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="foodhero_delete", methods="DELETE")
     * @IsGranted("view", subject="foodHero")
     */
    public function delete(Request $request, FoodHero $foodHero): Response
    {
        if ($this->isCsrfTokenValid('delete' . $foodHero->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($foodHero);
            $em->flush();
            $this->addFlash('success', "Votre compte Food-Hero a bien été supprimé !");
        }

        return $this->redirectToRoute('foodhero_index');
    }

    /**
     * @param FoodHero $foodHero
     * @param OfferRepository $offerRepository
     * @return Response
     * @throws \Exception
     * @Route("/{id}/offers", name="foodhero_list_offers", methods="GET")
     */
    public function listOffers(
        FoodHero $foodHero,
        OfferRepository $offerRepository,
        Request $request,
        PaginatorInterface $paginator
    ) {
        $offers = $offerRepository->findAllBeforeEndDateFoodhero(new \DateTime());

        $appointments = $paginator->paginate(
            $offers,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            8
        );

        return $this->render('Visitor/FoodHero/listOffers.html.twig', [
            'appointments' => $appointments,
            'foodhero' => $foodHero
        ]);
    }

    /**
     * @IsGranted("view", subject="foodHero")
     * @param FoodHero $foodHero
     * @param OfferRepository $offerRepository
     * @return Response
     * @throws \Exception
     * @Route("/{id}/pendingOffers", name="foodhero_list_pendingOffers", methods="GET")
     */
    public function listOffersAccepted(
        FoodHero $foodHero,
        OfferRepository $offerRepository,
        Request $request,
        PaginatorInterface $paginator
    ) {
        $offers = $offerRepository->findAcceptedByFoodHero(new \DateTime(), $foodHero);

        $appointments = $paginator->paginate(
            $offers,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            8
        );

        return $this->render('Visitor/FoodHero/listOffersAccepted.html.twig', [
            'appointments' => $appointments,
            'foodhero' => $foodHero,
        ]);
    }

    /**
     * @Route("/{foodhero}/distance/{offer}", name="foodhero_distance_offer", methods="GET")
     * @param FoodHero $foodhero
     * @param Offer $offer
     * @param DistanceCalculator $distanceCalculator
     * @param SessionInterface $session
     * @return Response
     */

    public function showDistanceOffer(
        FoodHero $foodhero,
        Offer $offer,
        DistanceCalculator $distanceCalculator,
        SessionInterface $session
    ): Response {
        $distance = null;
        $distanceTotal = null;
        $company = $offer->getCompany();
        $association = $offer->getAssociation();
        $distanceAssoComp = $distanceCalculator->calculateDistanceFromAddresses($company, $association);

        if ($session->has('latitude')) {
            $distance = $distanceCalculator->calculateDistanceFromGps(
                $session->get('latitude'),
                $session->get('longitude'),
                $company
            );
            $distanceTotal = $distance + $distanceAssoComp;
        }
        return $this->render('Visitor/FoodHero/showDistanceOffer.html.twig', [
            'foodhero' => $foodhero,
            'distance' => $distance,
            'distanceTotal' => $distanceTotal,
            'offer' => $offer,
        ]);
    }


    /**
     * @Route("/{foodhero}/offer/{offer}", name="foodhero_show_offer", methods="GET")
     * @param FoodHero $foodhero
     * @param Offer $offer
     * @return Response
     */

    public function showOffer(
        FoodHero $foodhero,
        Offer $offer
    ): Response {
        return $this->render('Visitor/FoodHero/showOffer.html.twig', [
            'foodhero' => $foodhero,
            'offer' => $offer,
        ]);
    }

    /**
     * @Route("/{foodhero}/offer/{offer}/accept", name="foodhero_accept_offer", methods="GET")
     * @param FoodHero $foodhero
     * @param Offer $offer
     * @return Response
     */

    public function acceptOffer(FoodHero $foodhero, Offer $offer, StatusRepository $statusRepository): Response
    {
        $status = $statusRepository->findOneByConstStatus('WaitingForRecuperation');

        $em = $this->getDoctrine()->getManager();
        $offer->setStatus($status);
        $offer->setFoodhero($foodhero);
        $em->flush();
        $this->addFlash('success', "L'offre a bien été acceptée !");

        return $this->redirectToRoute('foodhero_list_offers', ['id' => $foodhero->getId()]);
    }


    /**
     * @Route("/position/{latitude}/{longitude}")
     * @param float $latitude
     * @param float $longitude
     * @param SessionInterface $session
     * @return Response
     */
    public function showCoordinates(?float $latitude, ?float $longitude, SessionInterface $session): Response
    {

        $session->set('latitude', $latitude);
        $session->set('longitude', $longitude);
        return new Response("");
    }


    /**
     * @Route("/offer/{offer}/collect", name="foodhero_collect_offer", methods="GET")
     * @param Offer $offer
     * @param StatusRepository $statusRepository
     * @return Response
     */
    public function collectOffer(Offer $offer, StatusRepository $statusRepository)
    {
        $status = $statusRepository->findOneByConstStatus('WaitingForDelivery');

        $em = $this->getDoctrine()->getManager();
        $offer->setStatus($status);
        $offer->setFoodhero($this->getUser()->getFoodHero());
        $em->flush();
        $this->addFlash('success', "L'offre a bien été collectée !");

        return $this->redirectToRoute('foodhero_list_pendingOffers', [
            'id' => $this->getUser()->getFoodHero()->getId()
        ]);
    }

    /**
     * @Route("/offer/{offer}/delivered", name="foodhero_delivered_offer", methods="GET")
     * @param Offer $offer
     * @param StatusRepository $statusRepository
     * @return Response
     */
    public function deliveredOffer(Offer $offer, StatusRepository $statusRepository)
    {
        $status = $statusRepository->findOneByConstStatus('Delivered');

        $em = $this->getDoctrine()->getManager();
        $offer->setStatus($status);
        $em->flush();
        $this->addFlash('success', "L'offre a bien été livrée !");

        return $this->redirectToRoute('foodhero_list_pendingOffers', [
            'id' => $this->getUser()->getFoodHero()->getId()
        ]);
    }

    /**
     * @Route("/{id}/record", name="foodhero_record", methods="GET")
     * @param FoodHero $foodHero
     * @param OfferRepository $offerRepository
     * @return Response
     * @throws \Exception
     * @IsGranted("view", subject="foodHero")
     */
    public function record(
        FoodHero $foodHero,
        OfferRepository $offerRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response {
        $offers = $offerRepository->findAcceptedByFoodHeroBeforeEndDate(new \DateTime(), $foodHero);

        $offers = $paginator->paginate(
            $offers,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            20
        );

        return $this->render('Visitor/FoodHero/record.html.twig', [
            'offers' => $offers,
            'foodhero' => $foodHero,
        ]);
    }
}
