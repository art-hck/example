<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PlayerFilterController extends Controller
{
    /**
     * @Route("/player/filter")
     */
    public function index()
    {
        return new JsonResponse(["foo" => "bar"]);
    }
}