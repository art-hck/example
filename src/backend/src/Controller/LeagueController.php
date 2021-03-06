<?php

namespace App\Controller;

use App\Entity\League;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class LeagueController extends Controller
{

    /**
     * @SWG\Response(response=200, description="Returns leagues")
     * @SWG\Tag(name="League")
     * @Route("/league/name/{name}", methods={"GET"})
     *
     * @param string $name
     * @return JsonResponse
     */
    public function findByName(string $name)
    {
        $teams = $this->getDoctrine()->getRepository(League::class)
            ->createQueryBuilder('l')
            ->select('l.name')
            ->where('l.name LIKE :name')
            ->setParameter('name', preg_replace("/\s+/", "%", $name) . "%")
            ->groupBy("l.name")
            ->setMaxResults(30)
            ->getQuery()
            ->getResult()
        ;

        return new JsonResponse($teams);
    }
}