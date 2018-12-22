<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Substitution;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class GamesController extends Controller
{
    /**
     * @SWG\Response(response=200, description="Returns last games")
     * @SWG\Tag(name="Game")
     * @Route("/games/last", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getLastGames()
    {
        $games = $this->getDoctrine()
            ->getRepository(Game::class)
            ->findBy([], ["date" => "DESC"], 10)
        ;

        return new JsonResponse($games);
    }

    /**
     * @SWG\Response(response=200, description="Returns last games")
     * @SWG\Tag(name="Game")
     * @Route("/games/player/{playerId}", methods={"GET"})
     *
     * @param int $playerId
     * @return JsonResponse
     */
    public function getByPlayer(int $playerId) 
    {
        $games = $this->getDoctrine()->getRepository(Game::class)
            ->getByPlayer($playerId)
        ;
        
        $substitutions = $this->getDoctrine()->getRepository(Substitution::class)
            ->findBy(['game' => $games])
        ;

        $cards = $this->getDoctrine()->getRepository(Card::class)
            ->findBy(['game' => $games])
        ;

        $games = array_map(function(Game $game) use ($substitutions, $cards) {
            return [
                'game' => $game, 
                'substitutions' => array_filter($substitutions, function(Substitution $substitution) use ($game) {
                    $substitution->setPlayer(null); // We already know player => avoid extra db requests
                    return  $substitution->getGame()->getId() == $game->getId();
                }),
                'cards' => array_filter($cards, function(Card $card) use ($game) {
                    $card->setPlayer(null); // --- //
                    return  $card->getGame()->getId() == $game->getId();
                })
            ];
        }, $games);
        
        return new JsonResponse($games);
    }
}