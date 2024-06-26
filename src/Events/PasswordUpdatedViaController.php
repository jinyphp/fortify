<?php

namespace Jiny\Fortify\Events;

use Illuminate\Foundation\Events\Dispatchable;

class PasswordUpdatedViaController
{
    use Dispatchable;

    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
