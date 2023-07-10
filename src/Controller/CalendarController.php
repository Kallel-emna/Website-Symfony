<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\CongeType;
use App\Entity\Calendar;
use App\Form\CalendarType;
use App\Repository\CalendarRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/calendar')]
class CalendarController extends AbstractController
{
    #[Route('/', name: 'app_calendar_index', methods: ['GET'])]
    public function index(CalendarRepository $calendarRepository,Request $request, PaginatorInterface $paginator): Response
    {
        $donnees = $calendarRepository->findAll();

        $calendars = $paginator->paginate(
            $donnees,
            $request->query->getInt('page',1),
            1
        );
        return $this->render('calendar/index.html.twig', [
            'calendars' => $calendars,
        ]);
    }

    #[Route('/Conge', name: 'app_calendar_indexConge', methods: ['GET'])]
    public function indexConge(CalendarRepository $calendarRepository): Response
    {
        $user = $this->getUser();
        return $this->render('calendar/indexConge.html.twig', [
            'calendars' => $calendarRepository->findBy(['User' => $user]),
        ]);
    }

    #[Route('/new', name: 'app_calendar_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CalendarRepository $calendarRepository): Response
    {
        $calendar = new Calendar();
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calendarRepository->save($calendar, true);

            return $this->redirectToRoute('app_calendar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendar/new.html.twig', [
            'calendar' => $calendar,
            'form' => $form,
        ]);
    }

    #[Route('/newConge', name: 'app_calendar_newConge', methods: ['GET', 'POST'])]
    public function newConge(Request $request, CalendarRepository $calendarRepository): Response
    {
        $user = $this->getUser(); // Get the currently logged-in user
        $calendar = new Calendar();
        $form = $this->createForm(CongeType::class, $calendar);
        $form->get('background_color')->setData('#00FF00');
        $form->get('border_color')->setData('#000000');
        $form->get('text_color')->setData('#FFFFFF');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $background = $form->get('background_color')->getData();
            $border = $form->get('border_color')->getData();
            $text = $form->get('text_color')->getData();
            $calendar->setBackgroundColor($background);
            $calendar->setBorderColor($border);
            $calendar->setTextColor($text);
            $calendar->setUser($user);
            $calendarRepository->save($calendar, true);

            return $this->redirectToRoute('app_calendar_indexConge', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendar/newConge.html.twig', [
            'calendar' => $calendar,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_calendar_show', methods: ['GET'])]
    public function show(Calendar $calendar): Response
    {
        return $this->render('calendar/show.html.twig', [
            'calendar' => $calendar,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_calendar_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Calendar $calendar, CalendarRepository $calendarRepository): Response
    {
        $form = $this->createForm(CalendarType::class, $calendar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calendarRepository->save($calendar, true);

            return $this->redirectToRoute('app_calendar_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('calendar/edit.html.twig', [
            'calendar' => $calendar,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_calendar_delete', methods: ['POST'])]
    public function delete(Request $request, Calendar $calendar, CalendarRepository $calendarRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $calendar->getId(), $request->request->get('_token'))) {
            $calendarRepository->remove($calendar, true);
        }

        return $this->redirectToRoute('app_calendar_index', [], Response::HTTP_SEE_OTHER);
    }
}
