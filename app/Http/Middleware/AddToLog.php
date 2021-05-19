<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class AddToLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    private $logger;

   // public function __construct( Logger $logger)
   // {
   //     $this->logger = $logger;
   // }
    public function handle($request, Closure $next)
    {
	 $response = $next($request);

        if (app()->environment('local')) {
            $log = [
                'URI' => $request->getUri(),
                'METHOD' => $request->getMethod(),
                'REQUEST_BODY' => $request->all(),
                'RESPONSE' => $response->getContent()
            ];

            Log::info(json_encode($log));
        }

        return $response;
    }
    public function terminate($request, $response)
    {
      $url=$request->fullUrl();
      $ip=$request->ip();
      //$r->ip=$ip;
      //$r->url=$url;
      //$r->request=json_encode($request->all());
      //$r->response=$response;
      //$r->save();
    }
}
