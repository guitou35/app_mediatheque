<?php

namespace App\Controller\admin;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/genre")
 */
class GenreController extends AbstractController
{
    /**
     * @Route("/", name="genre_index", methods={"GET"})
     */
    public function index(GenreRepository $genreRepository): Response
    {
        return $this->render('admin/genre/index.html.twig', [
            'genres' => $genreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="genre_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($genre);
            $entityManager->flush();
            $nom = $genre->getNom();
            $this->addFlash('success', "Vous venez d'ajouter la catégorie $nom");

            return $this->redirectToRoute('genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/genre/new.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="genre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Genre $genre): Response
    {
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $nom = $genre->getNom();
            $this->addFlash('success', "Vous venez de modifier la catégorie $nom");
            return $this->redirectToRoute('genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/genre/edit.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="genre_delete", methods={"POST"})
     */
    public function delete(Request $request, Genre $genre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$genre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($genre);
            $nom = $genre->getNom();
            $this->addFlash('success', "Vous venez de supprimer la catégorie $nom");
            $entityManager->flush();

        }

        return $this->redirectToRoute('genre_index', [], Response::HTTP_SEE_OTHER);
    }
}
