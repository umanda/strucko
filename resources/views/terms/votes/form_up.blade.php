<form action="{{ action('TermVotesController@voteUp', [$term->slug_unique]) }}" method="POST">
    {!! csrf_field() !!}
    <button type="submit" class="btn btn-default" aria-label="Left Align">
        <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
    </button>
</form>