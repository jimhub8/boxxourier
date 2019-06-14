<?php

namespace App\Http\Controllers;

use App\Notifications\SignupActivate;
use App\Shipment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Branch;
use App\Country;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        // return $request->all();
        // return User::with(['roles'])->get();
        $users = User::orderBy('name')->get();
        $users->transform(function ($user, $key) {
            $branch_name = Branch::find($user->branch_id);
            $country_name = Country::find($user->country_id);
            $user->branch = $branch_name->branch_name;
            $user->country = $country_name->country_name;
            $user->status = 'active';
            return $user;
        });
        // return $users;
        return response()->json($users);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $this->generateRandomString();
        // return $request->all();
        $this->Validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'branch_id' => 'required',
            'countryList' => 'required',
            'role_id' => 'required',
        ]);
        // return $request->all();
        $user = new User;
        $password = $this->generateRandomString();
        $password_hash = Hash::make($password);
        $user->password = $password_hash;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->branch_id = $request->branch_id;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->country_id = $request->countryList;
        $user->activation_token = str_random(60);
        $user->save();
        $user->assignRole($request->role_id);
        $user->givePermissionTo($request->selected);
        $user->notify(new SignupActivate($user, $password));
        return $user;
    }
    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // return $request->all();
        // return $request->selected;
        $this->Validate($request, [
            'form.name' => 'required',
            'form.email' => 'required|email',
            'form.phone' => 'required|numeric',
            // 'form.branch_id' => 'required',
            // 'form.address' => 'required',
            // 'form.city' => 'required',
            // 'form.country' => 'required',
            // 'form.role_id' => 'required'
        ]);
        $user = User::find($request->form['id']);
        $user->name = $request->form['name'];
        $user->email = $request->form['email'];
        $user->phone = $request->form['phone'];
        $user->branch_id = $request->form['branch_id'];
        $user->address = $request->form['address'];
        $user->city = $request->form['city'];
        $user->country = $request->form['country'];
        $user->country_id = $request->form['country_id'];
        $user->save();
        foreach ($request->form['roles'] as $role) {
            $role_name = $role['name'];
        }
        $user->syncRoles($role_name);

        // $p_all = Permission::all();//Get all permissions

        // foreach ($p_all as $p) {
        //     $user->revokePermissionTo($p); //Remove all permissions associated with role
        // }

        // $user->givePermissionTo($request->selected);
        // foreach ($permissions as $permission) {
        //     $p = Permission::where('id', '=', $permission)->firstOrFail(); //Get corresponding form //permission in db
        //     $role->givePermissionTo($p);  //Assign permission to role
        // }

        return $user;
    }

    public function AuthUserUp(Request $request)
    {
        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->branch_id = $request->branch_id;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->country = $request->country;
        $user->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        User::find($user->id)->delete();
    }

    public function getLogedinUsers()
    {
        return Auth::user();
    }

    // public function profile(Request $request, $id)
    // {
    //     $upload = User::find($request->id);
    //     if ($request->hasFile('image')) {
    //         // return('test');
    //         // $imagename = time() . $request->image->getClientOriginalName();
    //         // $request->image->storeAs('public/test', $imagename);
    //         $img = $request->profile;
    //         // $image_path = ;
    //         if (!empty($upload->profile)) {
    //             $image_file_arr = explode('/', $upload->profile);
    //             $image_file = $image_file_arr[3];

    //             if (File::exists('storage/profile/' . $image_file)) {
    //                 $image_path = 'storage/profile/' . $image_file;
    //                 File::delete($image_path);
    //             }
    //             // return('image_filtgtre_arr');

    //             $imagename = Storage::disk('uploads')->put('profile', $img);
    //                 // return 'ppp';
    //         } else {
    //             $imagename = Storage::disk('uploads')->put('profile', $img);
    //         }
    //         return $imagename;
    //         $imgArr = explode('/', $imagename);
    //         $image_name = $imgArr[1];
    //         $upload->profile = '/storage/profile/' . $image_name;
    //         // $upload->image = '/storage/profile/'.$image_name;
    //         $upload->save();
    //         return $upload;
    //         // $imagename =  Storage::disk('uploads')->put('profile', $img);
    //     }
    //     return;

    // }

    public function profile(Request $request, $id)
    {
        $upload = User::find($request->id);
        if ($request->hasFile('image')) {
            // return('test');
            // $imagename = time() . $request->image->getClientOriginalName();
            // $request->image->storeAs('public/test', $imagename);
            $img = $request->image;
            // $image_path = ;

            if (File::exists('storage/profile/' . $upload->image)) {

                // return('test');
                $image_path = 'storage/profile/' . $upload->image;

                File::delete($image_path);
                // return $image_path;
            }
            // $imagename =  Storage::disk('uploads')->put('profile', $img);
            $imagename = Storage::disk('public')->put('profile', $img);
        }

        // return('noop');
        $imgArr = explode('/', $imagename);
        $image_name = $imgArr[1];
        $upload->profile = '/storage/profile/' . $image_name;
        // $upload->profile = '/healthwise/products/'.$image_name;
        $upload->save();
        return $upload;
    }

    public function getDrivers()
    {
        $users = User::all();
        $userArr = [];
        foreach ($users as $user) {
            if ($user->hasRole('Rider')) {
                $userArr[] = $user;
            }
        }
        return $userArr;
    }

    public function getCustomer()
    {
        $users = User::all();
        $userArr = [];
        foreach ($users as $user) {
            if ($user->hasRole('Client')) {
                $userArr[] = $user;
            }
        }
        return $userArr;
    }

    public function getSorted(Request $request)
    {
        // return $request->all();
        $roles = User::all();
        $users_id = [];
        $users = User::all();
        $userArr = [];
        foreach ($users as $user) {
            if ($user->hasRole($request->name)) {
                $userArr[] = $user;
            }
        }
        return $userArr;
    }

    public function getUserPro(Request $request, $id)
    {
        return Shipment::where('client_id', $id)->paginate(10);
    }

    public function getUserPerm(Request $request, $id)
    {
        $user = User::find($id);
        $permissions = $user->getAllPermissions();
        $can = [];
        foreach ($permissions as $perm) {
            $can[] = $perm->name;
        }
        return $can;
    }

    public function password(Request $request)
    {
        $this->Validate($request, [
            'password' => 'required|string|min:6',
            // 'password' => 'required|string|min:6|confirmed',
        ]);
        $user = User::find(Auth::id());
        $user->password = Hash::make($request->password);
        $user->save();
        return $user;
    }

    public function UserShip()
    {
        return Shipment::where('client_id', $id)->orWhere('driver', $id)->paginate(10);
    }
    public function logoutOther()
    {
        return view('auth.Logout');
    }

    public function logOtherDevices(Request $request)
    {
        Auth::logoutOtherDevices($request->password);
        return redirect('/courier');
    }

    public function permisions(Request $request, $id)
    {
        // return $request->all();
        $user = User::find($id);
        $user->syncPermissions($request->selected);
        return $user;
    }


    public function undeletedUser($id)
    {
        return User::where('id', $id)->restore();
    }

    public function deletedUsers()
    {
        $users = User::onlyTrashed()->get();
        $users->transform(function ($user, $key) {
            $branch_name = Branch::find($user->branch_id);
            $country_name = Country::find($user->country_id);
            $user->branch = $branch_name->branch_name;
            $user->country = $country_name->country_name;
            $user->status = 'active';
            return $user;
        });
        return $users;
    }
}
