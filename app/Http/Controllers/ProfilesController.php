<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\User;

class ProfilesController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(User $user)
    {
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;
        //dd($follows); 
    	// dd($user);
    	//$user = User::findOrFail($user);
        return view('profiles.index', compact('user','follows'));
    }

    public function edit(User $user)
    {
        $this->authorize('update',$user->profile);

        return view('profiles.edit',compact('user'));
    }

    public function update(User $user)
    {
        $data = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url',
            'image' => '',
        ]);

        if(request('image')){

            $imagePath = request('image')->store('profile','public');

            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000,1000);
            $image->save();

            $imageArray = ['image' => $imagePath];
        }

        //dd($data);

        auth()->user()->profile->update(array_merge($data,
            $imageArray ?? []    
        ));

        return redirect("/profile/{$user->id}");
        //dd($data);
    }
}
