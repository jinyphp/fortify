<?php

namespace Jiny\Fortify\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jiny\Fortify\Contracts\VerifyEmailViewResponse;
use Jiny\Fortify\Fortify;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Jiny\Fortify\Contracts\VerifyEmailViewResponse
     */
    public function __invoke(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(Fortify::redirects('email-verification'))
                    : app(VerifyEmailViewResponse::class);
    }
}
