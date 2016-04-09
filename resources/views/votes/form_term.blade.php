{{-- If user is logged in, he can vote unless already voted --}}
@if(Auth::check())
    {{-- If the user didnt vote, show the form. --}}
    @if($term->votes->isEmpty())
    <form action="{{ action('TermVotesController@voteUp', [$term->slug]) }}" method="POST">
        {!! csrf_field() !!}
        {!! getLocaleInputField() !!}
        <button type="submit" class="btn btn-link vote-positive" aria-label="Left Align">
            <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
        </button>
    </form>
    
    <span class="votes-lg">{{ $term->votes_sum }}</span>
    
    <form action="{{ action('TermVotesController@voteDown', [$term->slug]) }}" method="POST">
        {!! csrf_field() !!}
        {!! getLocaleInputField() !!}
        <button type="submit" class="btn btn-link vote-negative" aria-label="Left Align">
            <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>
        </button>
    </form>
    @else
        {{-- User did vote, so only show the appropriate glypicon for positive/negative --}}
        @if($term->votes->first()->is_positive)
            <div>
                <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
            </div>
            <span class="votes-lg">{{ $term->votes_sum }}</span>
        @else
            <span class="votes-lg">{{ $term->votes_sum }}</span>
            <div>
                <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>
            </div>
        @endif
    @endif
@else
{{-- Guest user, only show votes --}}
    <span class="votes-lg">{{ $term->votes_sum }}</span>
@endif