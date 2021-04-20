@foreach($tasks as $task)
    <option value="{{ $task->id }}">{{ $task->heading }}</option>
@endforeach