<?php
/**
 * Created by PhpStorm.
 * User: wilder4
 * Date: 03/12/18
 * Time: 15:03
 */

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminOfferController extends Controller
{
    /**
     * @Route("/offers", name="offer_admin_index", methods="GET")
     * @param OfferRepository $offerRepository
     * @return Response
     */
    public function index(OfferRepository $offerRepository, Request $request): Response
    {
        $offers = $offerRepository->findBy([], ['end'=>'DESC']);

        $paginator  = $this->get('knp_paginator');
        // Paginate the results of the query
        $appointments = $paginator->paginate(
            $offers,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            20
        );
        return $this->render('Admin/offerIndex.html.twig', [
            'appointments' => $appointments
        ]);
    }

    /**
     * @Route("/offer/{id}", name="offer_admin_show", methods="GET")
     */
    public function show(Offer $offer): Response
    {
        return $this->render('Admin/offerShow.html.twig', [
            'offer' => $offer,
        ]);
    }

    /**
     * @Route("/offer/{id}/activate", name="offer_admin_activate", methods="GET|POST")
     */
    public function activeOffer(Offer $offer): Response
    {
        $active = $offer->getActive();
        $offer->setActive(!$active);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('offer_admin_show', ['id' => $offer->getId()]);
    }
}
