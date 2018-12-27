<?php

namespace App\Controller;

use App\Entity\Country;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class CountryController extends Controller
{
    /**
     * @SWG\Response(response=200, description="Returns team")
     * @SWG\Tag(name="Team")
     * @Route("/country/name/{name}", methods={"GET"})
     *
     * @param string $name
     * @return JsonResponse
     */
    public function findByName(string $name)
    {
        $teams = $this->getDoctrine()->getRepository(Country::class)
            ->createQueryBuilder('c')
            ->where('c.name LIKE :name')
            ->setParameter('name', preg_replace("/\s+/", "%", $name) . "%")
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;

        return new JsonResponse($teams);
    }

}