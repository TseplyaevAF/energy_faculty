<div class="form-group">
    <h5><b>{{$lesson->discipline->title}}</b></h5>
    <h6>{{$lesson->semester}} семестр</h6>
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
        </div>
    @endforeach
</div>
@endif
<script src="https://getbootstrap.com/docs/4.5/assets/js/docs.min.js"></script>
