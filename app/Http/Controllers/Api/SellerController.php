<?php

namespace App\Http\Controllers\Api;

use App\Models\Seller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerController extends Controller
{

    /*store seller*/
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'      => 'required|string|max:255',
                'email'     => 'required|email|unique:sellers,email',
                'mobile_no' => 'required|digits_between:10,15|unique:sellers,mobile_no',
                'country'   => 'required|string|max:100',
                'state'     => 'required|string|max:100',
                'skills'    => 'required|string|min:1',
                'password'  => 'required|string|min:6'
            ]);

            $seller = Seller::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'mobile_no' => $validated['mobile_no'],
                'country'   => $validated['country'],
                'state'     => $validated['state'],
                'skills'    => $validated['skills'],
                'password'  => Hash::make($validated['password']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Seller Created successfully.',
                'data'    => $seller,
                'code'    => '201'
            ], 201);

        } 
        
        catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors'  => $e->errors()
            ], 422);
        } 
        
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    


    /* listing of Seller */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);

            $sellers = Seller::orderBy('id', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Seller List Fetched Successfully.',
                'data'    => $sellers,
                'code'    => "200"
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sellers.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    /*   seller  Login*/
    public function sellerLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);

        $seller = Seller::where('email', $request->email)->first();

        if (! $seller || ! Hash::check($request->password, $seller->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
                'code' => '401'
            ], 401);
        }

        $token = $seller->createToken('seller-token', ['seller'])->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Seller Login Successfully.',
            'role'    => 'seller',
            'token'   => $token
        ], 200);
    }
}
    
