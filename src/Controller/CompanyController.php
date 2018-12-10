<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Offer;
use App\Entity\DaysOfWeek;
use App\Entity\Schedule;
use App\Form\CompanyScheduleType;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use App\Repository\DaysOfWeekRepository;
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
    public function new(Request $request, DaysOfWeekRepository $daysOfWeekRepository): Response
    {
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
            $em = $this->getDoctrine()->getManager();
            $company->setUser($user);
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('company_show_offers');
        }

        return $this->render('Visitor/Company/new.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/offers", name="company_show_offers", methods="GET")
     * @param Company $company
     * @return Response
     */
    public function show(Company $company): Response
    {

        return $this->render('Visitor/Company/listOffers.html.twig', [
            'company' => $company,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="company_edit", methods="GET|POST")
     * @param Request $request
     * @param company $company
     * @return Response
     */
    public function edit(Request $request, Company $company): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('company_index', ['id' => $company->getId()]);
        }
        return $this->render('Visitor/Company/show.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="company_delete", methods="DELETE")
     * @param Request $request
     * @param Company $company
     * @return Response
     */
    public function delete(Request $request, Company $company): Response
    {
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($company);
            $em->flush();
        }

        return $this->redirectToRoute('company_index');
    }


    /**
     * @Route("/{id}/editschedule", name="company_edit_shedule", methods="GET|POST")
     * @param Request $request
     * @param company $company
     * @return Response
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

            return $this->redirectToRoute('company_index', ['id' => $company->getId()]);
        }

        return $this->render('Visitor/Company/editSchedule.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }
}
