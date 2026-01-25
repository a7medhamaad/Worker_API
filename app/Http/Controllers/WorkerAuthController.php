<?php

namespace App\Http\Controllers;

use App\Services\WorkerService\WorkerLoginService\WorkerLoginService;
use App\Services\WorkerService\WorkerRegisterService\WorkerRegisterService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Worker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WorkerAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:worker', ['except' => ['login', 'register','verifiy']]);
    }

    public function login(LoginRequest $request)
    {

        return (new WorkerLoginService())->login($request);
        // $request->validate([
        //     'email' => 'required|string|email',
        //     'password' => 'required|string',
        // ]);
        // $credentials = $request->only('email', 'password');
        // $token = Auth::attempt($credentials);

        // if (!$token = auth()->guard('worker')->attempt($credentials)) {
        //     return response()->json([
        //         'message' => 'Unauthorized',
        //     ], 401);
        // }

        // $user = auth()->guard('worker')->user();

        // return response()->json([
        //     'user' => $user,
        //     'authorization' => [
        //         'token' => $token,
        //         'type' => 'bearer',
        //     ]
        // ]);
    }

    public function register(RegisterRequest $request)
    {
        return (new WorkerRegisterService())->register($request);
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:workers',
        //     'password' => 'required|string|min:6',
        //     'phone' => 'required|string|max:17',
        //     'photo' => 'required|image|mimes:jpg,png,jpeg',
        //     'location' => 'required|string|min:6',
        // ]);

        // $photoPath = $request->file('photo')->store('workers', 'public');

        // $worker = Worker::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        //     'phone' => $request->phone,
        //     'photo' => $photoPath,
        //     'location' => $request->location,
        //     'status'=>$request->status,
        // ]);

    //     return response()->json([
    //         'message' => 'User created successfully',
    //         'user' => $worker
    //     ], 201);
    }

    public function logout()
    {
        auth()->guard('worker')->logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function userProfile()
    {
        return response()->json(auth()->Guard('worker')->user());
    }

    // public function createNewToken($token)
    // {
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => auth()->factory()->getTTL() * 60,
    //         'user' => auth()->guard('worker')->user(),
    //     ]);
    // }

    function verify($token){
        $worker=Worker::whereVerificationToken($token)->first();
        if(!$worker){
            return response()->json([
                "message"=>"this Link is invalid"
            ]);
        }
        $worker->verification_token=null;
        $worker->verified_at=now();
        $worker->save();
        return response()->json([
                "message"=>"Your Account Has been Verifiyed"
            ]);
    }
}
