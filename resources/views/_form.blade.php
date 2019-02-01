<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ url('comments') }}">
            @csrf
            <input type="hidden" name="commentable_type" value="\{{ get_class($model) }}" />
            <input type="hidden" name="commentable_id" value="{{ $model->id }}" />
            <div class="form-group">
                <div class="comment_input_line">
                    <div class="comment_logo" style="background-image: url('https://www.gravatar.com/avatar/64e1b8d34f425d19e1ee2ea7236d3028.jpg')"></div>
                    <textarea class="form-control @if($errors->has('message')) is-invalid @endif" name="message" rows="2" placeholder="{{ trans('comments.Join the disqussion') }}"></textarea>
                    
                </div>
                <div class="invalid-feedback">
                   {{ trans('comments.Your message is required.') }}
                </div>
            </div>
            <button type="submit" class="btn btn-sm btn-outline-success text-uppercase">{{ trans('comments.Submit') }}</button>
        </form>
    </div>
</div>
<br />
