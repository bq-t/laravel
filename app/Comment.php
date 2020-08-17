<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

	public function GetReply() {
		return $this->hasOne('App\CommentReply');
	}

	public static function CreateCommentsArray($query, $auth) {

        $data = [];

        foreach ($query as $comment) {
            $username = User::find($comment->user_id)->name;

            $arr = $comment->toArray();

            $arr = array_merge($arr, array(
                'username' => $username, 
                'auth' => $auth
            ));

            $get_quote = $comment->GetReply;

            if($get_quote != null) {

            	$quote = static::where('id', $get_quote->to_comment_id)->first();
            	$quote_user = User::find($quote->user_id)->name;

            	$arr = array_merge($arr, array(
            		'quote_name' => $quote_user,
            		'quote_text' => $quote->text,
            		'quote_date' => $quote->created_at,
            		'quote_del' => $quote->delete
            	));
            }

            array_push($data, $arr);
        }

        return $data;
	}
}
