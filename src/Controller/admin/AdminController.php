<?php

namespace App\Controller\admin;

use App\Form\PersonneType;
use App\Repository\LivreRepository;
use App\Repository\PersonneRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/admin")
 * @isGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    private $personneRepository;
    private ReservationRepository $reservationRepository;


    function __construct(PersonneRepository $personneRepository, ReservationRepository $reservationRepository)
    {
        $this->personneRepository = $personneRepository;
        $this->reservationRepository = $reservationRepository;
    }

    /**
     * @Route("/", name="admin_home")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('admin_list_emprunt');
    }

    /**
     *
     * @Route("/list/user", name="admin_list_new_user")
     */
    public function listNewUser()
    {
        $personnes = $this->personneRepository->findBy([
            "compteActived" => false
        ],['id'=>'DESC']);
        return $this->render('admin/validateUser.html.twig', [
            'personnes' => $personnes
        ]);
    }

    /**
     * @Route("/validate/user/{id}", name="admin_validate_new_user")
     */
    public function validateUser(int $id)
    {
        $personne = $this->personneRepository->find($id);
        $personne->setCompteActived(true);
        $this->getDoctrine()->getManager()->flush();
        $nom = $personne->getNom();
        $prenom = $personne->getPrenom();
        $this->addFlash('success', "Vous venez de valider l'utilisateur $nom $prenom");
        return $this->redirectToRoute('admin_list_new_user');
    }

    /**
     * @Route("/validate/emprunt/{id}" , name="admin_validate_emprunt")
     */
    public function valideEmrpunt($id)
    {
        $reservation = $this->reservationRepository->find($id);
        if ($reservation) {
            $em = $this->getDoctrine()->getManager();
            $dateJourEmprunt = new \DateTime();
            $dateRetour = new \DateTime();
            $diffDate = $reservation->getDateReservation()->diff($dateJourEmprunt);

            if ($diffDate->days <= 3 ) {
                $reservation->setDateEmprunt($dateJourEmprunt);
                $reservation->setDateRetour($dateRetour->add(new \DateInterval('P21D')));
                $reservation->setStatut('en-cours');

                $em->flush();
                $this->addFlash('success', "Le livre vient d'être emprunté");

              return  $this->redirectToRoute('user_get_reservations');
            } else if ($diffDate->days > 3 ) {
                $reservation->getLivre()->setStatut('dispo');
                $reservation->setDateRetour(new \DateTime('now'));
                $reservation->setStatut('done');
                $em->flush();
                $this->addFlash('error', "Le délai pour retirer un livre a été dépassé");

             return $this->redirectToRoute('user_get_reservations');
            }

        }
        $this->addFlash('error', "Il y a eu une erreur sur la réservation");
      return $this->redirectToRoute('user_get_reservations');

    }

    /**
     * @Route("validate/reception/{id}", name="admin_validate_reception")
     */
    public function valideReception($id)
    {
        $reservation = $this->reservationRepository->find($id);
        if ($reservation) {
            $em = $this->getDoctrine()->getManager();

            $reservation->setStatut('done');
            $reservation->getLivre()->setStatut('dispo');

            $em->flush();

            $this->addFlash('success', "Vous venez de valider la réception d'un livre");

            return  $this->redirectToRoute('admin_list_emprunt');

        }
        $this->addFlash('error', "Il y a eu une erreur sur la réservation");

        return $this->redirectToRoute('user_get_reservations');
    }

    /**
     * @Route("/list/emprunt" , name="admin_list_emprunt")
     */
    public function getListEmprunt()
    {
       $reservations =  $this->reservationRepository->findEmprunt();

       $reservationsDepaser = $this->reservationRepository->findEmpruntRetard(new \DateTime('now'));
        $nbReservationsDepasser = count($reservationsDepaser);
        $message = "il y a $nbReservationsDepasser emprunt en cours en retard";
       if ($nbReservationsDepasser > 0 ){
           $this->addFlash('notice', $message);
       }
        return $this->render('admin/livre/emprunt.html.twig', [
            'reservations'=> $reservations
        ]);
    }

    /**
     * @return Response
     * @Route("/list/all/reservations", name="admin_list_reservations")
     */
    public function getReservations()
    {
        $reservations = $this->reservationRepository->findBy([],['DateReservation' => 'DESC']);

        return $this->render('admin/livre/emprunt.html.twig',[
            'reservations' => $reservations
        ]);
    }

    /**
     * @return Response
     * @Route("/list/attente/reservations", name="admin_list_attente_reservations")
     */
    public function getReservationsAttente()
    {
        $reservations = $this->reservationRepository->findBy(['statut'=>'attente'],['DateReservation' => 'DESC']);

        return $this->render('admin/livre/emprunt.html.twig',[
            'reservations' => $reservations
        ]);
    }

}
