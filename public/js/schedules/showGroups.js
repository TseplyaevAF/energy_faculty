const MAIN_BG_COLOR = "#f4f6f9";
const BLOCK_COLOR = "#dcdfe3";
const TEXT_COLOR = "#000000";

function showDiv(id, chairsIds) {
    let scheduleChair = document.getElementById('chair-' + id);
    let itemChair = document.getElementById(id);
    // coursesBlock.style.display = coursesBlock.style.display == "block" ? "none" : "block";
    if (scheduleChair.style.display == "block") {
        scheduleChair.style.display = "none";
        scheduleChair.style.backgroundColor = MAIN_BG_COLOR;
        itemChair.style.backgroundColor = MAIN_BG_COLOR;
        resetTextProperty(itemChair.children[0])
    } else {
        scheduleChair.style.display = "block";
        scheduleChair.style.backgroundColor = BLOCK_COLOR;
        itemChair.style.backgroundColor = BLOCK_COLOR;
        changeTextProperty(itemChair.children[0], "700", TEXT_COLOR);
    }
    hideElement(id, chairsIds);
}

function hideElement(id, chairsIds) {
    chairsIds = JSON.parse(chairsIds);
    for (i = 0; i < chairsIds.length; i++) {
        if (chairsIds[i] != id) {
            let scheduleChair = document.getElementById('chair-' + chairsIds[i]);
            let itemChair = document.getElementById(chairsIds[i]);
            scheduleChair.style.display = "none";
            itemChair.style.backgroundColor = MAIN_BG_COLOR;
            resetTextProperty(itemChair.children[0])
        }
    }
}

function changeTextProperty(el, fontWeight, color) {
    el.style.fontWeight = fontWeight;
    el.style.color = color;
}

function resetTextProperty(el) {
    el.style.removeProperty("font-weight");
    el.style.removeProperty("color");
}