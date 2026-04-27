<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerType;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Hérite de AbstractController : ton contrôleur réutilise des méthodes (render, createForm, redirectToRoute…).
 * Les objets en type-hint (PlayerRepository, Request…) sont créés et injectés par Symfony = injection de dépendances.
 */
#[Route('/player')]
final class PlayerController extends AbstractController
{
    // GET /player/ — liste
    #[Route('/', name: 'player_index', methods: ['GET'])]
    public function index(PlayerRepository $playerRepository): Response
    {
        // findAll() retourne un tableau d'objets Player (un objet par ligne en base)
        return $this->render('player/index.html.twig', [
            'players' => $playerRepository->findAll()
        ]);
    }

    // GET /player/show/{id}
    #[Route('/show/{id}', name: 'player_show', methods: ['GET'])]
    public function show(Player $player)
    {
        // $player est déjà une instance chargée depuis la base (selon l'id dans l'URL)
        return $this->render('player/show.html.twig', [
            'player' => $player
        ]);
    }

    // GET/POST /player/new
    #[Route('/new', name: 'player_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em)
    {
        // new Player() crée un objet joueur vide en mémoire (pas encore en base)
        $newPlayer = new Player();

        // Le formulaire est "attaché" à cet objet : Symfony remplit $newPlayer via les setters quand c'est valide
        $formPlayer = $this->createForm(PlayerType::class, $newPlayer);
        $formPlayer->handleRequest($request);

        if ($formPlayer->isSubmitted() && $formPlayer->isValid()) {
            // persist prépare l'objet ; flush envoie les INSERT/UPDATE en base
            $em->persist($newPlayer);
            $em->flush();

            return $this->redirectToRoute('player_index');
        }

        return $this->render('player/new.html.twig', [
            'formPlayer' => $formPlayer
        ]);
    }

    // GET/POST /player/update/{id}
    #[Route('/update/{id}', name: 'player_update', methods: ['GET', 'POST'])]
    public function update(
        Player $player,
        Request $request,
        EntityManagerInterface $em
    ) {
        // Même PlayerType, mais lié à un Player existant (même objet, mis à jour puis sauvegardé)
        $formPlayer = $this->createForm(PlayerType::class, $player);
        $formPlayer->handleRequest($request);

        if ($formPlayer->isSubmitted() && $formPlayer->isValid()) {
            $em->persist($player);
            $em->flush();

            return $this->redirectToRoute('player_index');
        }

        return $this->render('/player/update.html.twig', [
            'formPlayer' => $formPlayer
        ]);
    }

    // POST /player/delete/{id}
    #[Route('/delete/{id}', name: 'player_delete', methods: ['POST'])]
    public function delete(
        Player $player,
        Request $request,
        EntityManagerInterface $em
    ) {
        if ($this->isCsrfTokenValid('delete' . $player->getId(), $request->request->get('_token'))) {
            // remove indique à Doctrine de supprimer cette ligne ; flush exécute le DELETE
            $em->remove($player);
            $em->flush();

            return $this->redirectToRoute('player_index');
        }
    }
}
