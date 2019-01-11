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
use Knp\Component\Pager\PaginatorInterface;
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
     * @Route("/offers", name="offer_admin_index", methods="GET")
     * @param OfferRepository $offerRepository
     * @return Response
     */
    public function index(OfferRepository $offerRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $offers = $offerRepository->findBy([], ['end'=>'DESC']);

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
        if ($active == true) {
            $this->addFlash('success', "L'offre a bien été activée !");
        } else {
            $this->addFlash('danger', "L'offre a bien été désactivée !");
        }
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('offer_admin_show', ['id' => $offer->getId()]);
    }
}
