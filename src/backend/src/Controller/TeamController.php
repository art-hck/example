<?php

namespace App\Controller;

use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class TeamController extends Controller
{
    /**
     * @SWG\Response(
     *     response=200,
     *     description="Returns team"
     * )
     *
     * @SWG\Response(
     *     response=404,
     *     description="When no team found"
     * )
     *
     * @SWG\Tag(name="Team")
     *
     * @Route("/team/{id}", requirements={"id"="\d+"}, methods={"GET"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getById(int $id)
    {
        $team = $this->getDoctrine()->getRepository(Team::class)->find($id)
        or (function ($playerId) {
            throw new NotFoundHttpException("team with id `${playerId}` not found");
        })($id);

        return new JsonResponse($team);
    }
    
    
    /**
     * @SWG\Response(response=200, description="Returns team")
     * @SWG\Tag(name="Team")
     * @Route("/team/name/{name}", methods={"GET"})
     * 
     * @param string $name
     * @return JsonResponse
     */
    public function findByName(string $name)
    {
        $teams = $this->getDoctrine()->getRepository(Team::class)
            ->createQueryBuilder('t')
            ->where('t.name LIKE :name')
            ->setParameter('name', preg_replace("/\s+/", "%", $name) . "%")
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;

        return new JsonResponse($teams);
    }

}