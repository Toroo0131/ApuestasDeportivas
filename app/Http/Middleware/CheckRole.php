<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/*
* Middleware encargado de verificar que el usuario autenticado
 * tenga uno de los roles permitidos para acceder a una ruta específica.
 *
 * Este middleware:
 * - Verifica si el usuario está autenticado
 * - Comprueba si el rol del usuario coincide con los roles permitidos
 * - Permite o bloquea el acceso según la verificación
 *
 * Uso típico en rutas:
 * middleware('checkRole:admin')
 * middleware('checkRole:admin,usuario')
*/

class CheckRole
{

/*
 * Maneja la solicitud entrante verificando el rol del usuario.
     *
     * Si el usuario no está autenticado retorna error 401.
     * Si el usuario no tiene un rol permitido retorna error 403.
     * Si cumple las condiciones permite continuar la petición.
     *
*/
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            return response()->json([
                'message' => 'No autenticado'
            ], 401);
        }

        if (! in_array($request->user()->role, $roles)) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        return $next($request);
    }
}
