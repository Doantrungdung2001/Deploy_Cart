<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Cart;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Session;
use App\Http\Controllers\GetDataController;
class CartController extends Controller
{
    public function Index(){
        $res = Http::get('https://p01-product-api-production.up.railway.app/api/user/products');
        return view('home',['product'=> $res['data']]);
    
    }

    public function AddCart(Request $req,$id){
        $res = Http::get('https://p01-product-api-production.up.railway.app/api/user/products');
        foreach($res['data'] as $prd){
            if($prd['sub_products'] != null){
                foreach($prd['sub_products'] as $item){
                    if($item['id'] == $id){
                        $product = array(
                            'id' =>$item['id'],
                            'name'=> $prd['name'],
                            'price'=>$prd['cost'],
                            'size'=>$item['size'],
                            'color'=> $item['color'],
                            'image_url'=>$item['image_url']
                        );
                        if(Session('Cart') != null){
                                $oldcart = Session('Cart');//oldcart la gio hang hien tai
                        }else{
                                $oldcart = null;
                        }
                        //$id_user = $req->id;
                        //Gio hang moi
                        $newcart = new Cart($oldcart); //tao mot doi tuong gio hang moi tu lop Cart    
                        $newcart->AddCart($product,$id);
                        $req->session()->put('Cart',$newcart);                        
                    }
                }
            }
        }
        return view('item');  
        
    }

    public function DeleteItemCart(Request $req,$id){
        if(Session('Cart') != null){
            $oldcart = Session('Cart');
        }else{
            $oldcart = null;
        }
        $newcart = new Cart($oldcart); //tao mot doi tuong gio hang moi tu lop Cart    
        $newcart->DeleteItemCart($id);
        if(Count($newcart->product) > 0){
            $req->Session()->put('Cart',$newcart);
        }else{
            $req->Session()->forget('Cart');
        }
        return view('item');
    }

    public function ViewCart(){
        return view('cart');
    }
    
    public function DeleteItemListCart(Request $req,$id){
        # code...
        if(Session('Cart') != null){
            $oldcart = Session('Cart');
        }else{
            $oldcart = null;
        }
        $newcart = new Cart($oldcart); //tao mot doi tuong gio hang moi tu lop Cart    
        $newcart->DeleteItemCart($id);
        if(Count($newcart->product) > 0){
            $req->Session()->put('Cart',$newcart);
        }else{
            $req->Session()->forget('Cart');
        }
        return view('list-cart');
    }

    public function SaveItemListCart(Request $req,$id,$quanty){
        # code...
        if(Session('Cart') != null){
            $oldcart = Session('Cart');
        }else{
            $oldcart = null;
        }
        $newcart = new Cart($oldcart); //tao mot doi tuong gio hang moi tu lop Cart    
        $newcart->UpdateItemCart($id,$quanty);

        $req->Session()->put('Cart',$newcart);
        //dd($newcart);
        return view('list-cart');   
    }

    public function product_cart(Request $req){
        if(Session('Cart')){
            $data = [];
            foreach (Session::get('Cart')->product as $item) {
                array_push($data,$item);
            }
            $id_user = Session::get('Cart')->id_user;
            $totalPrice = Session::get('Cart')->totalPrice;
            $totalQuanty = Session::get('Cart')->totalQuanty;
        }
        $product = ['Item'=>$data,
                    'toatalQuanty' => $totalQuanty,
                    'toatalPrice' => $totalPrice,
                    'id_user' => $id_user];
        return $product;
    }
    public function total_product_cart(Request $req){
        if(Session('Cart')){
            $value = $req->session()->get('Cart');
        }
        // return $value->totalQuanty;
        return response([
            //'id_user'=> $value->id_user,
            'toatalQuanty' => $value->totalQuanty
        ]);
    }
}
