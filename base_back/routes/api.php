<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    Route::group(['middleware' => ['api', 'lang']], function ($router) {
        Route::prefix('v1')->group(function () {
            //------- Authentication routes -------//
            Route::post('login', 'Authentication\AuthController@login')->name('login');
            Route::post('logout', 'Authentication\AuthController@logout');
            Route::post('refresh', 'Authentication\AuthController@refresh');
            Route::post('me', 'Authentication\AuthController@me');

            Route::post('recover-password', 'Authentication\PasswordController@recoverPassword');
            Route::post('change-password', 'Authentication\PasswordController@changePassword');

            //------- Roles routes -------//
            Route::resource('roles', 'Administration\RoleController');

            //------- Permissions routes -------//
            Route::get('get-all-permissions', 'Administration\PermissionController@getAllPermissions');
            Route::get('get-all-the-permissions-of-a-role/{role_id}', 'Administration\PermissionController@getAllThePermissionsOfARole');
            Route::get('permissions/{role_id}', 'Administration\PermissionController@index');
            Route::post('permissions', 'Administration\PermissionController@store');
            Route::delete('permissions/{id}', 'Administration\PermissionController@destroy');
            Route::post('permission-to-role', 'Administration\PermissionController@permissionToRole');
            Route::post('revoke-permission-to', 'Administration\PermissionController@revokePermissionTo');
            Route::post('sync-permissions', 'Administration\PermissionController@syncPermissions');

            //------- Users routes -------//
            Route::get('permissions-user', 'Administration\UserController@permissions');
            Route::apiResource('users', 'Administration\UserController');
            Route::patch('users/{id}/change-status', 'Administration\UserController@changeStatus');


        });
    });

    Route::apiResource('v1/tipo', 'Api\V1\TipoController');
    Route::apiResource('v1/partido', 'Api\V1\PartidoController');
    Route::apiResource('v1/persona', 'Api\V1\PersonaController');
    Route::apiResource('v1/candidato', 'Api\V1\CandidatoController');
    Route::apiResource('v1/punto_vota', 'Api\V1\PuntoVotacioController');
    Route::apiResource('v1/mesa', 'Api\V1\MesaController');
    Route::apiResource('v1/votante', 'Api\V1\VotanteController');
    Route::apiResource('v1/jurado', 'Api\V1\JuradoController');
    Route::apiResource('v1/voto', 'Api\V1\VotoController');
    Route::get('v1/validar-partido/{partido_id}/{tipo_voto}', 'Api\V1\VotoController@partidoListaAC');
    Route::get('v1/validar-votante/{documento}', 'Api\V1\VotoController@validarVotante');
    Route::get('v1/mesa/consultar-por-punto-votacion/{id}', 'Api\V1\MesaController@consultarPorPuntoVotacion');
    //Route::get('v1/punto_vota-test', 'Api\V1\PuntoVotacioController@index');
