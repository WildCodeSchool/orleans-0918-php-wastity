<?php

namespace App\Controller;

use App\Entity\Association;
use App\Entity\Schedule;
use App\Form\AssociationScheduleType;
use App\Form\AssociationType;
use App\Repository\AssociationRepository;
use App\Repository\DaysOfWeekRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/association")
 */
class AssociationController extends AbstractController
{
    /**
     * @Route("/new", name="association_new", methods="GET|POST")
     */
    public function new(Request $request, DaysOfWeekRepository $daysOfWeekRepository): Response
    {
        $association = new Association();
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        $days = $daysOfWeekRepository->findAll();
        foreach ($days as $day) {
            $schedule = new Schedule();
            $schedule->setDay($day);
            $association->addSchedule($schedule);
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($association);
            $em->flush();

            return $this->redirectToRoute('association_index');
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

            return $this->redirectToRoute('association_index', ['id' => $association->getId()]);
        }

        return $this->render('Visitor/Association/edit.html.twig', [
            'association' => $association,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="association_delete", methods="DELETE")
     */
    public function delete(Request $request, Association $association): Response
    {
        if ($this->isCsrfTokenValid('delete' . $association->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($association);
            $em->flush();
        }

        return $this->redirectToRoute('association_index');
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
    ): Response {
        $form = $this->createForm(AssociationScheduleType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('association_show', ['id' => $association->getId()]);
        }

        return $this->render('Visitor/Company/editSchedule.html.twig', [
            'association' => $association,
            'form' => $form->createView(),
        ]);
    }
}
