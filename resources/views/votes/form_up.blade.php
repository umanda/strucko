<form action="{{ action('TermVotesController@voteUp', [$term->slug]) }}" method="POST">
    {!! csrf_field() !!}
    <button type="submit" class="btn btn-link vote-positive" aria-label="Left Align">
        <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
    </button>
</form>