<?php

namespace Jiny\Fortify\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jiny\Fortify\Actions\DisableTwoFactorAuthentication;
use Jiny\Fortify\Actions\EnableTwoFactorAuthentication;
use Jiny\Fortify\Contracts\TwoFactorDisabledResponse;
use Jiny\Fortify\Contracts\TwoFactorEnabledResponse;

class TwoFactorAuthenticationController extends Controller
{
    /**
     * Enable two factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Jiny\Fortify\Actions\EnableTwoFactorAuthentication  $enable
     * @return \Jiny\Fortify\Contracts\TwoFactorEnabledResponse
     */
    public function store(Request $request, EnableTwoFactorAuthentication $enable)
    {
        $enable($request->user(), $request->boolean('force', false));

        return app(TwoFactorEnabledResponse::class);
    }

    /**
     * Disable two factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Jiny\Fortify\Actions\DisableTwoFactorAuthentication  $disable
     * @return \Jiny\Fortify\Contracts\TwoFactorDisabledResponse
     */
    public function destroy(Request $request, DisableTwoFactorAuthentication $disable)
    {
        $disable($request->user());

        return app(TwoFactorDisabledResponse::class);
    }
}
