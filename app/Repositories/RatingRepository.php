<?php

namespace App\Repositories;


use App\Models\Reviews;
use Illuminate\Support\Facades\Auth;

class RatingRepository {

    public function __construct(Reviews $reviews) {
        $this->reviews = $reviews;

    }
    public function saveAppraiserRating($request){
        $post = $request->all();
       foreach ($post['user_rating'] as $key => $value) {
            // print_r( $key.$value);
            $rating['job_id'] =  $post['job_id'];
            $rating['from_id'] = Auth::user()->id;
            $rating['to_id'] =  $key;
            $rating['rating'] =  $value;
            $model =   $this->reviews->create($rating);
        }

        return $model;
    }

}
