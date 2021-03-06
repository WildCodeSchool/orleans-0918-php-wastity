<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Offer;
use App\Entity\DaysOfWeek;
use App\Entity\Schedule;
use App\Entity\User;
use App\Form\CompanyMemberType;
use App\Form\CompanyScheduleType;
use App\Form\CompanyType;
use App\Form\OfferType;
use App\Repository\CompanyRepository;
use App\Repository\DaysOfWeekRepository;
use App\Repository\OfferRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use App\Service\AddressChecker;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/company")
 */
class CompanyController extends AbstractController
{
    /**
     * @Route("/new", name="company_new", methods="GET|POST")
     * @param Request $request
     * @return Response
     */
    public function new(
        Request $request,
        DaysOfWeekRepository $daysOfWeekRepository,
        AddressChecker $checkAddress
    ): Response {
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
            $address = $company->fullAddress();
            if ($checkAddress->checkAddress($address)) {
                $em = $this->getDoctrine()->getManager();
                $company->setUser($user);
                $em->persist($company);
                $em->flush();
                $this->addFlash('success', "Votre entreprise a bien été enregistrée !");

                return $this->redirectToRoute('company_show_offers', ['id' => $company->getId()]);
            } else {
                $this->addFlash('danger', "L'adresse n'a pas été trouvée !");
            }
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
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     * @throws \Exception
     * @IsGranted("companyView", subject="company")
     */
    public function listOffers(
        Company $company,
        OfferRepository $offerRepository,
        Request $request,
        PaginatorInterface $paginator
    ) {
        $offers = $offerRepository->findNotDeliveredForCompany(new \DateTime(), $company);

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
     * @param Company $company
     * @param Offer $offer
     * @return Response
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
     * @IsGranted("companyView", subject="company")
     */
    public function record(Company $company, PaginatorInterface $paginator, Request $request): Response
    {

        $offers = $company->getOffers();

        $offers = $paginator->paginate(
            $offers,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            20
        );

        return $this->render('Visitor/Company/record.html.twig', [
            'company' => $company,
            'offers' => $offers,
        ]);
    }

    /**
     * @Route("/{id}/showCompany", name="company_show", methods="GET|POST")
     * @param Company $company
     * @return Response
     * @IsGranted("companyView", subject="company")
     */
    public function showCompany(Company $company, Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(CompanyMemberType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneByEmail($form->getData()['email']);
            if ($form->getData()['email'] === $this->getUser()->getEmail() || $company->getMembers()->contains($user)) {
                $this->addFlash('danger', "Cet utilisateur fait déjà partie de cette entreprise");

                return $this->redirectToRoute('company_show', ['id' => $company->getId()]);
            }
            if ($userRepository->findOneByEmail($form->getData()['email'])) {
                $em = $this->getDoctrine()->getManager();
                $company->addMember($user);
                $em->flush();
                $this->addFlash('success', "Cet utilisateur a bien été ajouté");
            } else {
                $this->addFlash('danger', "Cet utilisateur n'existe pas");
            }
            return $this->redirectToRoute('company_show', ['id' => $company->getId()]);
        }
        
        return $this->render('Visitor/Company/show.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="company_edit", methods="GET|POST")
     * @param Request $request
     * @param company $company
     * @return Response
     * @IsGranted("companyView", subject="company")
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
     * @IsGranted("companyView", subject="company")
     * @IsGranted("edit", subject="offer")
     */
    public function editOffer(Request $request, Company $company, Offer $offer): Response
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'L\'offre a bien été modifiée !');
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
     * @IsGranted("companyView", subject="company")
     */
    public function delete(Request $request, Company $company): Response
    {
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($company);
            $em->flush();
        }

        $this->addFlash('success', 'Votre entreprise a bien été supprimée !');

        return $this->redirectToRoute('company_index');
    }


    /**
     * @Route("/{id}/editschedule", name="company_edit_shedule", methods="GET|POST")
     * @param Request $request
     * @param company $company
     * @return Response
     * @IsGranted("companyView", subject="company")
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
     * @IsGranted("companyView", subject="company")
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
    
    /**
     * @Route ("/{id}/removeMember/{user}", name="removeMember", methods="POST")
     * @param Company $company
     * @param User $user
     * @return Response
     * @IsGranted("companyAdmin", subject="company")
     */
    public function deleteMember(Company $company, User $user, Request $request) :Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $company->removeMember($user);
            $em->flush();
            $this->addFlash('danger', "Cet utilisateur a bien été supprimé");
        }

        return $this->redirectToRoute('company_show', ['id' => $company->getId()]);
    }

    /**
     * @Route ("/{id}/leaveCompany", name="leaveCompany", methods="POST")
     * @param Company $company
     * @return Response
     * @IsGranted("companyView", subject="company")
     */
    public function leaveCompany(Company $company, Request $request) :Response
    {
        if ($this->isCsrfTokenValid('leaveCompany', $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $company->removeMember($this->getUser());
            $em->flush();
            $this->addFlash('danger', 'Vous avez quitté l\'entreprise : '. $company->getName());
        }

        return $this->redirectToRoute('profile_index');
    }
}
