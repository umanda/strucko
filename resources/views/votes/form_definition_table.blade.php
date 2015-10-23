
{{-- If user is logged in and term is approved, he can vote unless already voted --}}
{{-- If the original term is not approved, user can not vote for definition --}}
@if(Auth::check() && ! ($term->status_id < 1000))
    {{-- If the user didnt vote, show the form. --}}
    @if($definition->votes->isEmpty())
        <td class="text-center vertical-center-cell">
            <form action="{{ action('DefinitionVotesController@voteUp', [$definition->id]) }}" method="POST">
                {!! csrf_field() !!}
                <button type="submit" class="btn btn-link vote-positive" aria-label="Left Align">
                    <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
                </button>
            </form>
        </td>
        <td class="text-center vertical-center-cell">
            <span>{{ $definition->votes_sum }}</span>
        </td>
        <td class="text-center vertical-center-cell">
            <form action="{{ action('DefinitionVotesController@voteDown', [$definition->id]) }}" method="POST">
                {!! csrf_field() !!}
                <button type="submit" class="btn btn-link vote-negative" aria-label="Left Align">
                    <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>
                </button>
            </form>
        </td>
    @else
        {{-- User did vote, so only show the appropriate glypicon for positive/negative --}}
        @if($definition->votes->first()->is_positive)
            <td class="text-center vertical-center-cell"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span></td>
            <td class="text-center vertical-center-cell"><span>{{ $definition->votes_sum }}</span></td>
            <td class="text-center vertical-center-cell"></td>
        @else
            <td class="text-center vertical-center-cell"></td>
            <td class="text-center vertical-center-cell"><span>{{ $definition->votes_sum }}</span></td>
            <td class="text-center vertical-center-cell"><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span></td>
        @endif
    @endif
@else
{{-- Guest user, only show votes --}}
    <td class="text-center vertical-center-cell"></td>
    <td class="text-center vertical-center-cell"><span>{{ $definition->votes_sum }}</span></td>
    <td class="text-center vertical-center-cell"></td>
@endif