<?php

namespace App\Traits;

trait ApiResponse
{

    /**
     * @param $data
     * @param null $message
     * @param int $code
     * success method
     */
    protected function successResponse($data,  int $code = 200)
    {
        return response()->json(['result' => $data, 'errors' => null], $code);
    }

    protected function success($data,  int $code = 200)
    {
        // dd($data);
        // return response()->json(['status' => $data, 'errors' => null], $code);
        return response()->json([
            'status' => true,
            'jobs' => $data
        ]);
    }


    /**
     * @param null $message
     * @param int $code
     */
    protected function errorResponse($message = null, int $code = 404)
    {
        return response()->json(['result' => null, 'errors' => $message], $code);
    }

    protected function successPaginationResponse($result, int $code = 200)
    {

        $pagination = [
            'total' => $result->total(),
            'count' => $result->count(),
            'limit' => intval($result->perPage()),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage()
        ];
        return ['status' => true,'jobs' => $result, 'pagination' => $pagination];
    }

    protected function successPaginate($result, int $code = 200)
    {

        $pagination = [
            'total' => $result->total(),
            'count' => $result->count(),
            'per_page' => intval($result->perPage()),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage()
        ];
        return ['result' => $result, 'pagination' => $pagination];
    }



}
