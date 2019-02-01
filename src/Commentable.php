<?php

namespace Laravelista\Comments;

use App\Helpers\Sorter;

/**
 * Add this trait to any model that you want to be able to
 * comment upon or get comments for.
 */
trait Commentable
{
    /**
     * Returns all comments for this model.
     */
    public function comments()
    {
        $sort = Sorter::getCommentSortId();
        $orderField = $sort == 1 ? 'rate' : 'id';
        $orderDir = $sort == 1 ? 'desc' : 'asc';
        return $this->morphMany(Comment::class, 'commentable')->orderBy($orderField, $orderDir);
    }
}
