<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Reservation;
use App\Form\SearchType;
use App\Repository\LivreRepository;
use App\Repository\PersonneRepository;
use App\Repository\ReservationRepository;
use App\services\CheckReservationDateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;
    /**
     * @var LivreRepository
     */
    private $livreRepository;
    /**
     * @var CheckReservationDateService
     */
    private $checkReservationDateService;


    public function __construct(Security $security, LivreRepository $livreRepository, CheckReservationDateService $checkReservationDateService)
    {

        $this->security = $security;
        $this->livreRepository = $livreRepository;
        $this->checkReservationDateService = $checkReservationDateService;
    }

    /**
     * @Route("/", name="user_home")
     */
    public function index(LivreRepository $livreRepository, Request $request): Response
    {
        $livreEnRetard = $this->checkReservationDateService->updateReservation();

        if ($livreEnRetard) {
            $this->addFlash('notice', "Des livres sont de nouveaux disponibles");
        }
        $personne = $this->security->getUser();
        $countReservations = "";

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $countReservations = $this->checkReservationDateService->countReservationRetard();

        } else {
            $countReservations = $this->checkReservationDateService->countReservationRetardByOne($personne);
        }

        if (!empty($countReservations)) {
            $this->addFlash('notice', "Vous avez une notification");
        }

        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchType::class, $data);

        $form->handleRequest($request);

        $livres = $livreRepository->searchLivre($data);

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('livre/_livres.html.twig', ['livres' => $livres]),
                'pagination' => $this->renderView('livre/_pagination.html.twig', ['livres' => $livres]),
                'pages' => ceil($livres->getTotalItemCount() / $livres->getItemNumberPerPage()),
            ]);
        }

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'livres' => $livres,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reserver/{id}" , name="user_reserve")
     */
    public function reserver($id)
    {
        $user = $this->security->getUser();
        $reservation = new Reservation();
        $livre = $this->livreRepository->find($id);

        $reservation->setLivre($livre);
        $reservation->setDateReservation(new \DateTime('now'));
        $reservation->setStatut('attente');
        $reservation->setPersonne($user);

        $livre->setStatut('nodispo');
        $titre = $livre->getTitre();

        $this->addFlash('success', "Vous venez de réserver le livre $titre ");


        $em = $this->getDoctrine()->getManager();
        $em->persist($reservation);
        $em->flush();

        return $this->redirectToRoute('user_get_reservations');
    }

    /**
     * @return Response
     * @Route("/reservations", name="user_get_reservations")
     */
    public function getReservation(ReservationRepository $reservationRepository)
    {

            $reservations = $reservationRepository->findReservations($this->security->getUser()->getId());

        return $this->render('user/reservation.html.twig', [
            'reservations' => $reservations
        ]);
    }

}
