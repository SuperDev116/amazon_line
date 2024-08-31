<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Product;
use App\Models\Error;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportProduct;

class ProductController extends Controller
{
	public function register_products(Request $request)
	{
		$user = User::find(Auth::id());
		$user->round = 0;
		$user->trk_num = 0;
		$user->stop = 1;
		$user->save();
		Product::where('user_id', $user->id)->delete();

		$req = json_decode($request['asin']);
		$codes = $req->codes;
		foreach ($codes as $c) {
			$product = new Product;
			$product->user_id = $user->id;
			$product->asin = $c->asin;
			// $product->image = $user->image;
			$product->reg_price = $c->price;
			$product->pro = $c->pro;
			$product->price = $c->price;
			$product->tar_price = floor($c->price * $c->pro / 100);
			$product->url = 'https://www.amazon.co.jp/dp/' . $c->asin .'?tag=gnem03010a-22&linkCode=ogi&th=1&psc=1';
			$product->save();
		}

		$user = User::find(Auth::id());
		$user->stop = 0;
		$user->save();
	}

	public function list_product()
	{
		$user = Auth::user();
		$products = Product::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(10);
		return view('mypage.product_list', ['user' => $user, 'products' => $products]);
	}

	public function delete_product()
	{
		$user = Auth::user();
		$products = Product::where('user_id', $user->id);
		$products->delete();

		$user = User::find(Auth::id());
		$user->round = 0;
		$user->trk_num = 0;
		$user->stop = 1;
		$user->save();
		return redirect()->route('register_product');
	}
	
	public function remove_product(Request $request)
	{
		Product::where('id', $request->product_id)->delete();
		return;
	}

	public function scan()
	{
		$user = User::find(Auth::user()->id);
		return $user;
	}

	public function csv_download(Request $request)
	{
		return Excel::download(new ExportProduct, 'products.xlsx');
	}
	
	public function csv_down(Request $request)
	{
		$data = "ASIN,価格,下落%,Keepa URL,再通知間隔\n";

		$user = Auth::user();
		$products = Product::where('user_id', $user->id)->get();
		foreach ($products as $p) {
			$data .= $p['asin'].",".($p['price'] == 0 ? $p['reg_price'] : $p['price']).",".$p['pro'].", https://keepa.com/#!product/5-".$p['asin'].",".$p['inter']."\n";
		}
		echo $data;
		exit();

		$filename = $user->file_name ?? "価格監視.csv";

		header('Content-Type: application/csv');
		header('Encoding: utf-8');
		header('Content-Disposition: attachment; filename="'.$filename);
		echo $data;
		exit();

		// $file = fopen($filename, "w+");
		// fwrite($file, $data);
		// fclose($file);
		// return;
	}

	public function stop()
	{
		$user = User::find(Auth::id());
		$user->round = 0;
		$user->trk_num = 0;
		$user->stop = 1;
		$user->save();
	}

	public function restart()
	{
		$user = User::find(Auth::id());
		$user->stop = 0;
		$user->save();
	}

	public function change_value(Request $request) {
		$col = $request->col;
		$value = $request->value;
		$user = User::find(Auth::id());
		$user->update([
			$col => $value
		]);
		return $user;
	}
}
