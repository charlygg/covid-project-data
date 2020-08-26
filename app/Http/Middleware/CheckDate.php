<?php

namespace App\Http\Middleware;

use Closure;
use DateTime;

class CheckDate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->date != null) {

            if ($this->validateDate($request->date) == false) {
                return redirect("/");
            }
        }

        return $next($request);
    }

    private function validateDate($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
