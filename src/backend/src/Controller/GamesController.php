<?php

namespace App\Controller;

use App\Entity\Game;
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
        /** @var Game[] $games */
        $games = $this->getDoctrine()
            ->getRepository(Game::class)
            ->findBy([], ["date" => "DESC"], 10)
        ;
            
        return new JsonResponse($games);
    }
}