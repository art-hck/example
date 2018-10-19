<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class RESTRequestService
{
    
    public function getBody(Request $request): array 
    {
        return json_decode($request->getContent(), true);
    }
    
    public function getQuery(Request $request): array
    {
        return $request->query->all();
    }

    public function getAttributes(Request $request): array
    {
        return array_diff_key(
            $request->attributes->all(), 
            array_flip(["_route", "_controller", "_route_params"])
        );
    }
    
    public function getAllParams(Request $request): array 
    {
        return array_merge($this->getAttributes($request), $this->getQuery($request));
    }
}