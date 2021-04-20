<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">@if(isset($noteDetail->id)) @lang('app.edit') @else @lang('app.addNew') @endif @lang('app.note')</h4>
</div>
<div class="modal-body">
    <div class="form-group">
        <label for="note-text" class="control-label">@lang('app.note'):</label>
        <textarea class="form-control" id="notetext" name="notetext">@if(isset($noteDetail->note_text)) {{$noteDetail->note_text}} @endif</textarea>
        <div class="form-control-focus"> </div>
        <span class="help-block"></span>
        <input type="hidden" id="stickyID" value="@if(isset($noteDetail->id)) {{$noteDetail->id}} @endif">
        <input type="hidden" id="stickyColor" value="@if(isset($noteDetail->colour)) {{$noteDetail->colour}} @endif">
    </div>
    <div class="skin skin-minimal">
        <div class="form-group">
            <label>@lang('modules.sticky.colors')</label>
            <div class="input-group">
                <ul class="icolors">
                    <li id="red" onclick="selectColor('red')" class="red selectColor  @if(isset($noteDetail->colour)  && $noteDetail->colour == 'red' ) active @endif"></li>
                    <li id="green" onclick="selectColor('green')"  class="green selectColor  @if(isset($noteDetail->colour)  && $noteDetail->colour == 'green' ) active @endif"></li>
                    <li id="blue" onclick="selectColor('blue')"  class="blue selectColor  @if(isset($noteDetail->colour)  && $noteDetail->colour == 'blue' ) active @endif"></li>
                    <li id="yellow" onclick="selectColor('yellow')"  class="yellow selectColor  @if(isset($noteDetail->colour)  && $noteDetail->colour == 'yellow' ) active @endif"></li>
                    <li id="purple" onclick="selectColor('purple')"  class="purple selectColor  @if(isset($noteDetail->colour)  && $noteDetail->colour == 'purple' ) active @endif"></li>
                </ul>
            </div>
            <span class="help-block">@lang('messages.defaultColorNote')</span>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">@lang('app.close')</button>
    <button type="button" onclick="addOrEditStickyNote('@if(isset($noteDetail->id)) {{$noteDetail->id}} @endif')" class="btn btn-danger waves-effect waves-light">@if(isset($noteDetail->id)) @lang('app.update') @else @lang('app.save') @endif</button>
</div>