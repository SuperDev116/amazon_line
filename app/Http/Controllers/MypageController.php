<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Product;
use App\Models\ExhibitSettings;
use App\Models\Brands;
use App\Models\User;
use App\Models\Item;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{  
	public function check_pwd(Request $request)
	{
		$user_data = json_decode($request['postData']);
		$user = User::find(Auth::user()->id);
		$password = $user_data->password;

		if (!Hash::check($password, $user->password)) {
			return true;
		}
	}

	public function change_pwd(Request $request)
	{
		$newPwd = $request['postData'];
		$user = User::find(Auth::user()->id);
		$user->forceFill([
			'password' => Hash::make($newPwd),
		])->save();
	}

	public function change_info(Request $request)
	{
		$user_info = json_decode($request['postData']);
		$user = User::find(Auth::user()->id);
		$user->access_key = $user_info->access;
		$user->secret_key = $user_info->secret;
		$user->save();
	}

	public function change_line(Request $request)
	{
		$user_info = json_decode($request['postData']);
		$user = User::find(Auth::user()->id);
		$user->access_token = $user_info->access;
		$user->save();
	}

	public function delete_account(Request $request)
	{
		$id = $request->id;
		User::find($id)->delete();
	}

	public function permit_account(Request $request)
	{
		$id = $request['id'];
		$user = User::find($id);
		$user->is_permitted = $request['isPermitted'];
		$user->save();
	}

	public function save_user_setting(Request $request)
	{
		$req = json_decode($request['exData']);
		$user = User::find(Auth::user()->id);
		$user->interval = $req->interval;
		$user->five = $req->five;
		$user->ten = $req->ten;
		$user->twenty = $req->twenty;
		$user->thirty = $req->thirty;
		$user->over = $req->over;
		$user->file_name = $req->file_name;
		$user->len = $req->len;
		$user->save();
	}
}
