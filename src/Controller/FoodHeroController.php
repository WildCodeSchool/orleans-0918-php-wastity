<?php

namespace App\Controller;

use App\Entity\FoodHero;
use App\Entity\Offer;
use App\Form\FoodHeroType;
use App\Repository\FoodHeroRepository;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

            return $this->redirectToRoute('foodhero_edit', ['id' => $foodHero->getId()]);
        }

        return $this->render('Visitor/FoodHero/new.html.twig', [
            'foodHero' => $foodHero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="foodhero_show", methods="GET")
     */
    public function show(FoodHero $foodHero): Response
    {
        return $this->render('Visitor/FoodHero/show.html.twig', ['FoodHero' => $foodHero]);
    }

    /**
     * @Route("/{id}/edit", name="foodhero_edit", methods="GET|POST")
     */
    public function edit(Request $request, FoodHero $foodHero): Response
    {
        $form = $this->createForm(FoodHeroType::class, $foodHero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('foodhero_index');
        }

        return $this->render('Visitor/FoodHero/edit.html.twig', [
            'foodhero' => $foodHero,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="foodhero_delete", methods="DELETE")
     */
    public function delete(Request $request, FoodHero $foodHero): Response
    {
        if ($this->isCsrfTokenValid('delete'.$foodHero->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($foodHero);
            $em->flush();
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
    public function listOffers(FoodHero $foodHero, OfferRepository $offerRepository)
    {
        $offers = $offerRepository->findAllBeforeEndDateFoodhero(new \DateTime());
        
        return $this->render('Visitor/FoodHero/listOffers.html.twig', [
            'offers' => $offers,
            'foodhero' => $foodHero
        ]);
    }
    
    /**
     * @Route("/{foodhero}/offer/{offer}", name="foodhero_show_offer", methods="GET")
     * @param FoodHero $foodhero
     * @param Offer $offer
     * @return Response
     */
    public function showOffer(FoodHero $foodhero, Offer $offer)
    {
        return $this->render('Visitor/FoodHero/showOffer.html.twig', [
            'offer' => $offer,
            'foodhero' => $foodhero
        ]);
    }
    
    
    /**
     * @Route("/{foodhero}/offer/{offer}/accept", name="foodhero_accept_offer", methods="GET")
     * @param FoodHero $foodhero
     * @param Offer $offer
     * @return Response
     */
    public function acceptOffer(FoodHero $foodhero, Offer $offer)
    {
        $em = $this->getDoctrine()->getManager();
        $offer->setFoodhero($foodhero);
        $em->flush();
        
        return $this->redirectToRoute('foodhero_list_offers', ['id' => $foodhero->getId()]);
    }
    
}
