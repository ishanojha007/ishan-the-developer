<?php
namespace App\Traits;
trait ResponseWithHttpRequest{


	protected function sendSuccess($message, $result = NULL)
    {
    	$response = [
            'ResponseCode'      => 200,
            'Status'            => True,
            'Message'           => $message,
        ];

        if(!empty($result)){
            $response['Data'] = $result;
        }
        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendFailed($errorMessages = [], $code = 200)
    {
    	$response = [
            'ResponseCode'      => $code,
            'Status'            => False,
        ];


        if(!empty($errorMessages)){
            $response['Message'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

}
