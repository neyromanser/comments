@inject('markdown', 'Parsedown')

@if(isset($reply) && $reply === true)
  <div id="comment-{{ $comment->id }}" class="media comment_item comment_item_reply">
@else
  <li id="comment-{{ $comment->id }}" class="media comment_item">
@endif
    <div class="comment_header">
        <div class="comment_header_left">
            <div class="comment_logo"  onclick="$('#reply-modal-{{ $comment->id }} textarea').focus();" style="background-image: url('https://www.gravatar.com/avatar/{{ md5($comment->commenter->email) }}.jpg')"></div>
            <div class="comment_author">
                <h5 class="mt-0">
                    {{ $comment->commenter->name }}
                    {!!isset($parent)?'<img src="/img/reply.png" alt=""> <span>'.$parent->commenter->name.'</span>':''!!}
                </h5>
                <div class="comment_date"><small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small></div>
            </div>
        </div>
        <div class="comment_rate">
            @can('vote', $comment)
                <div class="arrow_rate vote-button" data-id="{{ $comment->id }}" data-type="down">
                    <img src="/img/down-arrow.svg" height="8" alt="">
                </div>
                <div class="rate_number rate-dislike" style="color: red;">{{ $comment->rate_down }}</div>
                <div class="rate_number rate-like">{{ $comment->rate_up }}</div>
                <div class="arrow_rate vote-button" data-id="{{ $comment->id }}" data-type="up">
                    <img src="/img/up-arrow.svg" height="8" alt="">
                </div>
            @else
                <div class="rate_number" style="color: red;">{{ $comment->rate_down }}</div>
                <div class="rate_number">{{ $comment->rate_up }}</div>
            @endcan
        </div>
    </div>
    <div class="media-body">

        <div style="white-space: pre-wrap;">{!! $markdown->line($comment->comment) !!}</div>

        <p class="reply_p">
            @can('reply-to-comment', $comment)
                <button onclick="setTimeout(function() {$('#reply-modal-{{ $comment->id }} textarea').focus();}, 1000);"  data-toggle="modal" data-target="#reply-modal-{{ $comment->id }}">{{ trans('comments.Reply') }}</button>
            @endcan

            {{--
            @can('edit-comment', $comment)
                <button data-toggle="modal" data-target="#comment-modal-{{ $comment->id }}" class="btn btn-sm btn-link text-uppercase">Edit</button>
            @endcan
            @can('delete-comment', $comment)
                <a href="{{ url('comments/' . $comment->id) }}" onclick="event.preventDefault();document.getElementById('comment-delete-form-{{ $comment->id }}').submit();" class="btn btn-sm btn-link text-danger text-uppercase">Delete</a>
                <form id="comment-delete-form-{{ $comment->id }}" action="{{ url('comments/' . $comment->id) }}" method="POST" style="display: none;">
                    @method('DELETE')
                    @csrf
                </form>
            @endcan
            --}}
        </p>

        {{--
        @can('edit-comment', $comment)
            <div class="modal fade" id="comment-modal-{{ $comment->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="POST" action="{{ url('comments/' . $comment->id) }}">
                            @method('PUT')
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Comment</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="message">Update your message here:</label>
                                    <textarea required class="form-control" name="message" rows="3">{{ $comment->comment }}</textarea>
                                    <small class="form-text text-muted"><a target="_blank" href="https://help.github.com/articles/basic-writing-and-formatting-syntax">Markdown</a> cheatsheet.</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-outline-secondary text-uppercase" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-sm btn-outline-success text-uppercase">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
        --}}

        @can('reply-to-comment', $comment)
            <div class="modal fade comment_modal" id="reply-modal-{{ $comment->id }}" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="POST" action="{{ url('comments/' . $comment->id) }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" onclick="$('#reply-modal-{{ $comment->id }} textarea').focus();">{{ trans('comments.Reply to Comment') }}</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <textarea required class="form-control" name="message" rows="2" placeholder="{{ trans('comments.Enter your message here:') }}"></textarea>
                                </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-outline-secondary text-uppercase" data-dismiss="modal">{{ trans('comments.Cancel') }}</button>
                                <button type="submit" class="btn btn-sm btn-outline-success text-uppercase">{{ trans('comments.Reply') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan

        

        @foreach($comment->children as $child)
            @include('comments::_comment', [
                'comment' => $child,
                'reply' => true,
                'parent' => $comment
            ])
        @endforeach
    </div>
@if(isset($reply) && $reply === true)
  </div>
@else
  </li>
@endif
