<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

use Image;

class UserController extends Controller
{

    public function __construct(){
        $this->middleware('can:users.index')->only('index');
        $this->middleware('can:users.edit')->only('edit', 'update');
    }

    public function index(){
        // $category_name = '';
        $data = [
            'category_name' => 'users',
            'page_name' => 'users',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];

        $users = User::whereIn('status', ['active', 'inactive'])->get();


        // $pageName = 'analytics';
        return view('pages.user.index')->with($data)->with('users',$users);
    }

    public function create(){
        // $category_name = '';
        $data = [
            'category_name' => 'users',
            'page_name' => 'userscreate',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];

        $roles = Role::all();

        // $pageName = 'analytics';
        return view('pages.user.create')->with($data)->with('roles',$roles);
    }

    public function store(Request $request)
    {
        $users = new User();

        $request->validate(
            [
                'name'              =>      'required|string',
                'lastname'          =>      'required|string',
                'company_name'      =>      'nullable|string',
                'company_website'   =>      'nullable|string',
                'email'             =>      'required|email|unique:users,email',
                'phone_code'        =>      'required|string',
                'phone'             =>      'nullable|string',
                'source'            =>      'nullable|string',
                'password'          =>      'required|alpha_num|min:6'
            ]
        );

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $photouser = rand(11111111, 99999999) . $image->getClientOriginalName();

            $image_resize = Image::make($image->getRealPath());
            $image_resize->resize(300, 300);
            $image_resize->save(storage_path('app/public/uploads/profile_images/' . $photouser));
        } else {
            $photouser = 'default.jpg';
        }


        $users->name = $request->name;
        $users->lastname = $request->lastname;
        $users->company_name = $request->company_name;
        $users->company_website = $request->company_website;
        $users->email = $request->email;
        $users->phone_code = $request->phone_code;
        $users->phone = $request->phone;
        $users->source = $request->source;
        $users->password = bcrypt($request->password);
        $users->photo = $photouser;
        $users->status = $request->get('status');

        $users->save();

        $users->assignRole($request->input('roles'));

        return redirect()->route('users.index');
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        // $category_name = '';
        $data = [
            'category_name' => 'users',
            'page_name' => 'usersedit',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];

        $user = User::find($id);
        $roles = Role::all();

        // $pageName = 'analytics';
        return view('pages.user.edit')->with($data)->with('user',$user)->with('roles',$roles);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
            if ($request->get('password') != '') {
                $pass = bcrypt($request->get('password'));
            } else {
                $pass = $user->password;
        }

        $currentImage = $user->photo;

        if($request->file('photo') != '') {
            $image = $request->file('photo');
            $path = public_path() . '/assets/img';
            $photouser = rand(11111111, 99999999) . $image->getClientOriginalName();
            $image_resize = Image::make($image->getRealPath());
            $image_resize->resize(500, 500);
            $image_resize->save(storage_path('app/public/uploads/profile_images/' . $photouser));
        } else {
            $photouser = $currentImage;
        }

        User::whereId($id)->update([
            'name' => $request['name'],
            'lastname' => $request['lastname'],
            'company_name' => $request['company_name'],
            'company_website' => $request['company_website'],
            'password' => $pass,
            'phone_code' => $request['phone_code'],
            'phone' => $request['phone'],
            'source' => $request['source'],
            'photo' => $photouser,
            'status' => $request['status'],
        ]);

        $userinfo = User::find($id);
        $userinfo->roles()->sync($request->roles);

        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        //cambiar el status a deleted
        $user = User::findOrFail($id);
        $user->status = 'deleted';
        $user->save();

        return redirect()->route('users.index');
    }


    public function myprofile(){
        $id = \Auth::user()->id;
        // $category_name = '';
        $data = [
            'category_name' => 'users',
            'page_name' => 'myprofile',
            'has_scrollspy' => 0,
            'scrollspy_offset' => '',
        ];

        $user = User::find($id);
        $roles = Role::all();

        // $pageName = 'analytics';
        return view('pages.user.myprofile')->with($data)->with('user',$user)->with('roles',$roles);
    }

    public function updatemyprofile(Request $request){
        $id = \Auth::user()->id;

        $user = User::findOrFail($id);
            if ($request->get('password') != '') {
                $pass = bcrypt($request->get('password'));
            } else {
                $pass = $user->password;
        }

        $currentImage = $user->photo;

        if($request->file('photo') != '') {
            $image = $request->file('photo');
            $path = public_path() . '/assets/img';
            $photouser = rand(11111111, 99999999) . $image->getClientOriginalName();
            $image_resize = Image::make($image->getRealPath());
            $image_resize->resize(500, 500);
            $image_resize->save(public_path('/assets/img/' . $photouser));
        } else {
            $photouser = $currentImage;
        }

        User::whereId($id)->update([
            'name' => $request['name'],
            'lastname' => $request['lastname'],
            'company_name' => $request['company_name'],
            'company_website' => $request['company_website'],
            'phone_code' => $request['phone_code'],
            'phone' => $request['phone'],
            'source' => $request['source'],
            'password' => $pass,
            'photo' => $photouser,
        ]);


        return redirect()->back();
    }

}
