<?php

namespace App\Http\Controllers;

use App\Post;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

class PostsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function create()
    {
    	return view('posts.create');
    }

    public function store(Request $request)
    {
    	$data = $request->validate([
    		'caption' => 'required',
    		'image' => 'required | image',
    	]);

    	$imagePath = request('image')->store('uploads','public');

    	$image = Image::make(public_path("storage/{$imagePath}"))->fit(1200,1200);
    	$image->save();

    	auth()->user()->posts()->create([
    		'caption' => $data['caption'],
    		'image'   => $imagePath,
    	]);

    	return redirect('/profile/' . auth()->user()->id);
    }

    public function show(Post $post)
    {
        return view('posts.show',compact('post'));
        //dd($post);
    }
}
