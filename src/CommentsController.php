<?php

namespace Laravelista\Comments;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentsController extends Controller
{
    use ValidatesRequests, AuthorizesRequests;

    public function __construct()
    {
        $this->middleware(['web', 'auth']);
    }

    /**
     * Creates a new comment for given model.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer|min:1',
            'message' => 'required|string'
        ]);

        $model = $request->commentable_type::findOrFail($request->commentable_id);

        $comment = new Comment;
        $comment->commenter()->associate(auth()->user());
        $comment->commentable()->associate($model);
        $comment->comment = $request->message;
        $comment->save();

        return redirect()->to(url()->previous() . '#comment-' . $comment->id);
    }

    /**
     * Updates the message of the comment.
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('edit-comment', $comment);

        $this->validate($request, [
            'message' => 'required|string'
        ]);

        $comment->update([
            'comment' => $request->message
        ]);

        return redirect()->to(url()->previous() . '#comment-' . $comment->id);
    }

    /**
     * Deletes a comment.
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete-comment', $comment);

        $comment->delete();

        return redirect()->back();
    }

    /**
     * Creates a reply "comment" to a comment.
     */
    public function reply(Request $request, Comment $comment)
    {
        $this->authorize('reply-to-comment', $comment);

        $this->validate($request, [
            'message' => 'required|string'
        ]);

        $reply = new Comment;
        $reply->commenter()->associate(auth()->user());
        $reply->commentable()->associate($comment->commentable);
        $reply->parent()->associate($comment);
        $reply->comment = $request->message;
        $reply->save();

        return redirect()->to(url()->previous() . '#comment-' . $reply->id);
    }

    public function vote(Request $request){
        $this->validate($request, [
            'id' => 'required|integer|exists:comments,id',
            'type' => 'required|in:up,down',
        ]);

        $comment = Comment::where('id', $request->get('id'))
            ->first();

        $this->authorize('vote', $comment);

        if($request->get('type') == 'up'){
            $rate = 1;
            $comment->rate_up++;
        }else{
            $rate = -1;
            $comment->rate_down++;
        }
        $comment->rate++;
        $comment->save();

        $vote = new CommentVote();
        $vote->user_id = auth()->user()->id;
        $vote->comment_id = $comment->id;
        $vote->rate = $rate;
        $vote->save();

        $comment->save();

        return ['status' => 'success'];
    }
}
