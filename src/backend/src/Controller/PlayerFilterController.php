<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Team;
use App\Http\ErrorJsonResponse;
use App\Repository\PlayerRepository;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class PlayerFilterController extends Controller
{
    /**
     * @SWG\Response(
     *     response=200,
     *     description="Returns players of this team"
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="When no players|team found"
     * )
     *
     * @SWG\Tag(name="Team")
     *
     * @Route("/team/{teamId}/players", requirements={"teamId"="\d+"}, methods={"GET"})
     *
     * @param Request $request
     * @param int $teamId
     * @return JsonResponse | ErrorJsonResponse
     */
    public function getPlayersByTeam(Request $request, int $teamId = 0)
    {
        try {
            /** @var Team $team */
            $team = $this->getDoctrine()->getRepository(Team::class)->find($teamId)
                or (function ($teamId) {
                    throw new NotFoundHttpException("Team with id `${teamId}` not found");
                })($teamId)
            ;

            /** @var PlayerRepository $playersRepo */
            $playersRepo = $this->getDoctrine()->getRepository(Player::class);

            $players = $playersRepo->findByTeam($team, $request->query->get('orderBy', 'id'), $request->query->get('orderDirection', 'ASC'));
        } catch (NotFoundHttpException $e) {
            return new ErrorJsonResponse($e->getMessage(), [], $e->getStatusCode());
        } catch (\Exception $e) {
            return new ErrorJsonResponse($e->getMessage(), [], 500);
        }

        return new JsonResponse($players);
    }
}