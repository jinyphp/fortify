<?php

namespace Jiny\Fortify\Http\Responses;

use Illuminate\Http\JsonResponse;
use Jiny\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Jiny\Fortify\Fortify;

class TwoFactorDisabledResponse implements TwoFactorLoginResponseContract
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
                    ? new JsonResponse('', 200)
                    : back()->with('status', Fortify::TWO_FACTOR_AUTHENTICATION_DISABLED);
    }
}
