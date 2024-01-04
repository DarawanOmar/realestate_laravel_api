<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Catigories, User, Cities};
use Illuminate\Database\Eloquent\SoftDeletes;
class Properties extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];

    protected $casts = [
        'images'=>'array',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'images'
    ];
    protected $appends = ['photos'];

    // public function getPhotosAttribute(){
    //     $photos = [];
    //     foreach ($this->images as  $image) {
    //         $photos[] = asset('upload/properties/'.$this->$image);
    //     }
    //     return $photos;
    // }
    public function getPhotosAttribute(){
        $photos = [];
        if(is_array($this->images) || is_object($this->images)) {
            foreach ($this->images as  $image) {
                $photos[] = asset('upload/properties/'.$image);
            }
        }
        return $photos;
    }
    
    public function scopeOfUser($query, $user_id){
       if($user_id){
        return $query->where('user_id',$user_id);
       }else{
        return $query;
       }
    }

    public function scopeOfCatigory($query, $catigorey_id){
        if($catigorey_id){
            return $query->where('catigorey_id',$catigorey_id);
        }else{
            return $query;
        }
    }

    public function scopeOfCity($query, $city_id){
        return $city_id ? $query->where('city_id',$city_id) : $query;
    }

    public function scopeOfSearch($query, $search){
        return $search ? $query->where([['title','LIKE',"%".$search."%"], ['description',"LIKE","%".$search."%"]]) : $query;
    }

    
    public function scopeOfPrice($query, $price){//filterbetween?price[0]=100&price[1]=200&area[0]=50&area[1]=100
        return $price ? $query->whereBetween('price',[$price[0],$price[1]]) : $query;
    }

    public function scopeOfArea($query,$area){
        return $area ? $query->whereBetween('area', [$area[0],$area[1]]) : $query;
    }

    public function scopeOrderByPriceDesc($query) {
        return $query->orderBy('price', 'desc');
    }
    

    public function catigorey(){
        return $this->belongsTo(Catigories::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function city(){
        return $this->belongsTo(Cities::class);
    }
}
