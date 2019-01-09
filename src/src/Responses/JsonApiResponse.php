<?php

namespace App\Responses;

use Symfony\Component\HttpFoundation\JsonResponse;

final class JsonApiResponse extends JsonResponse
{
    /**
     * @inheritdoc
     */
    public function setData($data = [])
    {
        $data = ['data' => $data];
        parent::setData($data);
    }
}
