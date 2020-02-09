<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public $loginAfterSignUp = true;

    public function register(Request $request)
    {
      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'category' => $request->category,
      ]);

      $token = auth()->login($user);
      // auth()->factory()->setTTL(15);

      return $this->respondWithToken($token);
    }

    public function login(Request $request)
    {
      $credentials = $request->only(['email', 'password']);

      if (!$token = auth()->attempt($credentials)) {
        // if (!$token = auth()->attempt($credentials, ['exp' => Carbon::now()->addDays(7)->timestamp])) {
        return response()->json(['error' => 'Unauthorized'], 401);
      }
      return $this->respondWithToken($token);
    }
    public function getAuthUser(Request $request)
    {
        return response()->json(auth()->user());
    }
    public function logout()
    {
        auth()->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }
    protected function respondWithToken($token)
    {
      return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 60
      ]);
    }
    public function checkToken()
    {
      try{
        $payload = JWTAuth::parseToken()->getPayload();
        $ttl = Carbon::createFromTimestampUTC($payload['exp'])->diffInSeconds();
        
        //If 5minutes below, refresh token
        if($ttl <= 300){
          JWTAuth::setToken(JWTAuth::refresh());
          $user = JWTAuth::authenticate();
        }else{
          $user = JWTAuth::authenticate();
        }

      } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
        return response()->json(['error' => 'Token expired'], 401);

      } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
        return response()->json(['error' => 'Invalid token'], 401);

      } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json(['error' => 'No token'], 401);
      }
        
      if($user){
         return response()->json([
           'ttl' => $ttl,
           'accessToken'=> JWTAuth::getToken()->get()
          ], 200);
      }else {
          return response()->json(false, 401);
      }
    }

}
