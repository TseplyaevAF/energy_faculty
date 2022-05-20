<div class="" id="importStudentId">
    <div class="mb-2">
        <h6>
            <a type="button" class="studentsTemplateDownload">
                Скачать таблицу со студентами
            </a>
        </h6>
    </div>
    <div class="mb-2">
        <label>Название учебной группы</label>
        <input type="text" class="form-control" name="title" id="group_title" placeholder="ИВТ-18">
        <span class="text-danger importUsersErrors" id="studentGroupError"></span>
    </div>
    <div class="form-group">
        <label>Кафедра</label>
        <select id="chair_id" class="form-control">
            @foreach($chairs as $chair)
                <option value="{{ $chair->id }}">{{ $chair->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-2">
        <label>Файл с таблицей студентов</label>
        <div class="custom-file">
            <input type="file" id="excel_file" class="custom-file-input" accept=".xlsx">
            <label class="custom-file-label" for="exampleInputFile">Выберите файл</label>
        </div>
        <span class="text-danger importUsersErrors" id="fileError"></span>
    </div>
</div>
