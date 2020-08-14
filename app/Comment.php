<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

	public function GetReply() {
		return $this->hasOne('App\CommentReply');
	}

	public static function CreateCommentsArray($query) {

        $data = [];

        foreach ($query as $comment) {
            $username = User::find($comment->user_id)->name;
            $id = $comment->id;

            $arr = array(
                'id' => $id,
                'user_id' => $comment->user_id, 
                'username' => $username, 
                'created_at' => $comment->created_at, 
                'title' => $comment->title, 
                'theme' => $comment->theme, 
                'text' => $comment->text, 
                'page_id' => $comment->page_id,
                'delete' => $comment->delete
            );

            if(auth()->user() == NULL){
            	$auth = [ 'auth' => -1 ];
            }
            else {
            	$auth = [ 'auth' => auth()->user()->id ];
            }
            $arr = array_merge($arr,$auth);

            $get_quote = static::find($id)->GetReply;

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
