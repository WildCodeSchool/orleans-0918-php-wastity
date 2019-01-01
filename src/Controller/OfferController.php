<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Offer;
use App\Entity\Status;
use App\Form\OfferType;
use App\Repository\StatusRepository;
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
     * @Route("/new", name="offer_new", methods="GET|POST")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request, StatusRepository $statusRepository): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        $company= $this->getUser()->getCompany();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $offer->setCompany($company);

            $status = $statusRepository->findOneByConstStatus('AssociationResearch');

            $offer->setStatus($status);
            $em->persist($offer);
            $em->flush();

            return $this->redirectToRoute('company_show_offers', ['id' => $offer->getCompany()->getId()]);
        }

        return $this->render('Visitor/Offer/new.html.twig', [
            'company' => $company,
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
    public function edit(Request $request, Offer $offer, Company $company): Response
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('company_show_offers', ['id' => $offer->getCompany()->getId()]);
        }

        return $this->render('Visitor/Offer/edit.html.twig', [
            'company' => $company,
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
