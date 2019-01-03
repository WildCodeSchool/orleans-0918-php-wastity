<?php
/**
 * Created by PhpStorm.
 * User: wilder4
 * Date: 03/12/18
 * Time: 15:03
 */

namespace App\Controller;

use App\Entity\Offer;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminOfferController extends AbstractController
{
    /**
     * @Route("/offers", name="offer_index", methods="GET")
     * @param OfferRepository $offerRepository
     * @return Response
     */
    public function index(OfferRepository $offerRepository): Response
    {
        return $this->render('Admin/offerIndex.html.twig', [
            'offers' => $offerRepository->findAll(),
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
}
