<style>
    .progress {
        position:relative;
        width:100%;
        height: 20px;
    }
    .bar {
        background-color: rgba(14, 161, 85, 0.83);
        width:0%;
        height:20px;
    }
    .percent {
        display:inline-block;
        margin: auto;
        left:50%;
        color: #040608;
    }
    .deleteEdu {
        display: block;
        width: 13px;
        height: 13px;
        --weight: 1px;
        --aa: 1px; /* anti-aliasing */
        --color: rgba(95, 90, 90, 0.97);
        border-radius: 1px;
        background:
            linear-gradient(45deg, transparent calc(50% - var(--weight) - var(--aa)), var(--color) calc(50% - var(--weight)), var(--color) calc(50% + var(--weight)), transparent calc(50% + var(--weight) + var(--aa))),
            linear-gradient(-45deg, transparent calc(50% - var(--weight) - var(--aa)), var(--color) calc(50% - var(--weight)), var(--color) calc(50% + var(--weight)), transparent calc(50% + var(--weight) + var(--aa)));
    }
    .deleteEdu:hover {
        cursor: pointer;
    }
</style>

<div class="form-group">
    <h5><b>{{$lesson->discipline->title}}</b></h5>
    <h6><b>Группа: </b>{{$lesson->group->title}}, {{$lesson->semester}} семестр</h6>
</div>
<div class="mb-2">
    <a href="javascript:void(0)" data-toggle="modal"
       class="show btn btn-primary"
       data-target="#createEduModal">
        Добавить учебный материал
    </a>
</div>

{{--Модальное окно для добавления учебного материала --}}
<div class="modal fade" id="createEduModal" tabindex="-1"
     aria-labelledby="createEduModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="card-title">Добавление учебного материала</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="fileUploadForm" method="POST" action="{{ url('personal/tasks/store-edu') }}" enctype="multipart/form-data">
                    @csrf
                    <input value="{{ $lesson->id }}" type="hidden" name="lesson_id">
                    <div class="form-group mb-3">
                        <div class="custom-file mb-2">
                            <input name="file" type="file" class="custom-file-input" accept=".pdf,.mp4">
                            <label class="custom-file-label" for="exampleInputFile">Выберите файл</label>
                        </div>
                        <div class="progress">
                            <div class="bar"></div >
                            <div class="percent">0%</div >
                        </div>
                    </div>
                    <div class="d-grid mb-3">
                        <input type="submit" class="btn btn-primary createEdu" value="Загрузить"/>
                        <button type="button" class="btn btn-default stopVideo">Отмена</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Модальное окно для просмотра видео-материала --}}
<div class="modal fade bd-example-modal-xl" id="loadEduMaterialModal" tabindex="-1" role="dialog"
     aria-labelledby="loadEduMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-xl">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="loadEduMaterialModalBody">
            </div>
        </div>
    </div>
</div>

@if (isset($files['video']))
<div class="form-group mb-2">
    <h6><b>Видео:</b></h6>
    @foreach($files['video'] as $file)
        <div class="row mb-1" style="margin: 0">
            <a type="button" class="eduMaterialFile mr-2" id="eduDownload_{{$file->id}}">{{ $file->task }}</a>
            <div id="eduDelete_{{$file->id}}" class="eduDelete" style="position: relative; top: 6px">
                <div class="deleteEdu"></div>
            </div>
        </div>
    @endforeach
</div>
@endif

@if (isset($files['docs']))
<div class="form-group mb-2">
    <h6><b>Документы:</b></h6>
    @foreach($files['docs'] as $file)
        <div class="row mb-1" style="margin: 0">
            <a type="button" class="eduMaterialFile mr-2" id="eduDownload_{{$file->id}}">{{ $file->task }}</a>
            <div id="eduDelete_{{$file->id}}" class="eduDelete" style="position: relative; top: 6px">
                <div class="deleteEdu"></div>
            </div>
        </div>
    @endforeach
</div>
@endif
<script src="https://getbootstrap.com/docs/4.5/assets/js/docs.min.js"></script>
