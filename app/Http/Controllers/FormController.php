<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Requests;


class FomeController extends Controller

{


    

    public function formValidate()

    {

    	return view('form-validate');

    }


    public function formValidatePost(Request $request)

    {

    	$this->validate($request,[

    			'firstname' => 'required|min:5|max:35',

    			'lastname' => 'required|min:5|max:35',

    			'email' => 'required|email|unique:users',

    			'mobileno' => 'required|numeric',

    			'password' => 'required|min:3|max:20',

    			'confirm_password' => 'required|min:3|max:20|same:password',

    			'details' => 'required'

    		],[

    			'firstname.required' => ' The first name field is required.',

    			'firstname.min' => ' The first name must be at least 5 characters.',

    			'firstname.max' => ' The first name may not be greater than 35 characters.',

    			'lastname.required' => ' The last name field is required.',

    			'lastname.min' => ' The last name must be at least 5 characters.',

    			'lastname.max' => ' The last name may not be greater than 35 characters.',

    		]);


    	dd('You are successfully added all fields.');

    }

}