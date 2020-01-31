<?php

namespace App\Http\Controllers;

use App\Review;
use App\Comment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index(){
        $reviews = Review::where('status', 1)->orderBy('created_at', 'DESC')->paginate(9);
        
        $users = DB::table('users')->get();
        
        /*$likes = $users = DB::table('reviews')->leftJoin('users', 'reviews.user_id', '=', 'users.id')->leftJoin('likes', 'users.id', '=', 'likes.user_id')->get();
        /*dd($likes);*/
    	return view('index', compact('reviews','users'));
    	
    }
    
    public function show($id){
        $review = Review::where('id', $id)->where('status', 1)->first();
        /*dd($review);*/
        
        $review_user_id = Review::where('id', $id)->select('user_id')->first();
        //($review_user_id);
        //dd($review_user_id->user_id);
        $num = $review_user_id->user_id;
        /*dd($num);*/
        
        $user = User::where('id',$num)->select('name')->first();
        /*dd($user->name);*/
        
        $comment_id = Review::where('id', $id)->where('status', 1)->first()->id;
        //dd($comment_id);
        
        $comments = Comment::where('review_id', $comment_id)->get();
        //dd($comments);
        

        return view('show', compact('review','user','comments'));
    }
    
    public function create(){
        return view('review');
    }
    
    public function store(Request $request)
    {
        $post = $request->all();
        
        $validatedData = $request->validate([
        'title' => 'required|max:255',
        'body' => 'required',
        'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        if ($request->hasFile('image')) {
            $request->file('image')->store('/public/images');
            $data = ['user_id' => \Auth::id(), 'title' => $post['title'], 'body' => $post['body'], 'url' => $post['url'], 'image' => $request->file('image')->hashName()];
        
        } else {
          $data = ['user_id' => \Auth::id(), 'title' => $post['title'], 'body' => $post['body']];
        }
        
        Review::insert($data);

        return redirect('/')->with('flash_message', '投稿が完了しました');
    }
    
    public function comment(Request $request)
    {
        $post = $request->all();
        //dd($post);
        
        
        
        $validatedData = $request->validate([
        'description' => 'required|max:255',
        ]);
        
        $url = url()->previous();
        $keys = parse_url($url);
        $path = explode("/", $keys['path']);
        $last_num = end($path);
        
        $reviews_id = Review::where('id', $last_num)->where('status', 1)->first();


        $review_id = $reviews_id->id;
        
        $data = ['review_id' => $review_id, 'description' => $post['description']];
        
        Comment::insert($data);

        return redirect('/')->with('flash_message', 'コメントが送信されました');
    }
    
}
