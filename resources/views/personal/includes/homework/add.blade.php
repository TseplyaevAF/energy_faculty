<td class="work-status__active">{{ $statusVariants[0] }}</td>
<td class="project-actions text-left">
    <a class="btn btn-info btn-sm" href="{{ route('personal.homework.create', $task->id) }}">
        <i class="far fa-plus-square"></i>
        Добавить решение
    </a>
</td>
<td></td>