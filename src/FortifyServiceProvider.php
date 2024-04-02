<?php

namespace Jiny\Fortify;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Jiny\Fortify\Contracts\EmailVerificationNotificationSentResponse as EmailVerificationNotificationSentResponseContract;
use Jiny\Fortify\Contracts\FailedPasswordConfirmationResponse as FailedPasswordConfirmationResponseContract;
use Jiny\Fortify\Contracts\FailedPasswordResetLinkRequestResponse as FailedPasswordResetLinkRequestResponseContract;
use Jiny\Fortify\Contracts\FailedPasswordResetResponse as FailedPasswordResetResponseContract;
use Jiny\Fortify\Contracts\FailedTwoFactorLoginResponse as FailedTwoFactorLoginResponseContract;
use Jiny\Fortify\Contracts\LockoutResponse as LockoutResponseContract;
use Jiny\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Jiny\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Jiny\Fortify\Contracts\PasswordConfirmedResponse as PasswordConfirmedResponseContract;
use Jiny\Fortify\Contracts\PasswordResetResponse as PasswordResetResponseContract;
use Jiny\Fortify\Contracts\PasswordUpdateResponse as PasswordUpdateResponseContract;
use Jiny\Fortify\Contracts\ProfileInformationUpdatedResponse as ProfileInformationUpdatedResponseContract;
use Jiny\Fortify\Contracts\RecoveryCodesGeneratedResponse as RecoveryCodesGeneratedResponseContract;
use Jiny\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Jiny\Fortify\Contracts\SuccessfulPasswordResetLinkRequestResponse as SuccessfulPasswordResetLinkRequestResponseContract;
use Jiny\Fortify\Contracts\TwoFactorAuthenticationProvider as TwoFactorAuthenticationProviderContract;
use Jiny\Fortify\Contracts\TwoFactorConfirmedResponse as TwoFactorConfirmedResponseContract;
use Jiny\Fortify\Contracts\TwoFactorDisabledResponse as TwoFactorDisabledResponseContract;
use Jiny\Fortify\Contracts\TwoFactorEnabledResponse as TwoFactorEnabledResponseContract;
use Jiny\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Jiny\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;
use Jiny\Fortify\Http\Responses\EmailVerificationNotificationSentResponse;
use Jiny\Fortify\Http\Responses\FailedPasswordConfirmationResponse;
use Jiny\Fortify\Http\Responses\FailedPasswordResetLinkRequestResponse;
use Jiny\Fortify\Http\Responses\FailedPasswordResetResponse;
use Jiny\Fortify\Http\Responses\FailedTwoFactorLoginResponse;
use Jiny\Fortify\Http\Responses\LockoutResponse;
use Jiny\Fortify\Http\Responses\LoginResponse;
use Jiny\Fortify\Http\Responses\LogoutResponse;
use Jiny\Fortify\Http\Responses\PasswordConfirmedResponse;
use Jiny\Fortify\Http\Responses\PasswordResetResponse;
use Jiny\Fortify\Http\Responses\PasswordUpdateResponse;
use Jiny\Fortify\Http\Responses\ProfileInformationUpdatedResponse;
use Jiny\Fortify\Http\Responses\RecoveryCodesGeneratedResponse;
use Jiny\Fortify\Http\Responses\RegisterResponse;
use Jiny\Fortify\Http\Responses\SuccessfulPasswordResetLinkRequestResponse;
use Jiny\Fortify\Http\Responses\TwoFactorConfirmedResponse;
use Jiny\Fortify\Http\Responses\TwoFactorDisabledResponse;
use Jiny\Fortify\Http\Responses\TwoFactorEnabledResponse;
use Jiny\Fortify\Http\Responses\TwoFactorLoginResponse;
use Jiny\Fortify\Http\Responses\VerifyEmailResponse;
use PragmaRX\Google2FA\Google2FA;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/fortify.php', 'fortify');

        $this->registerResponseBindings();

        $this->app->singleton(TwoFactorAuthenticationProviderContract::class, function ($app) {
            return new TwoFactorAuthenticationProvider(
                $app->make(Google2FA::class),
                $app->make(Repository::class)
            );
        });

        $this->app->bind(StatefulGuard::class, function () {
            return Auth::guard(config('fortify.guard', null));
        });
    }

    /**
     * Register the response bindings.
     *
     * @return void
     */
    protected function registerResponseBindings()
    {
        $this->app->singleton(FailedPasswordConfirmationResponseContract::class, FailedPasswordConfirmationResponse::class);
        $this->app->singleton(FailedPasswordResetLinkRequestResponseContract::class, FailedPasswordResetLinkRequestResponse::class);
        $this->app->singleton(FailedPasswordResetResponseContract::class, FailedPasswordResetResponse::class);
        $this->app->singleton(FailedTwoFactorLoginResponseContract::class, FailedTwoFactorLoginResponse::class);
        $this->app->singleton(LockoutResponseContract::class, LockoutResponse::class);
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(LogoutResponseContract::class, LogoutResponse::class);
        $this->app->singleton(PasswordConfirmedResponseContract::class, PasswordConfirmedResponse::class);
        $this->app->singleton(PasswordResetResponseContract::class, PasswordResetResponse::class);
        $this->app->singleton(PasswordUpdateResponseContract::class, PasswordUpdateResponse::class);
        $this->app->singleton(ProfileInformationUpdatedResponseContract::class, ProfileInformationUpdatedResponse::class);
        $this->app->singleton(RecoveryCodesGeneratedResponseContract::class, RecoveryCodesGeneratedResponse::class);
        $this->app->singleton(RegisterResponseContract::class, RegisterResponse::class);
        $this->app->singleton(EmailVerificationNotificationSentResponseContract::class, EmailVerificationNotificationSentResponse::class);
        $this->app->singleton(SuccessfulPasswordResetLinkRequestResponseContract::class, SuccessfulPasswordResetLinkRequestResponse::class);
        $this->app->singleton(TwoFactorConfirmedResponseContract::class, TwoFactorConfirmedResponse::class);
        $this->app->singleton(TwoFactorDisabledResponseContract::class, TwoFactorDisabledResponse::class);
        $this->app->singleton(TwoFactorEnabledResponseContract::class, TwoFactorEnabledResponse::class);
        $this->app->singleton(TwoFactorLoginResponseContract::class, TwoFactorLoginResponse::class);
        $this->app->singleton(VerifyEmailResponseContract::class, VerifyEmailResponse::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePublishing();
        $this->configureRoutes();
        $this->registerCommands();
    }

    /**
     * Configure the publishable resources offered by the package.
     *
     * @return void
     */
    protected function configurePublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../stubs/fortify.php' => config_path('fortify.php'),
            ], 'fortify-config');

            $this->publishes([
                __DIR__.'/../stubs/CreateNewUser.php' => app_path('Actions/Fortify/CreateNewUser.php'),
                __DIR__.'/../stubs/FortifyServiceProvider.php' => app_path('Providers/FortifyServiceProvider.php'),
                __DIR__.'/../stubs/PasswordValidationRules.php' => app_path('Actions/Fortify/PasswordValidationRules.php'),
                __DIR__.'/../stubs/ResetUserPassword.php' => app_path('Actions/Fortify/ResetUserPassword.php'),
                __DIR__.'/../stubs/UpdateUserProfileInformation.php' => app_path('Actions/Fortify/UpdateUserProfileInformation.php'),
                __DIR__.'/../stubs/UpdateUserPassword.php' => app_path('Actions/Fortify/UpdateUserPassword.php'),
            ], 'fortify-support');

            $method = method_exists($this, 'publishesMigrations') ? 'publishesMigrations' : 'publishes';

            $this->{$method}([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'fortify-migrations');
        }
    }

    /**
     * Configure the routes offered by the application.
     *
     * @return void
     */
    protected function configureRoutes()
    {
        if (Fortify::$registersRoutes) {
            Route::group([
                'namespace' => 'Jiny\Fortify\Http\Controllers',
                'domain' => config('fortify.domain', null),
                'prefix' => config('fortify.prefix'),
            ], function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');
            });
        }
    }

    /**
     * Register the package's commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
            ]);
        }
    }
}
