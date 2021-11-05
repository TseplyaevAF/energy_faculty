function showDiv(id, chairsIds) {
    var coursesBlock = document.getElementById('chair-' + id);
    coursesBlock.style.display = coursesBlock.style.display == "block" ? "none" : "block";
    hideElement(id, chairsIds);
}

function hideElement(id, chairsIds) {
    chairsIds = JSON.parse(chairsIds);
    for (i = 0; i < chairsIds.length; i++) {
        if (chairsIds[i] != id) {
            var el = document.getElementById('chair-' + chairsIds[i]);
            el.style.display = "none";
        }
    }
}