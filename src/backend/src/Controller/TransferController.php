<?php

namespace App\Controller;

use App\Entity\Transfer;
use App\Form\TransferFilterType;
use App\Service\RESTRequestService;
use App\Service\ValidateService;
use App\Type\SeekCriteria\Types\SeekCriteriaTransferFilter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

class TransferController extends Controller
{
    /**
     * @SWG\Response(response=200, description="Returns last games")
     * @SWG\Tag(name="Transfer")
     * @Route("/transfers/largest", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getLargestTransfers()
    {
        /** @var Transfer[] $transfers */
        $transfers = $this->getDoctrine()->getRepository(Transfer::class)
            ->createQueryBuilder('t')
            ->orderBy("t.fee", "DESC")
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;

        return new JsonResponse($transfers);
    }

    /**
     * @SWG\Response(response=200, description="Returns last games")
     * @SWG\Tag(name="Transfer")
     * @Route("/transfers/lastest", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getLastestTransfers()
    {
        /** @var Transfer[] $transfers */
        $transfers = $this->getDoctrine()->getRepository(Transfer::class)
            ->createQueryBuilder('t')
            ->orderBy("t.date", "DESC")
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;

        return new JsonResponse($transfers);
    }


    /**
     * @SWG\Response(response=200, description="Returns last games")
     * @SWG\Tag(name="Transfer")
     * @Route("/transfers/filter", methods={"GET"})
     *
     * @param Request $request
     * @param ValidateService $validateService
     * @param RESTRequestService $restService
     * @return JsonResponse
     */
    public function getTransfers(Request $request, ValidateService $validateService, RESTRequestService $restService) {
        $data = $validateService
            ->validate($request, TransferFilterType::class, $restService->getAllParams($request))
            ->getData()
        ;

        $criteria = new SeekCriteriaTransferFilter();
        $criteria
            ->setDatePeriod($data["dateFrom"], $data["dateTo"])
            ->setFeeRange($data["fee"])
            ->setMvRange($data["mv"])
            ->setTeamId($data["teamId"])
            ->setLeagueId($data["leagueId"])
            ->setLeagueName($data["leagueName"])
            ->setOrderBy($data["orderBy"])
            ->setOrderDirection($data["orderDirection"])
            ->setOffset($data["offset"])
            ->setLimit($data["limit"])
        ;

        $transfers = $this->getDoctrine()
            ->getRepository(Transfer::class)
            ->findByCriteria($criteria)
        ;

        return new JsonResponse($transfers);
    }
}