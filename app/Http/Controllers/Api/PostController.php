<?php

namespace App\Http\Controllers\Api;

use Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//Model
use App\Models\Post;

class PostController extends Controller
{
    public function getAllPosts() {
        $posts = Post::get()->toJson(JSON_PRETTY_PRINT);
        return response($posts, 200);
    }

    public function createPost(Request $request) {
        $post = new Post;
        $post->post = $request->post;
        $post->image = $request->image;
        $post->save();

        return response()->json([
            "message" => "Post created successfully!"
        ], 201);
    }

    public function getPost($id) {
        if (Post::where('id', $id)->exists()) {
            $post = Post::where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
            return response($post, 200);
        } else {
            return response()->json([
                "message" => "Post not found"
            ], 404);
        }
    }

    public function updatePost(Request $request, $id) {
        if (Post::where('id', $id)->exists()) {
            $post = Post::find($id);
            $post->post = isset($request->post) ? $request->post : $post->post;
            $post->image = isset($request->image) ? $request->image : $post->image;
            $post->save();

            return response()->json([
                "message" => "Post updated successfully!"
            ], 200);
        } else {
            return response()->json([
                "message" => "Post not found"
            ], 404);

        }
    }

    public function deletePost ($id) {
        if(Post::where('id', $id)->exists()) {
            $post = Post::find($id);
            $post->delete();

            return response()->json([
                "message" => "Post deleted successfully"
            ], 202);
        } else {
            return response()->json([
                "message" => "Post not found"
            ], 404);
        }
    }
}
