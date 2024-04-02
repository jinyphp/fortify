<?php

namespace Jiny\Fortify\Http\Responses;

use Illuminate\Http\JsonResponse;
use Jiny\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Jiny\Fortify\Fortify;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return $request->wantsJson()
                    ? new JsonResponse('', 201)
                    : redirect()->intended(Fortify::redirects('register'));
    }
}
