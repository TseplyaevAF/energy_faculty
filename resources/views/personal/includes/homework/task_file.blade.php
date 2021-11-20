@php
    $modelId = explode('/', $task->task)[0];
    $mediaId = explode('/', $task->task)[2];
    $filename = explode('/', $task->task)[3];
@endphp
<a href="{{ route('personal.task.download', [$modelId, $mediaId, $filename]) }}">{{ $task->id }}. {{ $filename }}</a>