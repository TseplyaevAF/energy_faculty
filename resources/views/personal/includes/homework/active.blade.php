<td>
    @php
    $modelId = explode('/', $work->homework)[0];
    $mediaId = explode('/', $work->homework)[2];
    $filename = explode('/', $work->homework)[3];
    @endphp
    <a href="{{ route('personal.homework.download', [$modelId, $mediaId, $filename]) }}"><i class="fas fa-link mr-1"></i>{{ $filename }}</a>
    <p>Редактировать</p>
</td>
<td>-</td>