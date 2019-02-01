<?php

namespace Laravelista\Comments;

use Illuminate\Database\Eloquent\Model;

class CommentVote extends Model
{
    protected $fillable = ['rate', 'user_id', 'comment_id'];

}
