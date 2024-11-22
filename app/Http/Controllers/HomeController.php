<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Planshet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index(){
        $Planshet = Planshet::get();
        return view('home',compact('Planshet'));
    }
    public function home_create(){
        return view('home_create');
    }
    public function home_create_post(Request $request){
        $validate = $request->validate([
            'name' => 'required',
            'json' => 'nullable',
        ]);
        $validate['json'] = $request->input('json', 'null');
        $user = auth()->user()->name;
        Planshet::create([
            'name' => $request->name,
            'json' => $request->json,
            'user' => $user,
        ]);
        return redirect()->route('home')->with('status', "Yangi form qo'shildi");
    }
    public function home_del($id){
        $Planshet = Planshet::find($id);
        $Planshet->delete();
        return redirect()->route('home')->with('status', "Post o'chirildi");
    }
    public function home_show($id){
        $Planshet = Planshet::find($id);
        return view('home_show',compact('Planshet'));
    }
    public function update_post(Request $request){
        $request->validate([
            'input_field' => 'required',
            'textarea_field' => 'required|string',
        ]);
        $Planshet = Planshet::find($request->input_field);
        $Planshet->json = $request->textarea_field;
        $Planshet->save();
        if ($Planshet) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 500);
    }
    public function users(){
        $User = User::where('email','!=',auth()->user()->email)->get();
        return view('users',compact('User'));
    }
    public function users_create(Request $request){
        $validate = $request->validate([
            'name' => 'required',
            'type' => 'required',
            'email' => 'required',
        ]);
        User::create([
            'name' => $request->name,
            'type' => $request->type,
            'email' => $request->email,
            'password' => Hash::make('12345678'),
        ]);
        return redirect()->back()->with('status', "Yangi hodim qo'shildi. Parol: 12345678");
    }
    public function user_del($id){
        $User = User::find($id);
        $User->delete();
        return redirect()->back()->with('status', "Hodim o'chirildi");
    }
    public function profel(){
        return view('profel');
    }
    public function update_password(Request $request){
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['error' => 'Joriy parol noto‘g‘ri']);
        }
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();
        return back()->with('status', 'Parol muvaffaqiyatli yangilandi!');
    }
}
