<form action="{{ action('TermVotesController@voteDown', [$term->slug_unique]) }}" method="POST">
    {!! csrf_field() !!}
    <button type="submit" class="btn btn-default" aria-label="Left Align">
        <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>
    </button>
</form>