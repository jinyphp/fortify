<?php

namespace Jiny\Fortify\Http\Responses;

use Illuminate\Http\JsonResponse;
use Jiny\Fortify\Contracts\EmailVerificationNotificationSentResponse as EmailVerificationNotificationSentResponseContract;
use Jiny\Fortify\Fortify;

class EmailVerificationNotificationSentResponse implements EmailVerificationNotificationSentResponseContract
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
                    ? new JsonResponse('', 202)
                    : back()->with('status', Fortify::VERIFICATION_LINK_SENT);
    }
}
