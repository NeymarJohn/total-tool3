@if(isset($employees))
    @foreach($employees as $member)
    <option value="{{ $member->id }}">{{ ucwords($member->name) }}</option>
    @endforeach
@else
    @foreach($members as $member)
    <option value="{{ $member->user_id }}">{{ ucwords($member->user->name) }}</option>
    @endforeach
@endif

