//# sourceURL=js/personal/new-task/eduMaterial.js

$("#loadEduMaterialModal").on("hidden.bs.modal", function () {
    let $videoPlayer = $('#videoPlayer');
    $videoPlayer[0].pause();
    // $videoPlayer.get(0).currentTime = 0;
});

$('.createEdu').on('click', function () {
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
            $('#createEduModal').modal('hide');
            getEduMaterials()
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

$('.eduMaterialFile').on('click', function () {
    const filePath = $(this).text().split('/');
    if (filePath[3].includes('mp4')) {
        let videoId = $(this).attr('id').split('_')[1];
        // если видео уже было открыто ранее, то продолжать его просмотр
        if (videoId === eduMaterialId) {
            $('#loadEduMaterialModal').modal("show");
        } else {
            // иначе загрузить новое видео
            eduMaterialId = $(this).attr('id').split('_')[1];
            $.ajax({
                type: 'GET',
                url:  'tasks/load-edu/' + eduMaterialId,
                success: function(response) {
                    $('#loadEduMaterialModal').modal("show");
                    $('#loadEduMaterialModalBody').html(response).show();
                },
                error: function(jqXHR, status, error) {
                    alert('Невозможно загрузить видео');
                }
            });
        }

    } else {
        download(filePath, 'tasks')
    }
});

$('.eduDelete').on('click', function () {
    let id = $(this).attr('id').split('_')[1];
    if (confirm('Вы уверены, что хотите удалить файл?')) {
        $.ajax({
            url: 'tasks/' + id,
            type: 'DELETE',
            dataType: 'JSON',
            data: { '_token': $("input[name='_token']").val() },
            complete: function() {
                getEduMaterials();
            },
        });
    }
});
