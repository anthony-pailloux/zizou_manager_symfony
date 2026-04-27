<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Préfixe d’URL : toutes les routes de ce contrôleur commencent par /team
#[Route('/team')]
final class TeamController extends AbstractController
{

    // Fiche d’une équipe : l’{id} dans l’URL sert à charger l’entité Team (injection par Symfony)
    #[Route('/show/{id}', name: "team_show", methods: ['GET'])]
    public function show(Team $team)
    {
        return $this->render('/team/show.html.twig', [
            'team' => $team
        ]);
    }

    // Création : GET affiche le formulaire, POST enregistre en base
    #[Route('/new', name: "team_new", methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em)
    {

        $newTeam = new Team();
        $formTeam = $this->createForm(TeamType::class, $newTeam);

        $formTeam->handleRequest($request);


        if ($formTeam->isSubmitted() && $formTeam->isValid()) {
            $em->persist($newTeam);
            $em->flush();

            // dd($request);
            return $this->redirectToRoute('team_index');
        }

        return $this->render('/team/new.html.twig', [
            'formTeam' => $formTeam
        ]);
    }

    // Modification d’une équipe existante (même principe que new, sur un $team déjà en base)
    #[Route('/update/{id}', name: "team_update", methods: ['GET', 'POST'])]
    public function update(Team $team, Request $request, EntityManagerInterface $em)
    {
        $formTeam = $this->createForm(TeamType::class, $team);
        $formTeam->handleRequest($request);

        if ($formTeam->isSubmitted() && $formTeam->isValid()) {
            $em->persist($team);
            $em->flush();

            return $this->redirectToRoute('team_index');
        }

        return $this->render('team/update.html.twig', [
            'formTeam' => $formTeam
        ]);
    }

    // Suppression en POST seulement + jeton CSRF (évite qu’un lien GET supprime par erreur)
    #[Route('/delete/{id}', name: "team_delete", methods: ['POST'])]
    public function delete(Team $team, Request $request, EntityManagerInterface $em)
    {
        if ($this->isCsrfTokenValid('delete' . $team->getId(), $request->request->get('_token'))) {
            $em->remove($team);
            $em->flush();

            return $this->redirectToRoute('team_index');
        }
    }

    // Liste d’entraînement : GET /team/ — toutes les clés passées à Twig doivent exister côté template
    #[Route('/', name: 'team_index', methods: ['GET'])]
    public function index(TeamRepository $teamRepository): Response
    {
        // Valeurs en dur (tests) : id, lettre pour LIKE, longueur min du nom (voir repository)
        $id = 1;
        $teams = $teamRepository->findAllTeam();
        $teamsDesc = $teamRepository->findAllTeamsDesc();
        $team = $teamRepository->findTeamById($id);
        $teamsLL = $teamRepository->findTeamByLetterAndLenght('O', 4);

        // Clés = noms de variables côté Twig (teams, teamsDesc, team, teamsLL)
        return $this->render('team/index.html.twig', [
            'teams' => $teams,
            'teamsDesc' => $teamsDesc,
            'team' => $team,
            'teamsLL' => $teamsLL,
        ]);
    }
}
