<?php

namespace Jiny\Fortify\Actions;

use Illuminate\Support\Str;
use Jiny\Fortify\Fortify;

class CanonicalizeUsername
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        $request->merge([
            Fortify::username() => Str::lower($request->{Fortify::username()}),
        ]);

        return $next($request);
    }
}
