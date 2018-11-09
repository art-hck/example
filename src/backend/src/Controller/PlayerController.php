<?php

namespace App\Controller;

use App\Entity\Player;
use App\Exception\BadRestRequestHttpException;
use App\Form\GetPlayersType;
use App\Form\PlayersFilterType;
use App\Http\ErrorJsonResponse;

use App\Type\SeekCriteria\Types\SeekCriteriaPlayerFilter;
use App\Service\RESTRequestService;
use App\Service\ValidateService;
use Doctrine\ORM\ORMException;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
     * @Route("/player/{playerId}", requirements={"playerId"="\d+"}, methods={"GET"})
     *
     * @param int $playerId
     * @return JsonResponse | ErrorJsonResponse
     */
    public function getPlayer(int $playerId)
    {
        try {
            $player = $this->getDoctrine()->getRepository(Player::class)->find($playerId)
            or (function ($playerId) {
                throw new NotFoundHttpException("player with id `${playerId}` not found");
            })($playerId);
        } catch (HttpException $e) {
            return new ErrorJsonResponse($e->getMessage(), [], $e->getStatusCode());
        } catch (\Exception $e) {
            return new ErrorJsonResponse($e->getMessage(), [], 500);
        }
        
        return new JsonResponse($player);
    }

    /**
     *
     * @SWG\Parameter(name="orderBy", in="query", type="string", description="Order by")
     * @SWG\Parameter(name="orderDirection", in="query", type="string", description="Order direction")
     * @SWG\Parameter(name="limit", in="query", type="integer", description="Limit")
     * @SWG\Parameter(name="offset", in="query", type="integer", description="Offset")
     * @SWG\Tag(name="Player")
     * @SWG\Response(
     *     response=200,
     *     description="Returns array of players",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=App\Entity\Player::class))
     *     )
     * )
     * @SWG\Response(response=404, description="When no players|team found")
     * 
     * @Route("/players/by/{field}/{value}", requirements={"by"="\d+"}, methods={"GET"})
     *
     * @param Request $request
     * @param $field
     * @param $value
     * @param ValidateService $validateService
     * @param RESTRequestService $restService
     * @return JsonResponse | ErrorJsonResponse | ORMException
     */    
    public function getPlayers(Request $request, $field, $value, ValidateService $validateService, RESTRequestService $restService)
    {
        try {
            $data = $validateService
                ->validate($request, GetPlayersType::class, $restService->getAllParams($request))
                ->getData()
            ;
//            echo "<pre>";
//            var_dump($data);die;
            $players = $this
                ->getDoctrine()
                ->getRepository(Player::class)
                ->findBy(
                    [$field => $value],
                    [$data['orderBy'] => $data['orderDirection']],
                    $data['limit'],
                    $data['offset']
                )
            ;
        } catch (BadRestRequestHttpException $e) {
            return new ErrorJsonResponse($e->getMessage(), $e->getErrors(), $e->getStatusCode());
        }
        catch (HttpException $e) {
            return new ErrorJsonResponse($e->getMessage(), [], $e->getStatusCode());
        }
        catch (\Exception $e) {
            return new ErrorJsonResponse($e->getMessage(), [], 500);
        }
        
        return new JsonResponse($players);
    }


    /**
     * Getting players by dates of games, scored balls, cards received, game time etc.
     * 
     * @SWG\Parameter(name="dateFrom", in="query", type="string", description="")
     * @SWG\Parameter(name="dateTo", in="query", type="string", description="")
     * @SWG\Parameter(name="leagueId", in="query", type="integer", description="")
     * @SWG\Parameter(name="teamId", in="query", type="integer", description="")
     * @SWG\Parameter(name="minGoals", in="query", type="integer", description="")
     * @SWG\Parameter(name="maxGoals", in="query", type="integer", description="")
     * @SWG\Parameter(name="minCards", in="query", type="integer", description="")
     * @SWG\Parameter(name="maxCards", in="query", type="integer", description="")
     * @SWG\Parameter(name="cardsType", in="query", type="integer", description="")
     * @SWG\Parameter(name="minPlayTime", in="query", type="integer", description="")
     * @SWG\Parameter(name="maxPlayTime", in="query", type="integer", description="")
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
    public function getPlayersFilter(Request $request, ValidateService $validateService, RESTRequestService $restService)
    {
        try {
            $data = $validateService
                ->validate($request, PlayersFilterType::class, $restService->getAllParams($request))
                ->getData()
            ;
            
            $criteria = new SeekCriteriaPlayerFilter();
            
            $criteria
                ->setLeagueId($data["leagueId"])
                ->setTeamId($data["teamId"])
                ->setTeamName($data["teamName"])
                ->setCardsType($data["cardsType"])
                ->setDatePeriod($data["dateFrom"], $data["dateTo"])
                ->setGoalsRange($data["minGoals"], $data["maxGoals"])
                ->setCardsRange($data["minCards"], $data["maxCards"])
                ->setAgeRange($data["minAge"], $data["maxAge"])
                ->setPlayTimeRange($data["minPlayTime"], $data["maxPlayTime"])
                ->setRole($data["role"])
                ->setOrderBy($data["orderBy"])
                ->setOrderDirection($data["orderDirection"])
                ->setOffset($data["offset"])
                ->setLimit($data["limit"])
            ;

            $players = $this->getDoctrine()
                ->getRepository(Player::class)
                ->findByCriteria($criteria)
            ;
        } catch (BadRestRequestHttpException $e) {
            return new ErrorJsonResponse($e->getMessage(), $e->getErrors(), $e->getStatusCode());
        } catch (HttpException $e) {
            return new ErrorJsonResponse($e->getMessage(), [], $e->getStatusCode());
        } catch (\Exception $e) {
            return new ErrorJsonResponse($e->getMessage(), [], 500);
        }

        return new JsonResponse($players);
    }
}