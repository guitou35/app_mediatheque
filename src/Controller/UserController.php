<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchType;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_home")
     */
    public function index(LivreRepository $livreRepository, Request $request ): Response
    {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchType::class, $data);

        $form->handleRequest($request);

        $livres = $livreRepository->searchLivre($data);

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'livres'=> $livres,
            'form'=> $form->createView()
        ]);
    }

}
