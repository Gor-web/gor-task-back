<?php

namespace App\Http\Controllers;
use App\Mail\TestMail;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;



class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register','checkVerifyCode']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Support\MessageBag
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request) {
        $rules =  [
            'name' => 'required|string|between:2,100',
            'lastname' => 'required|string|between:2,100',
            'age'=>'numeric|required',
//            'photo' => ['image', 'dimensions:max_width=1000,max_height=1000'],
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
              return $errors;
        } else {
            $details = [
                'title' => 'Social network Gor',
                'body' => 'This is an email verification code'
            ];
            $random_code =rand(100000,999900);
            Storage::put('my_info/code', $random_code);
            Storage::put('my_info/name', $request->name);
            Storage::put('my_info/lastname', $request->lastname);
            Storage::put('my_info/email', $request->email);
            Storage::put('my_info/age', $request->age);
            Storage::put('my_info/password', $request->password);
            $email = $request->email;
            return Mail::to("$email")->send(new TestMail($details, $random_code));
        }
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }


    public function checkVerifyCode(Request $request){
        $random_code=Storage::get('my_info/code');
        $name=Storage::get('my_info/name');
        $age=Storage::get('my_info/age');
        $lastname=Storage::get('my_info/lastname');
        $email=Storage::get('my_info/email');
        $password=Storage::get('my_info/password');

        if ( $request->code == $random_code){
            User::create([
                'name' => $name,
                'lastname' => $lastname,
                'age' => $age,
                'email' => $email,
                'password' => Hash::make($password),
            ]);
            return  "samo";
        }else return "error ";

    }

}
