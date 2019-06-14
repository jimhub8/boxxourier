<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FileManagement\Props;
use App\FileManagement\Repositories\Attachment\AttachmentRepository;
use Auth;
use Exception;
use App\AttachmentCategory;
use App\Attachment;
use App\User;
use App\Company;
use Spatie\Permission\Models\Permission;
use App\Country;

class HomeController extends Controller
{
    public function courier()
    {
        $permissions = [];
        foreach (Permission::all() as $permission) {
            if (Auth::user()->can($permission->name)) {
                $permissions[$permission->name] = true;
            } else {
                $permissions[$permission->name] = false;
            }
        }
        $user = Auth::user();
        $country = Country::find($user->country_id);
        // dd($country);
        $user->country_name = $country->country_name;
        // $users->transform(function ($user, $key) {
        //     $country = Country::find($user->country_id);
        //     $user->country_name = $country->name;
        // 	return $user;
        // });
        // dd(json_decode(json_encode((Auth::user()), false)));
        $auth_user = array_prepend($user->toArray(), $permissions, 'can');
        return view('welcome', compact('auth_user'));
    }
    public function courierHome()
    {
        return redirect('/')->where('name', '[A-Za-z]+');
    }

    /**
     * Load homepage
     * 
     * @param  Request $request 
     * @return view           
     */
    public function index(Request $request)
    {
        // $props = Props::get();
        // return redirect('/login');
        if (Auth::check()) {
            return redirect('/courier');
        }
    }

    public function referral()
    {
        return view('referral');
    }
}
