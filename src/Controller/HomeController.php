<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Personne;
use App\Form\AdresseType;
use App\Form\PersonneType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, UserPasswordHasherInterface $passwordHasher) :Response
    {
            $personne = new Personne();
            $adresse = new Adresse();
            $personne->setAdresse($adresse);

            $formPersonne = $this->createForm(PersonneType::class,$personne);


            $formPersonne->handleRequest($request);
            if($formPersonne->isSubmitted() && $formPersonne->isValid()){
                $em = $this->getDoctrine()->getManager();
                $personne = $personne->setPassword($passwordHasher->hashPassword($personne, $personne->getPassword()));
                $personne->setRoles(['ROLE_USER']);
                $personne->setCompteActived(0);
                $em->persist($personne);
                $em->flush();

                return $this->redirectToRoute('app_login');
            }

        return $this->render('home/inscription.html.twig',[
            'formPersonne' => $formPersonne->createView(),
        ]);
    }
}
