<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\WebModels\WebRole;
use App\Models\Comments;
use App\Models\Likes;
use App\Models\CommentReply;


class AdminPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->web_role->rank >= WebRole::SUPER_ADMIN_RANK)  {
            // show all post
            $posts = DB::table('posts as pst')->where('deleted_at','=',NULL)->latest('pst.created_at')
                ->join('admins as adm', 'pst.admin_id', '=', 'adm.id')
                ->select('pst.id as post_id', 'pst.title', 'pst.content',
                        'pst.status', 'adm.id as admin_id', 'adm.firstname', 'adm.lastname')->get();
        } else {
            // show only user posts
            $posts = DB::table('posts as pst')->where('deleted_at','=',NULL)->where('admin_id', auth()->user()->id)
                ->join('admins as adm', 'pst.admin_id', '=', 'adm.id')
                ->select('pst.id as post_id', 'pst.title', 'pst.content',
                        'pst.status', 'adm.id as admin_id', 'adm.firstname', 'adm.lastname')->get();
        }

        return view('posts.index', compact('posts'));
    }

    public function newPost()
    {
        return view('posts.new');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = Post::create([
          'admin_id' => auth()->user()->id,
          'title' => $request->title,
          'content' => $request->content,
          'status' => Post::HIDDEN,
        ]);
        return redirect()->back()->with('message', 'Post Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // get the post
        $post = Post::where('id', $id)->first();
        if(!$post) {
            return redirect()->back()->with('error', 'Post not found');
        }
        $postLikes = Likes::where('id', $post->id)->count();

        // get Comments
        //$comments = Comments::where('post_id',$id)->get();
        $comments = DB::table('comments as c')->where('post_id','=',$id)->latest('c.created_at')
            ->join('users as u', 'c.user_id', '=', 'u.id')
            ->select('c.id as comment_id', 'c.post_id', 'c.user_id', 'c.content', 'c.created_at',
                    'u.firstname', 'u.lastname', 'u.username', 'u.id as user_id')->get();


        return view('posts.show', compact('post', 'comments', 'postLikes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::where('id', $id)->first();
        if(!$post) {
            return redirect()->back()->with('error', 'Post not found');
        }

        $post->update([
            'title' => $request->title,
            'content' => $request->content
        ]);
        return redirect()->back()->with('message', 'Post Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::where('id', $id)->first();
        if(!$post) {
            return redirect()->back()->with('error', 'Post not found');
        }
        $post->delete();
        return redirect()->back()->with('message', 'Post Deleted Successfully');
    }

    public function publish($id)
    {
        $post = Post::where('id', $id)->first();
        if(!$post) {
            return redirect()->back()->with('error', 'Post not found');
        }
        $post->update([
            'status' => Post::PUBLISHED,
        ]);
        return redirect()->back()->with('message', 'Post Published Successfully');
    }

    public function hide($id)
    {
        $post = Post::where('id', $id)->first();
        if(!$post) {
            return redirect()->back()->with('error', 'Post not found');
        }
        $post->update([
            'status' => Post::HIDDEN,
        ]);
        return redirect()->back()->with('message', 'Post Hidden Successfully');
    }

    public function replyComment(Request $request, $commentId)
    {
        $comment = $request['commentReply'.$commentId];
        if(!$comment) {
            return redirect()->back()->with('error', 'Comment Reply cannot be blank');
        }
        $reply = CommentReply::create([
            'content' => $comment,
            'comment_id' => $commentId,
            'user_id' => 0
        ]);
        return redirect()->back()->with('message', 'Reply added successfully');

    }

    public function deleteReply($replyId)
    {
        $reply = CommentReply::where('id',$replyId)->first();
        if(!$reply) {
            return redirect()->back()->with('error', 'Reply not found');
        }
        $reply->delete();
        return redirect()->back()->with('message', 'Reply deleted successfully');
    }

    public function deleteComment($commentId)
    {
        $comment = Comments::where('id',$commentId)->first();
        if(!$comment) {
            return redirect()->back()->with('error', 'Comment not found');
        }
        $comment->delete();
        return redirect()->back()->with('message', 'Comment deleted successfully');
    }
}
