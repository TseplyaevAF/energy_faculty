// ассоциативный массив для хранения загружаемых картинок
const images = new Map();
// для отправки массива с картинками на сервер
const inputImages = document.querySelector('input[name="images[]"]');


function FileListItems(files) {
  var b = new ClipboardEvent("").clipboardData || new DataTransfer()
  for (var i = 0, len = files.length; i < len; i++) b.items.add(files[i])
  return b.files
}

// преобразование ассоциативного массива к обычному
function getArray(images) {
  return array = Array.from(images, ([name, value]) => ({
    name,
    value
  }));
}

function getUniqueName(name) {
  return Math.random().toString(36).substr(2, 10) + '|' + name;
}

function deleteFromImgList() {
  var imageBlock = document.getElementsByClassName('load-img-item');
  var id = $(this).attr('data-id');
  array = getArray(images);
  for (let i = 0; i < images.size; i++) {
    if (id == array[i].name) {
      deletingImageIndex = i;
      break;
    }
  }
  images.delete(id);
  if (!images.size == 0) {
    imageBlock[deletingImageIndex].remove();
  } else {
    var imageList = document.getElementById("load-img-list");
    hide_block(imageList);
  }
}

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

function readURL(input, imageName) {
  var image = document.getElementById('prevImage' + imageName);
  var reader = new FileReader();

  reader.onloadend = function (e) {
    image.src = e.target.result;
  }

  reader.readAsDataURL(input);
}

function setImages(files) {
  var imageBlock = document.getElementsByClassName('load-img-item');
  var inputLabel = document.getElementsByClassName('custom-file-label');
  var imageSize = images.size;
  for (var i = 0; i < files.length; i++) {
    if (imageSize == 0) {
      uniqueName = getUniqueName(files[i].name)
      images.set(uniqueName, files[i]);
      imageBlock[i].children[0].id = "prevImage" + uniqueName;
      imageBlock[i].children[2].dataset.id = uniqueName;
      imageBlock[i].children[1].textContent = files[i].name;
      imageBlock[i].children[2].addEventListener('click', deleteFromImgList);
      imageSize++;
      var imagesList = document.getElementById("load-img-list");
      un_hide_block(imagesList);
      // console.log(imageBlock[i].children[i].outerHTML="<img src=\"" + images[i]  + "\" alt=\"image\" class=\"w-25\">\"");
    } else {
      uniqueName = getUniqueName(files[i].name);
      images.set(uniqueName, files[i]);
      var new_image = document.getElementsByClassName('load-img-item')[imageBlock.length - 1].cloneNode(true);
      document.getElementsByClassName("load-img-list")[0].appendChild(new_image);
      imageBlock[imageSize].children[0].id = "prevImage" + uniqueName;
      imageBlock[imageSize].children[2].dataset.id = uniqueName;
      imageBlock[imageSize].children[1].textContent = files[i].name;
      imageBlock[imageSize].children[2].addEventListener('click', deleteFromImgList);
      imageSize++;
    }
    inputLabel[0].innerHTML = "1";
    readURL(images.get(uniqueName), uniqueName);
  }
}


$(document).ready(function () {
  var imageList = document.getElementById("load-img-list");
  hide_block(imageList);

  // var oldImages = $('div.hidden').data('images');

  // if (oldImages !== undefined) {
  //   getOldImages(oldImages)
  // }
});

// function getOldImages() {
//   oldImages = oldImages.split('|');
//   files = [];

//   for (let i = 0; i < oldImages.length; i++) {
//     oldImages[i] = 'storage/' + oldImages[i];
//     files.push(new File([""], oldImages[i]));
//   }
//   setImages(files);
// }

$('form input[type=file]').on('change', function () {
  setImages(this.files);
});

$('#submitGroupNews').on('click', function () {
  array = getArray(images);
  var files = [];
  for (let i = 0; i < array.length; i++) {
    files[i] = array[i].value;
  }
  inputImages.files = new FileListItems(files);
});

function animal(id) {
  console.log(id);
}