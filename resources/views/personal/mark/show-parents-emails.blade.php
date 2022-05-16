<div class="form-group">
    <div class="mb-2">
        <input type="checkbox" id="select-all-emails">
        <span class="text-muted">Выделить все</span>
    </div>

    @foreach($parentsEmails as $studentFIO => $emails)
        @foreach($emails as $email)
        <div class="mb-4">
            <div class="mb-2">
                @if (isset($email->mother))
                    <h6><input type="checkbox" name="email" data-id="{{$studentFIO}}/{{ $email->mother->email }}">
                        <span class="gcolor">{{ $email->mother->email }} <span style="font-size: 14px">({{$studentFIO}}: мать)</span></span>
                    </h6>
                @endif
                @if (isset($email->father))
                    <h6><input type="checkbox" name="email" data-id="{{$studentFIO}}/{{ $email->father->email }}">
                        <span class="gcolor">{{ $email->father->email }} <span style="font-size: 14px">({{$studentFIO}}: отец)</span></span>
                    </h6>
                @endif
            </div>
        </div>
        @endforeach
    @endforeach
    <div class="form-group">
        <button type="button" class="btn btn-primary sendStudentProgress">Отправить</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
    </div>
</div>
<script>
    $('#select-all-emails').click(function(event) {
        if(this.checked) {
            $(':checkbox').each(function() {
                this.checked = true;
            });
        } else {
            $(':checkbox').each(function() {
                this.checked = false;
            });
        }
    });

    $('.sendStudentProgress').on('click', function () {
        let selectedEmails = [];
        let count = 1;
        $('input[name="email"]:checked').each(function() {
            let emailData = $(this).attr('data-id').split('/');
            selectedEmails[emailData[0]] = emailData[1];
            count++;
        });
        let selectedEmailsStr = "";
        selectedEmails.forEach(elem => {
            selectedEmailsStr += elem + '\n';
        });
        if (confirm(`Отправить успеваемость на следующие адреса:\n${selectedEmailsStr}`)) {
            console.log(selectedEmails);
        }
    })
</script>
