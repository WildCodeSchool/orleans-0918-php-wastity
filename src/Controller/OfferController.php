<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/offer")
 */
class OfferController extends AbstractController
{
    /**
     * @Route("/", name="active_offer_index", methods="GET")
     * @param OfferRepository $offerRepository
     * @return Response
     * @throws \Exception
     */
    public function showActiveOffers(OfferRepository $offerRepository): Response
    {
        $date = new \DateTime();
        
        return $this->render(
            'Visitor/Offer/index.html.twig',
            ['offers' => $offerRepository->findAllBeforeEndDate($date)]
        );
    }
    
    /**
     * @Route("/association", name="active_offers_index_association", methods="GET")
     * @param OfferRepository $offerRepository
     * @return Response
     * @throws \Exception
     */
    public function showActiveOffersForAssociations(OfferRepository $offerRepository): Response
    {
        $date = new \DateTime();
        
        return $this->render(
            'Visitor/Offer/indexForAssociation.html.twig',
            ['offers' => $offerRepository->findAllBeforeEndDate($date)]
        );
    }

    /**
     * @Route("/new", name="offer_new", methods="GET|POST")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        $company= $this->getUser()->getCompany();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $offer->setCompany($company);
            $em->persist($offer);
            $em->flush();

            return $this->redirectToRoute('company_show_offers', ['id' => $offer->getCompany()->getId()]);
        }

        return $this->render('Visitor/Offer/new.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="offer_edit", methods="GET|POST")
     * @param Request $request
     * @param Offer $offer
     * @return Response
     */
    public function edit(Request $request, Offer $offer): Response
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('company_show_offers', ['id' => $offer->getCompany()->getId()]);
        }

        return $this->render('Visitor/Offer/edit.html.twig', [
            'offer' => $offer,
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
