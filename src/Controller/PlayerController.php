<?php

/**
 * Contrôleur HTTP : chaque méthode répond à une URL (#[Route]) et renvoie une page HTML ou une redirection.
 * Symfony injecte automatiquement PlayerRepository, EntityManager, Request, etc. dans les paramètres.
 */
namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerType;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/player')]
final class PlayerController extends AbstractController
{
    // Liste tous les joueurs (GET /player/)
    #[Route('/', name:'player_index', methods: ['GET'])]
    public function index(PlayerRepository $playerRepository): Response
    {
        return $this->render('player/index.html.twig', [
            'players' => $playerRepository->findAll()
        ]);
    }

    // Détail d'un joueur : Symfony récupère le Player en base à partir de {id} dans l’URL
    #[Route('/show/{id}', name: 'player_show', methods: ['GET'])]
    public function show(Player $player){
        
        return $this->render('player/show.html.twig', [
            'player' => $player
        ]);
    }

    // Création : GET affiche le formulaire vide, POST enregistre si valide
    #[Route('/new', name: 'player_new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em)
    {
        $newPlayer = new Player();

        $formPlayer = $this->createForm(PlayerType::class, $newPlayer);
        // Remplit le formulaire avec les données de la requête (POST)
        $formPlayer->handleRequest($request);

        if ($formPlayer->isSubmitted() && $formPlayer->isValid()) {
            // persist = prépare l'INSERT ; flush = exécute les requêtes SQL en base
            $em->persist($newPlayer);
            $em->flush();

            return $this->redirectToRoute('player_index');
        }

        return $this->render('player/new.html.twig', [
            'formPlayer' => $formPlayer
        ]);
    }

    // Édition : le formulaire est lié au joueur existant (même PlayerType que pour la création)
    #[Route('/update/{id}', name: 'player_update', methods: ['GET', 'POST'])]
    public function update(
            Player $player, 
            Request $request, 
            EntityManagerInterface $em
        ){
        $formPlayer = $this->createForm(PlayerType::class, $player);
        $formPlayer->handleRequest($request);

        if($formPlayer->isSubmitted() && $formPlayer->isValid()){
            $em->persist($player);
            $em->flush();

            return $this->redirectToRoute('player_index');
        }

        // Pas encore soumis ou formulaire invalide : on réaffiche la page avec les erreurs éventuelles
        return $this->render('/player/update.html.twig', [
            'formPlayer' => $formPlayer
        ]);
    }
}