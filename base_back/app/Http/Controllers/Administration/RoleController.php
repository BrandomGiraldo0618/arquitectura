<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
	/**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Index roles
     */
    public function index(Request $request)
    {
        try
        {
            $params = $request->all();
            $roles = [];

            if(isset($params['search']) && !empty($params['search'])) {
                $search = $params['search'];
                $roles = Role::whereRaw("translate(name,'áéíóúÁÉÍÓÚäëïöüÄËÏÖÜ','aeiouAEIOUaeiouAEIOU') ilike '%$search%'")->get();
            } else {
                $roles = Role::all();
            }

            $this->_response = [
                'success' => true,
                'data' => $roles
            ];
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
     * Create new role
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:3,100'
        ]);

        if ($validator->fails())
        {
            $errors = $validator->errors();
            return response()->json(['success' => false, 'message' => $errors->all()]);
        }

        try
        {
            $role = Role::create([
                'name' => $request->name,
                'status' => $request->status
            ]);

            $this->_response = ['success' => true, 'message' => trans('permissions.role_created'), 'data' => $role];
        }
        catch (Exception $e)
        {
            //Write error in log
            Log::error($e->getMessage() . ' line: ' . $e->getLine() . ' file: ' . $e->getFile());
            return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
        }

        return response()->json($this->_response, self::STATUS_CREATED);
    }

    /**
     * Update an existent role
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:3,100'
        ]);

        if ($validator->fails())
        {
            $errors = $validator->errors();
            return response()->json(['success' => false, 'message' => $errors->all()]);
        }

        try
        {
            $role = Role::find($id);

            if(null === $role)
            {
                return response()->json(['success' => false, 'message' => trans('permissions.search_role')], self::STATUS_NOT_FOUND);
            }

            $role->name = $request->name;

            if(false === $role->save())
            {
                return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
            }

            $this->_response = ['success' => true, 'message' => trans('permissions.role_updated'), 'data' => $role];
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
     * Remove role
     */
    public function destroy($id)
    {
        try
        {
            $role = Role::find($id);

            if(null === $role)
            {
                return response()->json(['success' => false, 'message' => trans('permissions.search_role')], self::STATUS_NOT_FOUND);
            }

            //1. Get users related with role - model_has_roles
            $totalUsers = DB::table('model_has_roles')->where('role_id', $id)->count();


            if(0 < $totalUsers)
            {
                return response()->json(['success' => false, 'message' => trans('permissions.cant_be_deleted')]);
            }

            //4. If the role is unused remove permissions and remove role
            $role->syncPermissions([]);

            if(false === $role->delete())
            {
                return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
            }

            $this->_response = ['success' => true, 'message' => trans('permissions.deleted_role')];
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
