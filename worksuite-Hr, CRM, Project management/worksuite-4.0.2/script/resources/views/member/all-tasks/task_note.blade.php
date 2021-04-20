@forelse($notes as $note)
<div class="row b-b m-b-5 font-12">
    <div class="col-xs-12 m-b-5">
        <span class="font-semi-bold">{{ ucwords($note->user->name) }}</span> <span class="text-muted font-12">{{ ucfirst($note->created_at->diffForHumans()) }}</span>
    </div>
    <div class="col-xs-10">
        {!! ucfirst($note->note)  !!}
    </div>
    
    @if ($note->user_id == $user->id)
        <div class="col-xs-2 text-right">
            <a href="javascript:;" data-comment-id="{{ $note->id }}" class="btn btn-xs  btn-outline btn-default" onclick="deleteNote('{{ $note->id }}');return false;"><i class="fa fa-trash"></i> @lang('app.delete')</a>
        </div>        
    @endif
</div>
@empty
<div class="col-xs-12">
    @lang('messages.noRecordFound')
</div>
@endforelse
