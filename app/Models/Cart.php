<?php
namespace App\Models;
class Cart{
    public $product;
    public $totalPrice = 0;
    public $totalQuanty =0;
    public $id_user =0;
    public function __construct($cart){
        if($cart){
            $this->product = $cart->product;
            $this->totalPrice = $cart->totalPrice;
            $this->totalQuanty = $cart->totalQuanty;
            $this->id_user = $cart->id_user;
        } 
    }
    
    public function AddCart($product , $id){
        $newProduct = ['quanty'=>0,'price'=>$product['price'],'productInfo'=>$product];
        if($this->product){
            if(array_key_exists($id,$this->product)){
                $newProduct =$this->product[$id];
            }
        }
        
        $newProduct['quanty']++;
        $newProduct['price'] = $newProduct['quanty'] * $product['price'];
        $this->product[$id] = $newProduct;
        $this->totalPrice += $product['price'];
        $this->totalQuanty++;
        $this->id_user = 2;
    }

    public function DeleteItemCart($id){
        $this->totalQuanty -=$this->product[$id]['quanty'];
        $this->totalPrice -= $this->product[$id]['price'];
        unset($this->product[$id]);
    }

    public function UpdateItemCart($id ,$quanty){
        # code...
        $this->totalQuanty -= $this->product[$id]['quanty'];
        $this->totalPrice -= $this->product[$id]['price'];
        
        $this->product[$id]['quanty'] = $quanty;
        $this->product[$id]['price'] = $quanty * $this->product[$id]['productInfo']['price'];

        $this->totalQuanty += $this->product[$id]['quanty'];
        $this->totalPrice += $this->product[$id]['price'];
    }
}
?>