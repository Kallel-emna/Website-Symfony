<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Calendar;
use App\Repository\CalendarRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class CalendrierController extends AbstractController
{
    #[Route('/calendriere', name: 'app_calendrier')]
    public function index(CalendarRepository $calendar): Response
    {
        $user = $this->getUser(); // Get the currently logged-in user
        $events = $this->getDoctrine()->getRepository(Calendar::class)->findBy(['User' => $user]);

        $rdvs = [];
        foreach ($events as $event) {
            $rdvs[] = [
                'id' => $event->getID(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->isAllDay(),
            ];
        }
        $data = json_encode($rdvs);

        return $this->render('calendrier/indexConge.html.twig', compact('data'));
    }
    #[Route('/calendrier/admin', name: 'app_calendrier_admin')]
    public function index_admin(CalendarRepository $calendar): Response
    {
        $events = $calendar->findAll();
        
        $rdvs = [];
        foreach($events as $event)
        {
            $rdvs[] = [
                'id' => $event->getID(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'description' => $event->getDescription(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->isAllDay()
            ];
        }
        $data = json_encode($rdvs);

        return $this->render('calendrier/index.html.twig',compact('data'));
    }

}
