@if(Auth::check())
<form action="{{ action('TermVotesController@voteDown', [$term->slug]) }}" method="POST">
    {!! csrf_field() !!}
    <button type="submit" class="btn btn-link vote-negative" disabled="disabled" aria-label="Left Align">
        <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>
    </button>
</form>
@endif