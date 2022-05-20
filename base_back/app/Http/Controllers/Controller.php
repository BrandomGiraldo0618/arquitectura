<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 2xx - Success
     * 3xx - Redirection
     * 4xx - Client error
     * 5xx - Server Error
     */

    //HTTP status codes
    const STATUS_OK = 200;
    const STATUS_CREATED = 201;
    const STATUS_NO_CONTENT = 204;
    const STATUS_NOT_MODIFIED = 304;
    const STATUS_BAD_REQUEST = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_PAYMENT_REQUIRED = 402;
    const STATUS_FORBIDDEN = 403;
    const STATUS_NOT_FOUND = 404;
    const STATUS_UNPROCESSABLE_ENTITY = 422;
    const STATUS_INTERNAL_SERVER_ERROR = 500;
    const STATUS_NOT_IMPLEMENTED = 501;

    //Response for all api calls
    protected $_response = [];

    public function __construct()
    {
        //if(!Auth::check()) abort(403, 'Unauthorized');

        //$this->user = Auth::user();
        //app('translator')->setLocale($this->user->language_code); // 'en', 'ro', etc
    }

    /**
     * Rewrite validation structure for api
     *
     * @param  Validator $validator
     * @return json
     */
    protected function formatValidationErrors(Validator $validator)
    {
        $keys = $validator->errors()->keys();
        $messages = $validator->errors()->all();

        $json = [];
        $json['status'] = 422;
        $json['attributes'] = $keys;
        $json['messages'] = $messages;

        return $json;
    }

    protected $roles = [
        'admin' => ['Administrador', 'Administrator'],
        'user' => ['Usuario', 'User']
    ];

    protected $permissionsEs = [
                                "index_users" => ["Listar usuarios", "users", "Usuarios"],
                                "create_users" => ["Crear usuarios", "users", "Usuarios"],
                                "update_users" => ["Actualizar usuarios", "users", "Usuarios"],
                                "delete_users" => ["Eliminar usuarios", "users", "Usuarios"],

                                "index_permissions" => ["Listar permisos", "permissions", "Permisos"],
                                "create_permissions" => ["Crear permisos", "permissions", "Permisos"],
                                "update_permissions" => ["Actualizar permisos", "permissions", "Permisos"],
                                "delete_permissions" => ["Eliminar permisos", "permissions", "Permisos"],

                                "index_posts" => ["Listar publicaciones", "posts", "Publicaciones"],
                                "create_posts" => ["Crear publicaciones", "posts", "Publicaciones"],
                                "update_posts" => ["Actualizar publicaciones", "posts", "Publicaciones"],
                                "delete_posts" => ["Eliminar publicaciones", "posts", "Publicaciones"],

                                "index_phrases" => ["Listar frases", "phrases", "Frase"],
                            ];


    protected $permissionsEn = [
                                "index_users" => ["Visualización de usuarios", "users", "Users"],
                                "create_users" => ["Creación de usuarios", "users", "Users"],
                                "update_users" => ["Actualización de usuarios", "users", "Users"],
                                "delete_users" => ["Eliminación de usuarios", "users", "Users"],

                                "index_permissions" => ["Permiso de visualización", "permissions", "Permissions"],
                                "create_permissions" => ["Permiso de creación", "permissions", "Permissions"],
                                "update_permissions" => ["Permiso de actualización", "permissions", "Permissions"],
                                "delete_permissions" => ["Permiso de eliminar", "permissions", "Permissions"],

                                "index_posts" => ["Index posts", "posts", "Posts"],
                                "create_posts" => ["Create posts", "posts", "Posts"],
                                "update_posts" => ["Actualizar posts", "posts", "Posts"],
                                "delete_posts" => ["Eliminar posts", "posts", "Posts"],

                                "index_phrases" => ["Listar frases", "phrases", "Publicaciones"],
                            ];
}
