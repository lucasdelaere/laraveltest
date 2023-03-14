<?php

namespace App\Http\Controllers;

use App\Events\UsersSoftDelete;
use App\Http\Requests\UsersRequest;
use App\Models\Photo;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUsersController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$users = User::orderByDesc("id")->get();
        $users = User::with(["roles", "photo"])
            ->orderByDesc("id")
            ->paginate(10); //withTrashed() shows the soft deleted entries. onlyTrashed shows only the soft deleted entries.
        $trashedUsers = User::with(["roles", "photo"])
            ->orderByDesc("id")
            ->onlyTrashed()
            ->paginate(10);
        //$users = User::all()
        //        $users = DB::table("users")
        //            ->select(
        //                "users.id as user_id",
        //                "photo_id",
        //                "users.name as user_name",
        //                "users.email",
        //                DB::raw("GROUP_CONCAT(roles.name) as role_names"),
        //                "is_active",
        //                "users.created_at as user_created_at",
        //                "users.updated_at as user_updated_at"
        //            )
        //            ->leftJoin("user_role", "users.id", "=", "user_role.user_id")
        //            ->leftJoin("roles", "user_role.role_id", "=", "roles.id")
        //            ->groupBy(
        //                "users.id",
        //                "photo_id",
        //                "user_name",
        //                "email",
        //                "is_active",
        //                "user_created_at",
        //                "user_updated_at"
        //            )
        //            ->orderBy("users.id")
        //            ->get();
        //
        //        $users = $users->map(function ($user) {
        //            $user->role_names = explode(",", $user->role_names);
        //            return $user;
        //        });
        //below is longer method to get user roles (requires 2 extra foreaches in users page, replacing the above 2 left joins)
        //$user_roles = DB::table("user_role")->get();
        //$roles = Role::pluck("name", "id")->all();
        //now pass $user_roles and $roles below as well
        return view("admin.users.index", [
            "users" => $users,
            "trashedUsers" => $trashedUsers,
        ]); //send variables in assoc array
    }
    public function index2()
    {
        $users = User::orderByDesc("id")->paginate(10);
        $trashedUsers = User::orderByDesc("id")
            ->onlyTrashed()
            ->paginate(10);
        return view("admin.users.index2", [
            "users" => $users,
            "trashedUsers" => $trashedUsers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck("name", "id")->all();
        /*ddd($roles); //(dump and die (and debug)) var_dump van laravel */
        return view("admin.users.create", compact("roles"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersRequest $request)
    {
        /*User::create([
            "name" => $request["name"],
            "email" => $request["email"],
            "password" => Hash::make($request["password"]),
            "role_id" => $request["role_id"],
            "is_active" => $request["is_active"],
        ]);*/
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_active = $request->is_active;
        $user->password = Hash::make($request->password);
        if ($file = $request->file("photo_id")) {
            // before we would make the path as 'img/{photo_name}{current_timestamp}'
            $path = request()
                ->file("photo_id") //look at 'getFileAttribute' function in Photo class, this makes a 'assets/...' filename
                ->store("users"); // store under assets/users
            $photo = Photo::create(["file" => $path]);
            //update photo_id (FK in users table)
            $user->photo_id = $photo->id;
        }

        $user->save();
        /*wegschrijven van meerdere rollen in tussentabel*/
        $user->roles()->sync($request->roles, false); //sync = attach + detach
        return redirect("admin/users")->with("status", [
            "User saved!",
            "alert-success",
        ]); //redirect uses cached page (view would reload it)
        //return redirect()->route('users.index'); //using aliases, so it will not use a cached page
        //return back()->withInput(); //terugkeren naar formulier (met ingevulde input)
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //        $user = User::find($id); //id MUST exist
        //        if(!$user) {
        //            throw new ModelNotFoundException();
        //        }
        // above code is replaced by following method:
        $user = User::findOrFail($id);
        $roles = Role::pluck("name", "id")->all();
        return view("admin.users.edit", compact("user", "roles"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        // validation IN the controller (instead of in requests (UserRequest))
        request()->validate([
            "name" => ["required", "max:255", "min:3"],
            "email" => ["required", "email"],
            "roles" => ["required", Rule::exists("roles", "id")],
            "is_active" => ["required"],
        ]);

        $user = User::findOrFail($id);
        if (trim($request->password) == "") {
            $input = $request->except("password");
        } else {
            $input = $request->all();
            $input["password"] = Hash::make($request["password"]);
        }

        //first check if a new photo was provided (otherwise do nothing)
        if ($file = $request->file("photo_id")) {
            $oldPhoto = Photo::find($user->photo_id);
            $path = request()
                ->file("photo_id")
                ->store("users");
            //upload to img folder (no longer needed). We now upload to assets folder, which is symlinked to storage/app/public/users
            //            $name = time() . $file->getClientOriginalName();
            //            $file->move("img", $name);

            //if there wasn't an old photo, create new in db
            if ($oldPhoto) {
                unlink(public_path($oldPhoto->file));

                /*here we could delete old photo:
                 $oldPhoto->delete();
                 and create new photo:
                 $photo = Photo::create(["file" => $name]);*/

                $oldPhoto->update(["file" => $path]);

                //keep old photo_id (FK in users table)
                $input["photo_id"] = $oldPhoto->id;
            } else {
                //create photo (new id)
                $photo = Photo::create(["file" => $path]);
                //update photo_id (FK in users table)
                $input["photo_id"] = $photo->id;
            }
        }

        //user saven
        $user->update($input);
        /* rollen in de tussentabel */
        $user->roles()->sync($request->roles, true); //= detach + attach
        return redirect("/admin/users")->with("status", [
            "User updated!",
            "alert-success",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // User::findOrFail($id)->delete();
        $user = User::findOrFail($id);
        UsersSoftDelete::dispatch($user);
        $user->delete();
        return redirect()
            ->route("users.index")
            ->with("status", ["User deleted!", "alert-danger"]);
    }

    protected function restore($id)
    {
        User::onlyTrashed()
            ->where("id", $id)
            ->restore();
        // or findOrFail
        $user = User::all()
            ->where("id", $id)
            ->first();
        $user
            ->posts()
            ->onlyTrashed()
            ->restore();
        return redirect("admin/users")->with("status", [
            "User restored!",
            "alert-warning",
        ]);
        //return redirect()->route('admin.users');
        //return back()
    }
}
