<?php

namespace App\Http\Controllers\Gnosis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gnosis\UserStoreRequest;
use App\Http\Requests\Gnosis\UserUpdateRequest;
use App\Models\Gnosis\User;
use App\Models\Gnosis\Role;
use Illuminate\Http\Request;
use Session;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of all users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('users.list');

        $models = User::with('roles')->orderBy('created_at', 'DESC')->paginate(5);
        return view('gnosis/layouts/users-index')->with(compact('models'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('users.create');

        $roles = Role::whereVisible(true)->pluck('label', 'name');
        return view('gnosis/layouts/users-create')->with(compact('roles'));
    }

    /**
     * Store a newly created user in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        $this->authorize('users.create');

        $model = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password)
        ]);

        foreach ($request->roles as $role) {
            $model->grantRole($role);
        }

        Session::flash('flash_message', [
            'type'    => 'success',
            'message' => 'The user was created successfully.'
        ]);

        if ($request->has('save_exit')) {
            return redirect()->route('users.index');
        }

        return redirect()->route('users.edit', ['id' => $model->id]);
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('users.view');

        $roles = Role::whereVisible(true)->pluck('label', 'name');
        $model = User::findOrFail($id);

        return view('gnosis/layouts/users-edit')->with(compact('model', 'roles'));
    }

    /**
     * Update the specified user in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $this->authorize('users.update');

        $model = User::findOrFail($id);
        $model->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $model->revokeAllRoles();

        foreach ($request->roles as $role) {
            $model->grantRole($role);
        }

        Session::flash('flash_message', [
            'type'    => 'success',
            'message' => 'The user was updated successfully.'
        ]);

        if ($request->has('save_exit')) {
            return redirect()->route('users.index');
        }

        return redirect()->route('users.edit', ['id' => $model->id]);
    }

    /**
     * Remove the specified user from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('users.delete');

        $user = User::findOrFail($id);

        if ($user->id == Auth::user()->id) {
            Session::flash('flash_message', [
                'type'    => 'danger',
                'message' => "You cannot delete yourself"
            ]);
            return redirect()->back();
        }

        if (!Auth::user()->hasRole('super_admin')) {
            foreach ($user->roles as $role) {
                if ($role->protected) {
                    Session::flash('flash_message', [
                        'type'    => 'danger',
                        'message' => "The user could not be deleted, due to having the <strong>{$role->label}</strong> role: The role is protected"
                    ]);
                    return redirect()->back();
                }
            }
        }

        $user->delete();

        Session::flash('flash_message', [
            'type'    => 'success',
            'message' => 'The user was deleted successfully.'
        ]);

        return redirect()->back();
    }
}
