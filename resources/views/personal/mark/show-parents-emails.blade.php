<div class="form-group">
    <div class="mb-2">
        <input type="checkbox" id="select-all-emails">
        <span class="text-muted">Выделить все</span>
    </div>

    @foreach($parentsEmails as $studentFIO => $emails)
        @foreach($emails as $email)
            <div class="mb-2">
                @if (isset($email->mother) && isset($email->mother->email))
                    <h6><input type="checkbox" class="cb-email" data-id="{{$studentFIO}}/{{ $email->mother->email }}/mother">
                        <span class="gcolor">{{ $email->mother->email }} <span style="font-size: 14px">({{$studentFIO}}: мать)</span></span>
                    </h6>
                @endif
                @if (isset($email->father) && isset($email->father->email))
                    <h6><input type="checkbox" class="cb-email" data-id="{{$studentFIO}}/{{ $email->father->email }}/father">
                        <span class="gcolor">{{ $email->father->email }} <span style="font-size: 14px">({{$studentFIO}}: отец)</span></span>
                    </h6>
                @endif
            </div>
        @endforeach
    @endforeach
    <div class="form-group">
        <button type="button" class="btn btn-primary sendStudentProgress">Отправить</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
    </div>
</div>
<script>
    $('#select-all-emails').click(function() {
        $('.cb-email').prop('checked',this.checked);
    });

    $('.cb-email').change(function () {
        if ($('.cb-email:checked').length === $('.cb-email').length){
            $('#select-all-emails').prop('checked',true);
        }
        else {
            $('#select-all-emails').prop('checked',false);
        }
    });

    $('.sendStudentProgress').on('click', function () {
        let selectedEmails = {};
        let month = $('#student-progress-month').find(":selected").text();
        let semester = $('#student-progress-semester').find(":selected").text();
        $('.cb-email:checked').each(function() {
            let emailData = $(this).attr('data-id').split('/');
            selectedEmails[emailData[0] + '_' + emailData[2]] = emailData[1];
        });
        let selectedEmailsStr = "";
        for (const [key, value] of Object.entries(selectedEmails)) {
            selectedEmailsStr += value + '\n';
        }
        if (confirm(`Отправить успеваемость на следующие адреса:\n${selectedEmailsStr}`)) {
            $.ajax({
                type: 'POST',
                url:  'marks/send-student-progress',
                data: {
                    '_token': $("input[name='_token']").val(),
                    'student_progress': studentProgress,
                    'selected_emails': selectedEmails,
                    'month': month,
                    'semester': semester,
                },
                success: function(response) {
                    alert(response)
                    $('#sendStudentProgressToParentsModal').modal("hide");
                }
            });
        }
    })
</script>
