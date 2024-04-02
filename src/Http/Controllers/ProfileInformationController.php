<?php

namespace Jiny\Fortify\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Jiny\Fortify\Contracts\ProfileInformationUpdatedResponse;
use Jiny\Fortify\Contracts\UpdatesUserProfileInformation;
use Jiny\Fortify\Fortify;

class ProfileInformationController extends Controller
{
    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Jiny\Fortify\Contracts\UpdatesUserProfileInformation  $updater
     * @return \Jiny\Fortify\Contracts\ProfileInformationUpdatedResponse
     */
    public function update(Request $request,
                           UpdatesUserProfileInformation $updater)
    {
        if (config('fortify.lowercase_usernames')) {
            $request->merge([
                Fortify::username() => Str::lower($request->{Fortify::username()}),
            ]);
        }

        $updater->update($request->user(), $request->all());

        return app(ProfileInformationUpdatedResponse::class);
    }
}
