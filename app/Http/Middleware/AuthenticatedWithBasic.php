<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticatedWithBasic
{
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        
        $u = 'zipidy';
        $p = 'zipidy';
        $template = '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
                    <html><head>
                    <title>401 Unauthorized</title>
                    </head><body>
                    <h1>Unauthorized</h1>
                    <p>This server could not verify that you
                    are authorized to access the document
                    requested.  Either you supplied the wrong
                    credentials (e.g., bad password), or your browser doesn\'t understand how to supply the credentials required.</p></body></html>';
        
        session_start();
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm=""');
            header('HTTP/1.0 401 Unauthorized');
            echo $template;
            $_SESSION["PHP_REDIRECTED"] = 0;
            exit;
        } else {
            if ($_SERVER['PHP_AUTH_USER'] == $u && $_SERVER['PHP_AUTH_PW'] == $p) {
                return $next($request);
            } else {
                header('WWW-Authenticate: Basic realm=""');
                header('HTTP/1.0 401 Unauthorized');
                echo $template;
                $_SESSION["PHP_REDIRECTED"] = 0;
                exit;
            }
        }
    }
}
