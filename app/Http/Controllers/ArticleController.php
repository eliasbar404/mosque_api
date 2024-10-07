<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Member;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    // Get All Articles
    public function Get_Articles(){
        $articles = Article::all();
        return $articles;
    }

    // Get One Article by ID
    public function Get_Article($id){
        $article = Article::find($id);
        // if($article)
        return $article;
    }

    // Create new Article
    public function Create_Article(Request $request){
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image if it exists
        ]);

        if ($request->hasFile('image')) {
                        // Save the new image
            $imageName = time() . '.' . request()->image->extension();
            request()->image->move(public_path('images/articles'), $imageName);
                
                        // Update the profile picture URL
                        $validatedData['image'] = 'images/articles/' . $imageName;
            // $image = $request->file('image');
            // $imageName = time() . '_' . $image->getClientOriginalName();
            // $imagePath = $image->storeAs('images/articles', $imageName, 'public'); // Store image in 'public/images/articles'
            // $validatedData['image'] = $imagePath; // Save the image path to the validated data
        }

        $article = Article::create($validatedData);

        return response()->json("Create article $article->id successfully", 200);

    }

    // Update An Article
    public function Update_Article($id,Request $request){
        $article = Article::find($id);
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug,' . $article->id,
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image if it exists
        ]);

        // Check if a new image is uploaded
        if ($request->hasFile('image')) {
           // Delete the old image if it exists
            if ($article->image && Storage::disk('public')->exists($article->image)) {
                Storage::disk('public')->delete($article->image);
            }

        //     // Store the new image

        $imageName = time() . '.' . request()->image->extension();
        request()->image->move(public_path('images/articles'), $imageName);
            
                    // Update the profile picture URL
                    $validatedData['image'] = 'images/articles/' . $imageName;
            // $image = $request->file('image');
            // $imageName = time() . '_' . $image->getClientOriginalName();
            // $imagePath = $image->storeAs('images/articles', $imageName, 'public'); // Store image in 'public/images/articles'
            // $validatedData['image'] = $imagePath; // Save the new image path to the validated data
        }

        // Update the article with the validated data (including the new image path if available)
        $article->update($validatedData);

        // if($validation){
        //     $article->title = $request->title;
        //     $article->slug  = $request->slug;
        //     $article->save();
    
            return response()->json("Update Article $article->id successfully", 200);

        // }

        // return response()->json("Faild To Update Article $article->id !", 400);


    }

    // Delete An Article
    public function Delete_Article($id){
        $article = Article::where('id',$id)->first();
        if($article){
            $article->delete();
            return response()->json("Delete article $id successfully", 200);
        }
        return response()->json('There is an error', 400);

    }

    // Like An Article
    public function Like_Article(){
        $validator = Validator::make(request()->all(), [
            'member_id'         => 'required|exists:members,id',
            'article_id'         => 'required|exists:articles,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $article = Article::where('id',request()->article_id)->first();

        if($article){
            $like = new Like;
            $like->id         =  \Illuminate\Support\Str::uuid(); // Generate UUID
            $like->article_id = request()->article_id;
            $like->member_id  = request()->member_id;
            $like->save();
            return response()->json($like, 201);
        }

        return response()->json('There is an error', 400);
    }

    // Comment An Article
    public function Comment_Article(){
        $validator = Validator::make(request()->all(), [
            'member_id'         => 'required|exists:members,id',
            'article_id'         => 'required|exists:articles,id',
            'comment'            => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $article = Article::where('id',request()->article_id)->first();

        if($article){
            $comment = new Comment;
            $comment->id         =  \Illuminate\Support\Str::uuid(); // Generate UUID
            $comment->article_id = request()->article_id;
            $comment->member_id  = request()->member_id;
            $comment->comment    = request()->comment;
            $comment->save();
            return response()->json($comment, 201);
        }

        return response()->json('There is an error', 400);
    }



    // Publish Article status
    public function publish_article($id){
        $article = Article::where('id',$id)->first();
        if($article){
            $article->status       = "published";
            $article->published_at = now();
            $article->save();
            return response()->json("Published Article $id is successfully!", 200);
        }
        return response()->json('there is an error!', 400);

    }

    // Raise Article view count
    public function increace_view_count($id){
        $article = Article::where('id',$id)->first();
        if($article){
            $article->view_count = $article->view_count+1;
            return response()->json("Increace view count of article $id is successfully!", 200);
        }
        return response()->json('there is an error!', 400);

    }


    // Get all Commonts of an article
    public function Get_Article_Comments($id){
        $article = Article::where('id',$id)->first();
        if($article){
            // return  $article->comments->user;
            $comments = $article->comments()->with('member')->get();
            return $comments->toArray();
            // return $commentsArray;
        }
        return response()->json('there is an error!', 400);

    }

    public function Delete_Comment($id){
        $comment = Comment::where('id',$id)->first();

        if($comment){
            // return  $article->comments->user;
            $comment->delete();
            return response()->json('comment deleted suss!', 200);
        }
        return response()->json('there is an error!', 400);

    }

    // get article likes

    public function Article_Likes($id){
        $article = Article::where('id',$id)->first();
        if($article){
            // return  $article->comments->user;
            $likes = $article->likes;
            return count($likes);
            // return $commentsArray;
        }
        return response()->json('there is an error!', 400);

    }

}
