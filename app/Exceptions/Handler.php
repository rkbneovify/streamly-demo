<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {   if ($exception instanceof QueryException && $request->wantsJson()) {
        return response()->json(['success'=>false,'response_code'=>500, 'error'=>$exception->getMessage(), 'message' =>'Internal Server Error'], 500);   
    
    } if ($exception instanceof BindingResolutionException && $request->wantsJson()) {
        return response()->json(['success'=>false,'response_code'=>500, 'error'=>$exception->getMessage(), 'message' =>'Internal Server Error'], 500);   
    
    }
    if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
        return response()->json(['success'=>false,
            'message' => 'Entry for '.str_replace('App\\', '', $exception->getModel()).' not found','response_code'=>404], 404);
    }

    if ($exception instanceof NotFoundHttpException && $request->wantsJson()) {
        return response()->json(['success'=>false,'response_code'=>404, 'error'=>$exception->getMessage(), 'message' =>'Resource not found'], 404);   
    }
    
    if ($exception instanceof MethodNotAllowedHttpException && $request->wantsJson()) {
        return response()->json(['success'=>false,'response_code'=>405, 'error'=>$exception->getMessage(), 'message' =>'Method not allowed.'], 405);   
    }

    if($exception instanceof \Stripe\Exception\ApiConnectionException||$exception instanceof \Stripe\Exception\ApiErrorException||$exception instanceof \Stripe\Exception\RateLimitException||$exception instanceof \Stripe\Exception\InvalidRequestException||$exception instanceof \Stripe\Exception\AuthenticationException){
        return response()->json(['message'=>$exception->getMessage()]);
    }

        return parent::render($request, $exception);
    }
}
