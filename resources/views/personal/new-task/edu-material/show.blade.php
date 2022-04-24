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
</style>

<div class="form-group">
    <h5><b>{{$lesson->discipline->title}}</b></h5>
    <h6><b>Группа: </b>{{$lesson->group->title}}, {{$lesson->semester}} семестр</h6>
</div>
<div class="mb-2">
    <a href="javascript:void(0)" data-toggle="modal"
       class="show btn btn-default"
       data-target="#createEdu">
        Добавить учебный материал
    </a>
</div>

{{--Модальное окно для добавления учебного материала (видео-лекции) --}}
<div class="modal fade" id="createEdu" tabindex="-1" role="dialog"
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
                <form id="fileUploadForm" method="POST" action="{{ url('personal/tasks/load-edu') }}" enctype="multipart/form-data">
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
                        <input type="submit" value="Загрузить" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://getbootstrap.com/docs/4.5/assets/js/docs.min.js"></script>
<script>
    $(document).ready(function () {
        let bar = $('.bar');
        let percent = $('.percent');
        $('#fileUploadForm').ajaxForm({
            beforeSend: function () {
                let percentVal = '0%';
                bar.width(percentVal)
                percent.html(percentVal);
            },
            uploadProgress: function (event, position, total, percentComplete) {
                let percentVal = percentComplete + '%';
                bar.width(percentVal)
                percent.html(percentVal);
            },
            success: function (response) {
                alert(response)
                $('#createEdu').modal('hide');
            },
            complete: function (xhr) {
                let percentVal = '0%';
                bar.width(percentVal)
                percent.html(percentVal);
            },
            error: function (response) {
                alert(response.responseText);
            }
        });
    });
</script>
