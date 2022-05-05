<div class="mb-4">
    <label>Контакты родителей студента:</label>
    <h6>{{$student->user->fullName()}}</h6>
</div>

<form id="parentsContactsForm">
    <input type="hidden" name="student_id" value="{{$student->id}}">

    <h5>Информация о матери</h5>
    <div class="mb-4 ml-4">
        <div class="mb-2">
            <h6>ФИО<span class="gcolor"></span></h6>
            <div><input value="{{ isset($parents->mother->FIO) ? $parents->mother->FIO : '' }}"
                        type="text" class="form-control" name="parents[mother][FIO]"></div>
        </div>
        <div class="mb-2">
            <h6>Телефон<span class="gcolor"></span></h6>
            <div><input value="{{ isset($parents->mother->phone) ? $parents->mother->phone : '' }}"
                        class="form-control phoneNumber" name="parents[mother][phone]" type="tel"></div>
            <span class="text-danger parentsContactsErrors" id="phoneMotherError"></span>
        </div>
        <div class="mb-2">
            <h6>E-mail<span class="gcolor"></span></h6>
            <div><input value="{{ isset($parents->mother->email) ? $parents->mother->email : '' }}"
                        type="text" class="form-control" name="parents[mother][email]"></div>
            <span class="text-danger parentsContactsErrors" id="emailMotherError"></span>
        </div>
    </div>

    <h5>Информация об отце</h5>
    <div class="mb-4 ml-4">
        <div class="mb-2">
            <h6>ФИО<span class="gcolor"></span></h6>
            <div><input value="{{ isset($parents->father->FIO) ? $parents->father->FIO : '' }}"
                        type="text" class="form-control" name="parents[father][FIO]"></div>
        </div>
        <div class="mb-2">
            <h6>Телефон<span class="gcolor"></span></h6>
            <div><input value="{{ isset($parents->father->phone) ? $parents->father->phone : '' }}"
                        class="form-control phoneNumber" name="parents[father][phone]" type="tel"></div>
            <span class="text-danger parentsContactsErrors" id="phoneFatherError"></span>
        </div>
        <div class="mb-2">
            <h6>E-mail<span class="gcolor"></span></h6>
            <div><input value="{{ isset($parents->father->email) ? $parents->father->email : '' }}"
                        type="text" class="form-control" name="parents[father][email]"></div>
            <span class="text-danger parentsContactsErrors" id="emailFatherError"></span>
        </div>
    </div>

    <div class="form-group">
        <button type="button" class="btn btn-primary updateParentsContacts">Сохранить</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
    </div>
</form>
<script src="https://getbootstrap.com/docs/4.5/assets/js/docs.min.js"></script>
<script>
    $('.phoneNumber').inputmask("+8-(999)-999-99-99");
</script>
