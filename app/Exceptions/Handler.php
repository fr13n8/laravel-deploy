<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            $dir = substr(__DIR__,0,-14);
            $backtrace =  $e->getTraceAsString();
            $backtrace = str_replace([$dir],"", $backtrace);
            $backtrace = preg_replace('^(.*vendor.*)\n^','',$backtrace);

            Log::channel('slack')->error('@here'.PHP_EOL.'**Error:** '.$e->getMessage() . PHP_EOL. '**Line:** ' . $e->getLine() . PHP_EOL. '**File:** '. $e->getFile() . PHP_EOL . '**Trace:**'.PHP_EOL. $backtrace);
        });
    }
}
