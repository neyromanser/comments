
<div class="comments-block">
    <div class="comments_header">
        <h2>{{ trans('comments.Comments') }}</h2>
        <div class="comments_filters">
            <span class="{{ \App\Helpers\Sorter::getCommentSortId() == 1 ? 'active' : '' }}">
                <a href="?comment_sort=1">{{ trans('comments.Popular') }}</a>
            </span>
            <span class="{{ \App\Helpers\Sorter::getCommentSortId() == 2 ? 'active' : '' }}">
                <a href="?comment_sort=2">{{ trans('comments.Recent') }}</a>
            </span>
        </div>
    </div>
    @auth
        @include('comments::_form')
        @if($model->comments->count() < 1)
            <p class="lead">{{ trans('comments.There are no comments yet.') }}</p>
        @endif

        <ul class="list-unstyled">
            @foreach($model->comments->where('parent', null) as $comment)
                @include('comments::_comment')
            @endforeach
        </ul>

    @else
        @if($model->comments->count() < 1)
            <p class="lead">{{ trans('comments.There are no comments yet.') }}</p>
        @endif

        <ul class="list-unstyled">
            @foreach($model->comments->where('parent', null) as $comment)
                @include('comments::_comment')
            @endforeach
        </ul>

        <div class="card">
            <p class="card-text">{{ trans('comments.You must log in to post a comment.') }}</p>
            <a data-toggle="modal" data-target="#login_modal" class="login login_comments">{{ trans('main.Sign in') }}</a>
        </div>
    @endauth
</div>

@push('js-scripts')
    <script>
        jQuery(document).ready(function() {
            if($('.vote-button').length){
                $('.vote-button').bind('click', function(){
                    let btn = $(this)
                    let data = {
                        id: btn.data('id'),
                        type: btn.data('type')
                    }
                    $.ajax({
                        type: 'POST',
                        data: data,
                        url: '{{ route('vote_comment') }}',
                        success: function (response) {
                            if(response.status == 'success'){
                                let cl = data.type == 'up'
                                    ? 'rate-like'
                                    : 'rate-dislike'
                                let counter = $(btn.parent().find('.'+cl))
                                counter.html(parseInt(counter.html()) + 1)
                                $(btn.parent().find('.vote-button')).hide()
                            }
                        }
                    });
                })
            }
        })
    </script>
@endpush
