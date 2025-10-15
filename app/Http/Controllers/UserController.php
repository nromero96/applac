<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Country;
use App\Models\Department;
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

        //get url parameters
        $type = request()->get('type');

        //1 is Has Role Administrator
        //2 is Has Role Sales
        //3 is Has Role Customer

        if($type == 1 || empty($type)){
            //get users with role administrator
            $users = User::role('administrator')
                            ->where('status', '!=', 'deleted')
                            ->with('department')
                            ->get();
        }elseif($type == 2){
            //get users with role sales
            $users = User::role('sales')
                            ->where('status', '!=', 'deleted')
                            ->with('department')
                            ->get();
        }elseif($type == 3){
            //get users with role customer
            $users = User::role('customer')
                            ->where('status', '!=', 'deleted')
                            ->with('department')
                            ->get();
        }elseif($type == 4){
            //get users with role customer
            $users = User::role('operations')
                            ->where('status', '!=', 'deleted')
                            ->with('department')
                            ->get();
        }else{
            $users = User::where('status', '!=', 'deleted')->get();
        }


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
        $departments = Department::all();
        $contries = Country::all();

        // $pageName = 'analytics';
        return view('pages.user.create')->with($data)->with('roles',$roles)->with('departments', $departments)->with('contries',$contries);
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
                'location'          =>      'nullable',
                'phone_code'        =>      'required|string',
                'phone'             =>      'nullable|string',
                'source'            =>      'nullable|string',
                'password'          =>      'required|alpha_num|min:6',
                'department_id'     =>      'nullable',
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
        $users->location = $request->location;
        $users->phone_code = $request->phone_code;
        $users->phone = $request->phone;
        $users->source = $request->source;
        $users->password = bcrypt($request->password);
        $users->photo = $photouser;
        $users->status = $request->get('status');
        $users->department_id = ($request['department_id'] == '') ? null : $request['department_id'];
        $users->priority_countries = $request['priority_countries'];

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
        $departments = Department::all();
        $contries = Country::all();

        // $pageName = 'analytics';
        return view('pages.user.edit')->with($data)->with('user',$user)->with('roles',$roles)->with('departments', $departments)->with('contries',$contries);
    }

    public function update(Request $request, $id)
    {
        //validar si el email ya existe
        $request->validate(
            [
                'name'              =>      'required|string',
                'lastname'          =>      'required|string',
                'company_name'      =>      'nullable|string',
                'company_website'   =>      'nullable|string',
                'email'             =>      'required|email|unique:users,email,'.$id,
                'location'          =>      'nullable',
                'phone_code'        =>      'required|string',
                'phone'             =>      'nullable|string',
                'source'            =>      'nullable|string',
                'password'          =>      'nullable|alpha_num|min:6',
                'department_id'     =>      'nullable',
            ]
        );

        $user = User::findOrFail($id);
            if ($request->get('password') != '') {
                $pass = bcrypt($request->get('password'));
            } else {
                $pass = $user->password;
        }

        $currentImage = $user->photo;

        if($request->file('photo') != '') {
            $image = $request->file('photo');
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
            'email' => $request['email'],
            'location' => $request['location'],
            'password' => $pass,
            'phone_code' => $request['phone_code'],
            'phone' => $request['phone'],
            'source' => $request['source'],
            'photo' => $photouser,
            'status' => $request['status'],
            'department_id' => ($request['department_id'] == '') ? null : $request['department_id'],
            'priority_countries' => $request['priority_countries'],
        ]);

        $userinfo = User::find($id);
        $userinfo->roles()->sync($request->roles);

        //Retornar a la vista de edicion con mensaje de actualizacion
        return redirect()->route('users.edit', $id)->with('success', 'User updated successfully');
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
        $contries = Country::all();

        // $pageName = 'analytics';
        return view('pages.user.myprofile')->with($data)->with('user',$user)->with('roles',$roles)->with('contries',$contries);
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
            'location' => $request['location'],
            'phone_code' => $request['phone_code'],
            'phone' => $request['phone'],
            'source' => $request['source'],
            'password' => $pass,
            'photo' => $photouser,
        ]);


        return redirect()->back();
    }

    //verificar si el email ya existe
    public function verifyemailRegister(Request $request){
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if($user){
            return response()->json(['status' => 'error', 'message' => 'Email already registered. Change or continue as guest.']);
        }else{
            return response()->json(['status' => 'success', 'message' => 'Email available. Access will be sent there.']);
        }
    }

}
