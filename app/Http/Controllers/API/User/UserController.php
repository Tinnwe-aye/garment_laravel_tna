<?php

namespace App\Http\Controllers\API\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * check  resource.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request = $request->all();
        $password = User::where("email", $request["email"])->select('password')->first();
        if($password){
            if (password_verify($request["password"], $password->password)) {
                // Password is correct, allow login
                log::info('Login successful!');
                return response()->json([
                    'status' =>  'OK',
                    'message' =>  trans(" "),
                    ],200);

            } else {
                // Password is incorrect
                log::info('Login failed. Incorrect password.');
                return response()->json([
                    'status' =>  'NG',
                    'message' =>  trans("Email and Password are not match."),
                    ],200);
            }
        } else {
            return response()->json([
                'status' =>  'NG',
                'message' =>  trans("Email and Password are not match."),
                ],200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request= $request->all();
            $user=new User();
            $existingUser = User::where('email', $request["email"])->first();
            if ($existingUser) {
                return response()->json([
                    'status' =>  'NG',
                    'message' =>  trans("Email is already used."),
                    ],200);
            }
            $name = $request["firstName"].$request["lastName"];
            $user->name=$name;
            $user->email=$request["email"];
            $user->password=password_hash($request["password"], PASSWORD_BCRYPT);
            $user->save();

            return response()->json([
                'status' =>  'OK',
                'message' =>  trans(" "),
                ],200);

        } catch (\Exception $e) {
            $fullMessage = $e->getMessage();
            $position = strpos($fullMessage, '(');
            if ($position !== false) {
                // Cut the string before the first occurrence of "("
                $cutString = substr($fullMessage, 0, $position);
                return response()->json([
                'status' =>  'NG',
                'message' =>  trans($cutString),
                ],200);
            }            
        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
