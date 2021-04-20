@foreach($stickyNotes as $note)
    <div id="stickyBox_{{$note->id}}" class="col-md-12 sticky-note">
        <div class="well
             @if($note->colour == 'red')
                bg-danger
             @endif
        @if($note->colour == 'green')
                bg-success
             @endif
        @if($note->colour == 'yellow')
                bg-warning
             @endif
        @if($note->colour == 'blue')
                bg-info
             @endif
        @if($note->colour == 'purple')
                bg-purple
             @endif
                b-none">
            <p>{!! nl2br($note->note_text)  !!}</p>
            <hr>
            <div class="row font-12">
                <div class="col-xs-9">
                    @lang("modules.sticky.lastUpdated"): {{ $note->updated_at->diffForHumans() }}
                </div>
                <div class="col-xs-3">
                    <a href="javascript:;"  onclick="showEditNoteModal({{$note->id}})"><i class="ti-pencil-alt text-white"></i></a>
                    <a href="javascript:;" class="m-l-5" onclick="deleteSticky({{$note->id}})" ><i class="ti-close text-white"></i></a>
                </div>
            </div>
        </div>
    </div>
@endforeach