<?php

namespace App\Controller\admin;

use App\Form\PersonneType;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin")
 * @isGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    private $personneRepository;

    function __construct(PersonneRepository $personneRepository)
    {
        $this->personneRepository = $personneRepository;
    }

    /**
     * @Route("/", name="admin_home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     *
     * @Route("/listUser", name="admin_list_new_user")
     */
    public function listNewUser()
    {
        $personnes = $this->personneRepository->findBy([
            "compteActived" => false
        ]);
        return $this->render('admin/validateUser.html.twig', [
            'personnes' => $personnes
        ]);
    }

    /**
     * @Route("/validateUser/{id}", name="admin_validate_new_user")
     */
    public function validateUser(int $id)
    {
        $personne = $this->personneRepository->find($id);
        $personne->setCompteActived(true);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('admin_list_new_user');
    }

}
