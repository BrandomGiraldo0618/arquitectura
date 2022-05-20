<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getAllPermissions()
    {
        try {
            $permissions = Permission::all();

            return ['success' => true, 'data' => $permissions->makeHidden(['created_at', 'updated_at'])];
        } catch (Exception $e) {
            //Write error in log
            Log::error($e->getMessage() . ' line: ' . $e->getLine() . ' file: ' . $e->getFile());
            return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllThePermissionsOfARole($role_id)
    {
        try {
            $role = Role::findById($role_id);
            $permissions = $role->permissions;

            return ['success' => true, 'data' => $permissions->makeHidden(['created_at', 'updated_at',  'guard_name','pivot'])];
        } catch (Exception $e) {
            //Write error in log
            Log::error($e->getMessage() . ' line: ' . $e->getLine() . ' file: ' . $e->getFile());
            return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($roleId)
    {
        try
        {
            $permissions = Permission::all();
            $role = Role::find($roleId);

            $sections = array();

            if(count($permissions) > 0)
            {
                foreach ($permissions as $permission)
                {
                    if('es' == Auth::user()->language)
                    {
                        $key = array_search($this->permissionsEs[$permission->name][2], array_column($sections, 'section'));


                        if (is_bool($key) === true)
                        {
                            $infoSection = [
                                'section' => $this->permissionsEs[$permission->name][2],
                                'permissions' => []
                            ];

                            array_push($sections,$infoSection);
                        }


                        if (!is_bool($key))
                        {
                            $allowed = false;

                            if($role->hasPermissionTo($permission->name))
                            {
                                $allowed  = true;
                            }

                            $infoPermissions = [
                                'id' => $permission->id,
                                'name' => $this->permissionsEn[$permission->name][0],
                                'real_name' => $permission->name,
                                'allowed' => $allowed
                            ];

                            array_push($sections[$key]['permissions'], $infoPermissions);
                        }

                    }
                    else
                    {

                        $key = array_search($this->permissionsEs[$permission->name][2], array_column($sections, 'section'));


                        if (is_bool($key) === true)
                        {
                            $infoSection = [
                                'section' => $this->permissionsEs[$permission->name][2],
                                'permissions' => []
                            ];

                            array_push($sections,$infoSection);
                        }


                        $key2 = array_search($this->permissionsEs[$permission->name][2], array_column($sections, 'section'));

                        if (!is_bool($key2))
                        {
                            $allowed = false;

                            if($role->hasPermissionTo($permission->name))
                            {
                                $allowed  = true;
                            }

                            $infoPermissions = [
                                'id' => $permission->id,
                                'name' => $this->permissionsEn[$permission->name][0],
                                'real_name' => $permission->name,
                                'allowed' => $allowed
                            ];

                            array_push($sections[$key2]['permissions'], $infoPermissions);
                        }

                    }
                }
            }

            $this->_response = $sections;
        }
        catch (Exception $e)
        {
            //Write error in log
            Log::error($e->getMessage() . ' line: ' . $e->getLine() . ' file: ' . $e->getFile());
            return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
        }

        return response()->json($this->_response, self::STATUS_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            $id = $request->input('id');
            $permission = null;

            if($id != 0)
            {
                $permission = Permission::find($id);
            }

            if(null == $permission)
            {
                $permission = Permission::create(['name' => $request->input('name')]);

                $this->_response = ['success' => true, 'message' => trans('permissions.permission_created')];
            }
            else
            {
                $permission->name = $request->input('name');
                $permission->save();

                $this->_response = ['success' => true, 'message' => trans('permissions.permission_updated')];
            }
        }
        catch (Exception $e)
        {
            //Write error in log
            Log::error($e->getMessage() . ' line: ' . $e->getLine() . ' file: ' . $e->getFile());
            return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
        }

        return response()->json($this->_response, self::STATUS_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $permission = Permission::find($id);

            if(null == $permission) { // Si el permiso o el rol no existen se retorna un código http 404
                return response()->json(['message' => 'No encontrado'], self::STATUS_NOT_FOUND);
            }

            $roles = $permission->roles; // Se obtienen todos los roles que tienen este permiso

            foreach($roles as $role) {
                $role->revokePermissionTo($permission); // Se remueve el permiso a cada uno de los roles
            }

            $permission->delete(); // Se elimina el permiso

            $this->_response = ['message' => 'Permiso eliminado'];
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' line: ' . $e->getLine() . ' file: ' . $e->getFile());
            return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
        }

        return response()->json($this->_response, self::STATUS_OK);
    }

    /**
     * Give permission to role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function permissionToRole(Request $request)
    {
        try
        {
            $permissionsId = $request->input('permissions_id');
            $permissionsId = explode(',', $permissionsId);
            $roleId = $request->input('role_id');

            $permissions = Permission::whereIn('id', $permissionsId)->get();
            $role = Role::find($roleId);

            if(count($permissions) == 0 || null == $role)
            {
                return response()->json([], self::STATUS_NOT_FOUND);
            }

            $role->givePermissionTo($permissions);

            $this->_response = ['message' => trans('permissions.associated_permission')];
        }
        catch (Exception $e)
        {
            //Write error in log
            Log::error($e->getMessage() . ' line: ' . $e->getLine() . ' file: ' . $e->getFile());
            return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
        }

        return response()->json($this->_response, self::STATUS_OK);
    }

    /**
     * Revoke permission to role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function revokePermissionTo(Request $request)
    {
        try
        {
            $permissionsId = $request->input('permissions_id');
            $permissionsId = explode(',', $permissionsId);
            $roleId = $request->input('role_id');

            $permissions = Permission::whereIn('id', $permissionsId)->get();
            $role = Role::find($roleId);

            if(count($permissions) == 0 || null == $role)
            {
                return response()->json([], self::STATUS_NOT_FOUND);
            }

            $role->revokePermissionTo($permissions);

            $this->_response = ['message' => trans('permissions.revoked_permission')];
        }
        catch (Exception $e)
        {
            //Write error in log
            Log::error($e->getMessage() . ' line: ' . $e->getLine() . ' file: ' . $e->getFile());
            return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
        }

        return response()->json($this->_response, self::STATUS_OK);
    }

    /**
     * Function that allows to async all permissions of a role
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function syncPermissions(Request $request)
    {
        try
        {
            $role_id = $request->input('role_id');
            $permissionsText = $request->input('permissions');

            $role = Role::find($role_id);
            $permissions = explode(',', $permissionsText);

            if(null == $role)
            {
                return response()->json([], self::STATUS_NOT_FOUND);
            }

            $role->syncPermissions($permissions);

            $this->_response = ['message' => trans('permissions.synchronized_permissions')];
        }
        catch (Exception $e)
        {
            //Write error in log
            Log::error($e->getMessage() . ' line: ' . $e->getLine() . ' file: ' . $e->getFile());
            return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
        }

        return response()->json($this->_response, self::STATUS_OK);
    }
}
