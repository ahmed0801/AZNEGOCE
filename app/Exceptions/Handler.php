<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }



    public function render($request, Throwable $e)
{
    if ($e instanceof TokenMismatchException) {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Votre session a expiré. Rechargez la page.'], 419);
        }

        // Connexion : on garde l'identifiant saisi
        if ($request->routeIs('login.admin')) {
            return redirect()->route('login.form.admin')
                ->withInput($request->except('password', '_token'))
                ->withErrors(['login' => 'La page est restée ouverte trop longtemps. Veuillez réessayer.']);
        }

        // Autres pages : retour à la connexion avec un message clair
        return redirect()->route('login.form.admin')
            ->withErrors(['login' => 'Votre session a expiré. Veuillez vous reconnecter.']);
    }

    return parent::render($request, $e);
}


}
