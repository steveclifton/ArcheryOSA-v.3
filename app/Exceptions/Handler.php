<?php

namespace App\Exceptions;

use App\Jobs\SendExceptionEmail;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;

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
        if (!$this->shouldntReport($exception)) {
            $message = request()->getPathInfo() . '<br>';
            $message .= ($exception->getMessage() ?? '') . '<br>' . ($exception->getFile() ?? '') . '<br>Line: ' . ($exception->getLine() ?? '');

            SendExceptionEmail::dispatch($message, 'ArcheryOSA Exception');
        }
        else {
            Log::channel('daily')->warning(['REQUEST_URI' => ($_SERVER['REQUEST_URI'] ?? ''),
                                            'REMOTE_ADDR' => ($_SERVER['REMOTE_ADDR'] ?? ''),
                                            'HTTP_USER_AGENT' => ($_SERVER['HTTP_USER_AGENT'] ?? ''),
                                            'QUERY_STRING' => ($_SERVER['QUERY_STRING'] ?? ''),
                                            'REQUEST_METHOD' => ($_SERVER['REQUEST_METHOD'] ?? ''),
                                        ]);
        }

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
    {
        return parent::render($request, $exception);
    }
}
