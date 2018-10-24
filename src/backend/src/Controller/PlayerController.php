<?php

namespace App\Controller;

use App\Entity\Player;
use App\Exception\BadRestRequestHttpException;
use App\Form\GetPlayersType;
use App\Form\PlayersFilterType;
use App\Http\ErrorJsonResponse;

use App\Service\RESTRequestService;
use App\Service\ValidateService;
use App\Type\SeekCriteria\SeekCriteria;
use App\Type\SeekCriteria\SeekCriteriaRange;
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
     * @SWG\Parameter(
     *     name="orderBy",
     *     in="query",
     *     type="string",
     *     description="Order by"
     * )
     *
     * @SWG\Parameter(
     *     name="orderDirection",
     *     in="query",
     *     type="string",
     *     description="Order direction"
     * )
     * 
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     description="Limit"
     * )
     *      
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     type="integer",
     *     description="Offset"
     * )
     * 
     * @SWG\Tag(name="Player")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returns array of players",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=App\Entity\Player::class))
     *     )
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="When no players|team found"
     * )
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
     * /**
     *
     * @SWG\Parameter(
     *     name="dateFrom",
     *     in="query",
     *     type="string",
     *     description="Date from"
     * )
     *
     * @SWG\Parameter(
     *     name="dateTo",
     *     in="query",
     *     type="string",
     *     description="Date to"
     * )
     *
     * @SWG\Parameter(
     *     name="leagueId",
     *     in="query",
     *     type="integer",
     *     description="League id"
     * )
     *
     * @SWG\Parameter(
     *     name="teamId",
     *     in="query",
     *     type="integer",
     *     description="Team id"
     * )
     *
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

            $criteria = new SeekCriteria();

            if ($data["dateFrom"] && $data["dateTo"]) {
                $criteria->setDatePeriod($data["dateFrom"], $data["dateTo"]);
            }

            $criteria->setLeagueId($data["leagueId"]);
            $criteria->setTeamId($data["teamId"]);
            
            if($data["minGoals"] && $data["maxGoals"]) {
                $criteria->setGoalsRange($data["minGoals"], $data["maxGoals"]);
            }
            
            if($data["minCards"] && $data["maxCards"]) {
                $criteria
                    ->setCardsRange($data["minCards"], $data["maxCards"])
                    ->setCardsType($data["cardsType"])
                ;
            }

            if($data["minPlayTime"] && $data["maxPlayTime"]) {
                $criteria->setPlayTimeRange($data["minPlayTime"], $data["maxPlayTime"]);
            }            

            $players = $this
                ->getDoctrine()
                ->getRepository(Player::class)
                ->findByCriteria($criteria, $data["orderBy"], $data["orderDirection"], $data["offset"], $data["limit"])
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