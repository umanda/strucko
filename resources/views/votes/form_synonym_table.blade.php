{{-- If user is logged in, he can vote unless already voted --}}
@if(Auth::check())
    {{-- If the user didnt vote, show the form. --}}
    @if($synonym->votes->isEmpty())
        <td class="text-center vertical-center-cell">
            <form action="{{ action('ConceptsController@voteForSynonym', [$term->slug]) }}" method="POST">
                {!! csrf_field() !!}
                <input type="hidden" name="synonym_slug" value="{{ $synonym->synonym->slug }}">
                <input type="hidden" name="is_positive" value="1">
                <button type="submit" class="btn btn-link vote-positive" aria-label="Left Align">
                    <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
                </button>
            </form>
        </td>
        <td class="text-center vertical-center-cell">
            <span>{{ $synonym->votes_sum }}</span>
        </td>
        <td class="text-center vertical-center-cell">
            <form action="{{ action('ConceptsController@voteForSynonym', [$term->slug]) }}" method="POST">
                {!! csrf_field() !!}
                <input type="hidden" name="synonym_slug" value="{{ $synonym->synonym->slug }}">
                <button type="submit" class="btn btn-link vote-negative" aria-label="Left Align">
                    <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>
                </button>
            </form>
        </td>
    @else
        {{-- User did vote, only show the appropriate glypicon for positive/negative --}}
        @if($synonym->votes->first()->is_positive)
            <td class="text-center vertical-center-cell"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span></td>
            <td class="text-center vertical-center-cell"><span>{{ $synonym->votes_sum }}</span></td>
            <td class="text-center vertical-center-cell"></td>
        @else
            <td class="text-center vertical-center-cell"></td>
            <td class="text-center vertical-center-cell"><span>{{ $synonym->votes_sum }}</span></td>
            <td class="text-center vertical-center-cell"><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span></td>
        @endif
    @endif
@else
{{-- Guest user, only show votes --}}
    <td class="text-center vertical-center-cell"></td>
    <td class="text-center vertical-center-cell"><span>{{ $synonym->votes_sum }}</span></td>
    <td class="text-center vertical-center-cell"></td>
@endif