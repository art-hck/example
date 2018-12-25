<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Game;
use App\Entity\Substitution;
use App\Form\GameFilterType;
use App\Service\RESTRequestService;
use App\Service\ValidateService;
use App\Type\SeekCriteria\Types\SeekCriteriaGameFilter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class GamesController extends Controller
{
    /**
     * @SWG\Response(response=200, description="Returns last games")
     * @SWG\Tag(name="Game")
     * @SWG\Parameter(name="dateFrom", in="query", type="string", description="")
     * @SWG\Parameter(name="dateTo", in="query", type="string", description="")
     * @SWG\Parameter(name="orderBy", in="query", type="string", description="")
     * @SWG\Parameter(name="orderDirection", in="query", type="string", description="")
     * @SWG\Parameter(name="offset", in="query", type="integer", description="")
     * @SWG\Parameter(name="limit", in="query", type="integer", description="")
     * @Route("/games/filter", methods={"GET"})
     *
     * @param Request $request
     * @param ValidateService $validateService
     * @param RESTRequestService $restService
     * @return JsonResponse
     */
    public function getGames(Request $request, ValidateService $validateService, RESTRequestService $restService) {
        $data = $validateService
            ->validate($request, GameFilterType::class, $restService->getAllParams($request))
            ->getData()
        ;
        
        $criteria = new SeekCriteriaGameFilter();

        $criteria
            ->setDatePeriod($data["dateFrom"], $data["dateTo"])
            ->setTeamId($data["teamId"])
            ->setDuration($data["duration"])
            ->setOrderBy($data["orderBy"])
            ->setOrderDirection($data["orderDirection"])
            ->setOffset($data["offset"])
            ->setLimit($data["limit"])
        ;
        
        $games = $this->getDoctrine()
            ->getRepository(Game::class)
            ->findByCriteria($criteria)
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