<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Photo;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminPostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        //        $allPosts = [];

        //slow
        //Post::all();

        //faster, with chunks
        //        Post::with(["categories, user, photo"])->chunk(100, function (
        //            $posts
        //        ) use (&$allPosts) {
        //            foreach ($posts as $post) {
        //                $allPosts[] = $post;
        //            }
        //        });

        //faster, with paginate (chunks automatically)
        $posts = Post::with(["categories", "user", "photo"])
            ->filter(request("search"), request("fields")) // refers to 'scopeFilter' from Post class
            ->withTrashed() // we will also show the soft deleted posts (so we can restore them)
            ->paginate(20)
            ->appends([
                "search" => request("search"),
                "fields" => request("fields"),
            ]);
        return view("admin.posts.index", [
            "posts" => $posts,
            "fillableFields" => Post::getFillableFields(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $categories = Category::all();
        return view("admin.posts.create", compact("categories"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        request()->validate(
            [
                "title" => ["required", "between:2,255"],
                "categories" => ["required", Rule::exists("categories", "id")],
                "body" => "required",
                "photo_id" => "required", // or give a default value of NULL if no photo is uploaded
            ],
            [
                "title.required" => "Title is required",
                "title.between" => "Title between 2 and 255 characters",
                "body.required" => "Message is required",
                "categories.required" => "Please choose at least one category",
                "photo_id.required" => "Please upload a photo",
            ]
        );
        $post = new Post();
        $post->user_id = Auth::user()->id;
        $post->title = $request->title;
        //$post->slug = $post->slugify($post->title);
        $post->slug = Str::slug($post->title);
        $post->body = $request->body;

        if ($file = $request->file("photo_id")) {
            $path = request()
                ->file("photo_id")
                ->store("posts");
            $photo = Photo::create(["file" => $path]);
            $post->photo_id = $photo->id;
        }

        $post->save();
        /*aangeduide categorieën syncen */
        $post->categories()->sync($request->categories, false);
        return redirect()
            ->route("posts.index")
            ->with("status", "Post Created");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Post $post)
    {
        //$post = Post::findOrFail($id);
        // slug is a computed property, so don't need to add it as a field in our table, instead use a function in code.
        $slug = $post->slugify($post->title);

        return view("admin.posts.show", ["post" => $post, "slug" => $slug]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        // or use dependency injection
        $post = Post::findOrFail($id);
        $categories = Category::all();
        return view("admin.posts.edit", compact("categories", "post"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        request()->validate(
            [
                "title" => ["required", "between:2,255"],
                "categories" => ["required", Rule::exists("categories", "id")],
                "body" => "required",
            ],
            [
                "title.required" => "Title is required",
                "title.between" => "Title between 2 and 255 characters",
                "body.required" => "Message is required",
                "categories.required" => "Please choose at least one category",
            ]
        );
        $post = Post::findOrFail($id);
        $input = $request->all();
        $input["slug"] = Str::slug($input["title"]);

        if ($file = $request->hasFile("photo_id")) {
            $oldPhoto = $post->photo;
            $path = request()
                ->file("photo_id")
                ->store("posts");
            //if there wasn't an old photo, create new in db
            if ($oldPhoto) {
                unlink(public_path($oldPhoto->file));
                $oldPhoto->update(["file" => $path]);
                //keep old photo_id (FK in users table)
                $input["photo_id"] = $oldPhoto->id;
            } else {
                //create photo (new id)
                $photo = Photo::create(["file" => $path]);
                //update photo_id (FK in users table)
                $input["photo_id"] = $photo->id;
            }
        }

        $post->update($input);
        /*aangeduide categorieën syncen */
        $post->categories()->sync($request->categories, true);
        return redirect()
            ->route("posts.index")
            ->with("status", "Post Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Post::findOrFail($id)->delete();
        return redirect()
            ->route("posts.index")
            ->with("status", "Post Deleted");
    }

    // Dit is logica dus zetten we in model/controller, niet in web.php
    public function indexByAuthor(User $author)
    {
        $posts = $author->posts()->paginate(20);
        return view("admin.posts.index", ["posts" => $posts]);
    }

    protected function postRestore($id)
    {
        Post::onlyTrashed()
            ->where("id", $id)
            ->restore();
        return back();
        //return redirect()->route('admin.users');
        //return back()
    }
}
