<td>
    @php
    $modelId = explode('/', $work->homework)[0];
    $mediaId = explode('/', $work->homework)[2];
    $filename = explode('/', $work->homework)[3];
    @endphp
    <a href="{{ route('personal.homework.download', [$modelId, $mediaId, $filename]) }}"><i class="fas fa-link mr-1"></i>{{ $filename }}</a>
</td>
<td>
    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#showFeedback{{ $work->id }}">Посмотреть</button>
    <!-- Pop up form for show feedback about work -->
    <div class="modal fade" id="showFeedback{{ $work->id }}" style="display: none; padding-right: 17px;" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Отзыв о работе</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!! $work->grade !!}
                    </div>
                    <div class="form-group text-right">
                        <i>
                            Преподаватель:
                            {{ $work->task->lesson->teacher->user->surname }}
                            {{ mb_substr($work->task->lesson->teacher->user->name, 0, 1) }}.
                            {{ mb_substr($work->task->lesson->teacher->user->patronymic, 0, 1)}}.
                        </i>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</td>

