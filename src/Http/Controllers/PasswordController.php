<?php

namespace Jiny\Fortify\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jiny\Fortify\Contracts\PasswordUpdateResponse;
use Jiny\Fortify\Contracts\UpdatesUserPasswords;
use Jiny\Fortify\Events\PasswordUpdatedViaController;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Jiny\Fortify\Contracts\UpdatesUserPasswords  $updater
     * @return \Jiny\Fortify\Contracts\PasswordUpdateResponse
     */
    public function update(Request $request, UpdatesUserPasswords $updater)
    {
        $updater->update($request->user(), $request->all());

        event(new PasswordUpdatedViaController($request->user()));

        return app(PasswordUpdateResponse::class);
    }
}
