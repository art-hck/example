<?php

namespace App\Controller;

use App\Entity\Player;
use App\Event\GetPlayerEvent;
use App\EventSubscriber\PlayerViewsSubscriber;
use App\Form\PlayersFilterType;
use App\Http\ErrorJsonResponse;

use App\Type\SeekCriteria\Types\SeekCriteriaPlayerFilter;
use App\Service\RESTRequestService;
use App\Service\ValidateService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class PlayerController extends Controller
{

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Returns player"
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="When no player found"
     * )
     *
     * @SWG\Tag(name="Player")
     *
     * @Route("/player/{id}", requirements={"playerId"="\d+"}, methods={"GET"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getById(int $id)
    {
        $player = $this->getDoctrine()->getRepository(Player::class)->find($id)
        or (function ($id) {
            throw new NotFoundHttpException("Player with id `${id}` not found");
        })($id);

        $player->incViews();
        $this->getDoctrine()->getManager()->persist($player);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse($player);
    }

    /**
     * Getting players by dates of games, scored balls, cards received, game time etc.
     *
     * @SWG\Parameter(name="age", in="query", type="string", description="")
     * @SWG\Parameter(name="cards", in="query", type="string", description="")
     * @SWG\Parameter(name="cardsType", in="query", type="integer", description="")
     * @SWG\Parameter(name="dateFrom", in="query", type="string", description="")
     * @SWG\Parameter(name="dateTo", in="query", type="string", description="")
     * @SWG\Parameter(name="assists", in="query", type="string", description="")
     * @SWG\Parameter(name="international", in="query", type="boolean", description="")
     * @SWG\Parameter(name="goals", in="query", type="string", description="")
     * @SWG\Parameter(name="height", in="query", type="string", description="")
     * @SWG\Parameter(name="leagueId", in="query", type="integer", description="")
     * @SWG\Parameter(name="playTime", in="query", type="string", description="")
     * @SWG\Parameter(name="role", in="query", type="string", description="")
     * @SWG\Parameter(name="teamId", in="query", type="integer", description="")
     * @SWG\Parameter(name="teamName", in="query", type="integer", description="")
     * @SWG\Parameter(name="orderBy", in="query", type="string", description="")
     * @SWG\Parameter(name="orderDirection", in="query", type="string", description="")
     * @SWG\Parameter(name="offset", in="query", type="integer", description="")
     * @SWG\Parameter(name="limit", in="query", type="integer", description="")
     * @SWG\Response(response=200, description="Returns players")
     * @SWG\Tag(name="Player")
     * @Route("/players/filter", methods={"GET"})
     * @param Request $request
     * @param ValidateService $validateService
     * @param RESTRequestService $restService
     * @return ErrorJsonResponse|JsonResponse
     */
    public function getPlayers(Request $request, ValidateService $validateService, RESTRequestService $restService)
    {
        $data = $validateService
            ->validate($request, PlayersFilterType::class, $restService->getAllParams($request))
            ->getData()
        ;
        
        $criteria = new SeekCriteriaPlayerFilter();
        
        $criteria
            ->setAgeRange($data["age"])
            ->setAssistsRange($data["assists"])
            ->setCardsRange($data["cards"])
            ->setCardsType($data["cardsType"])
//            ->setCountryId($data["countryId"])
            ->setDatePeriod($data["dateFrom"], $data["dateTo"])
            ->setGoalsRange($data["goals"])
            ->setHeightRange($data["height"])
            ->setLeagueId($data["leagueId"])
            ->setLeagueName($data["leagueName"])
            ->setIsInternational($data["international"])
            ->setPlayTimeRange($data["playTime"])
            ->setRole($data["role"])
            ->setTeamId($data["teamId"])
            ->setTeamName($data["teamName"])
            ->setOrderBy($data["orderBy"])
            ->setOrderDirection($data["orderDirection"])
            ->setPlayerName($data["playerName"])
            ->setOffset($data["offset"])
            ->setLimit($data["limit"])
        ;

        $players = $this->getDoctrine()
            ->getRepository(Player::class)
            ->findByCriteria($criteria)
        ;

        return new JsonResponse($players);
    }
}