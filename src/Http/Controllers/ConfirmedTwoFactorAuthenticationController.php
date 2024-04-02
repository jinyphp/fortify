<?php

namespace Jiny\Fortify\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jiny\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Jiny\Fortify\Contracts\TwoFactorConfirmedResponse;

class ConfirmedTwoFactorAuthenticationController extends Controller
{
    /**
     * Enable two factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Jiny\Fortify\Actions\ConfirmTwoFactorAuthentication  $confirm
     * @return \Jiny\Fortify\Contracts\TwoFactorConfirmedResponse
     */
    public function store(Request $request, ConfirmTwoFactorAuthentication $confirm)
    {
        $confirm($request->user(), $request->input('code'));

        return app(TwoFactorConfirmedResponse::class);
    }
}
