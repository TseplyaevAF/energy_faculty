var select = document.querySelector('select');
var studentData = document.getElementById("studentsDataId");
var teacherData = document.getElementById("teachersDataId");

function hide_block(block) {
    if (!block.classList.contains('none')) {
        block.classList.add('none');
    }
}

function un_hide_block(block) {
    if (block.classList.contains('none')) {
        block.classList.remove('none');
    }
}

function checkSelect() {
    var indexSelected = select.selectedIndex,
        option = select.querySelectorAll('option')[indexSelected];

    var selectedId = option.value;

    switch (selectedId) {
        case '0':
            hide_block(studentData);
            hide_block(teacherData);
            break;
        case '1':
            un_hide_block(studentData);
            hide_block(teacherData);
            break;
        case '2':
            un_hide_block(teacherData);
            hide_block(studentData);
            break;
    }
}

select.onchange = function () {
    checkSelect();
};

function findOption(select) {
    const option = select.querySelector(`option[value="${select.value}"]`)
    // Действия над option
}

$(document).ready(function () {
    var studentData = document.getElementById("studentsDataId");
    hide_block(studentData);
    hide_block(teacherData);
    checkSelect();
});