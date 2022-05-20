<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Library\General;
use App\Models\Role;
use App\Models\User;
use Auth;
use Config;
use DB;
use Hash;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $user = Auth::user();
        // if(!$user->can('index_users'))
        // {
        //     return response()->json([], self::STATUS_UNAUTHORIZED);
        // }

        try
        {
            $search = $request->input('search');
            $rows = $request->input('rows');
            $role_id = json_decode($request->input('role_id'), true);

            $user = Auth::user();

            $users = User::where(function ($sq) use ($search) {
                            $sq->where('name', 'like', '%'.$search.'%')
                                  ->orWhere('email', 'like', '%'.$search.'%');
                       })->orderby('id', 'asc');

            if($request->filled('role_id'))
            {
                $users = $users->whereIn('role_id', $role_id);
            }

            if($request->filled('rows'))
            {
                $users = $users->paginate($rows);
            }
            else
            {
                $users = $users->get();

            }

            if(!empty($users))
            {
                foreach ($users as $user)
                {
                    $modelHasRole = DB::table('model_has_roles')->where('model_id',$user->id)->first();
                    if(isset($modelHasRole))
                    {
                        $user->role = Role::where('id',$modelHasRole->role_id)->first();
                    }
                }
            }

            return new UserCollection($users);
        }
        catch (Exception $e)
        {
            //Write error in log
            Log::error($e->getMessage() . ' line: ' . $e->getLine() . ' file: ' . $e->getFile());
            return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $response = $this->save($request);
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try
        {
            $user = User::findOrFail($id);

            $modelHasRole = DB::table('model_has_roles')->where('model_id',$user->id)->first();
            if(isset($modelHasRole))
            {
                $user->role = Role::where('id',$modelHasRole->role_id)->first();
            }

            if(null === $user)
            {
                return response()->json([], self::STATUS_NOT_FOUND);
            }

            $this->_response= ['success' => true, 'data' => new UserResource($user)];


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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $response = $this->save($request, $user->id);
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if(!$user->can('delete_users'))
        {
            return response()->json([], self::STATUS_UNAUTHORIZED);
        }

        try
        {
            $user = User::find($id);

            if(null === $user)
            {
                return response()->json([], self::STATUS_NOT_FOUND);
            }

            $settings = Config::get('filesconfig.users');
            if($user->image != '')
            {
                if (\Storage::exists($settings['images'] . $user->image))
                {
                    \Storage::delete($settings['images'] . $user->image);
                }
            }

            if(false === $user->delete())
            {
                return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
            }

            $user->syncRoles([]);

            $this->_response = ['success' => true, 'message' => '¡USUARIO ELIMINADO!'];
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
     *
     * It stores or updates the user
     *
     */

    public function save(Request $request, $id = 0)
    {
        try
        {
            DB::BeginTransaction();

            $user = User::find($id);

            if(null == $user)
            {
                $user = new User;
            }

            //-- It saves the image related to the business
            $imageName = '';
            if ($request->hasFile('image_file'))
            {
                $file = $request->file('image_file');
                $settings = Config::get('filesconfig.users');
                $fileExtension = strtolower($file->getClientOriginalExtension());
                $originalName = $file->getClientOriginalName();
                $imageName = General::fileName($fileExtension);
                $path = $settings['images'] . $imageName;
                \Storage::disk('local')->put($path, \File::get($file));

                if($user->image != '')
                {
                    if (\Storage::exists($settings['images'] . $user->image))
                    {
                        \Storage::delete($settings['images'] . $user->image);
                    }
                }
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->address = $request->address;
            $user->cellphone = $request->phone;
            $user->role_id = $request->role_id;
            if($request->filled('password'))
            {
                $password = $request->password;
                $user->password = Hash::make($password);
            }

            $status = false;
            $requestStatus = $request->input('status');

            if(1 == $requestStatus)
            {
                $status = true;
            }


            $user->active = $status;
            if($request->filled('language'))
            {
                $user->language = $request->language;
            }

            if($imageName != '')
            {
                $user->image = $imageName;
            }

            if(!$user->save())
            {
                DB::rollback();
                $this->_response = ['success' => false];
                return ['response' => $this->_response, 'status' => self::STATUS_INTERNAL_SERVER_ERROR];
            }

            //-- We find the role
            $role = Role::find($request->role_id);

            //-- We verify if the user already has the role
            if(!$user->hasRole($role->name))
            {
                $user->syncRoles($role->name);
            }

            DB::commit();

            $message = 'Actualización exitosa!';

            if(0 == $id)
            {
                $message =  'Registro exitoso!';
            }

            $this->_response = [
                'success' => true,
                'data' => $user,
                'message' => $message
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
     * Returns the permissions of an user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function permissions()
    {
        try
        {
            $user = Auth::user();

            if(null == $user)
            {
                return response()->json([], self::STATUS_NOT_FOUND);
            }

            $permissions = $user->getAllPermissions()
                                ->pluck('name');

            $this->_response = [
                'success' => true,
                'data' => $permissions
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

    public function changeStatus($id,Request $request)
    {
        DB::beginTransaction();

        $user = User::findOrFail($id);

        $status = $user->active;

        if(true == $status)
        {
            $active = false;
        }
        else
        {
            $active = true;
        }

        $user->active = $active;

        if(!$user->save())
        {
            DB::rollback();
            return response()->json([], self::STATUS_INTERNAL_SERVER_ERROR);
        }

        DB::commit();

        $this->_response = ['success' => true, 'message' => 'Estado actualizado', 'data' => new UserResource($user)];
        return response()->json($this->_response, self::STATUS_OK);

    }
}
