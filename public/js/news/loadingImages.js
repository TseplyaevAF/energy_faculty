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
  var imageSize = images.size;
  for (var i = 0; i < files.length; i++) {
    if (imageSize == 0) {
      uniqueName = getUniqueName(files[i].name)
      images.set(uniqueName, files[i]);
      imageBlock[i].children[0].id = "prevImage" + uniqueName;
      imageBlock[i].children[1].textContent = files[i].name;
      imageBlock[i].children[2].children[0].dataset.id = uniqueName;
      imageBlock[i].children[2].children[0].addEventListener('click', deleteFromImgList, {passive: true});
      imageSize++;
      var imagesList = document.getElementById("load-img-list");
      un_hide_block(imagesList);
    } else {
      uniqueName = getUniqueName(files[i].name);
      images.set(uniqueName, files[i]);
      var new_image = document.getElementsByClassName('load-img-item')[imageBlock.length - 1].cloneNode(true);
      document.getElementsByClassName("load-img-list")[0].appendChild(new_image);
      imageBlock[imageSize].children[0].id = "prevImage" + uniqueName;
      imageBlock[imageSize].children[1].textContent = files[i].name;
      imageBlock[imageSize].children[2].children[0].dataset.id = uniqueName;
      imageBlock[imageSize].children[2].children[0].addEventListener('click', deleteFromImgList, {passive: true});
      imageSize++;
    }
    readURL(images.get(uniqueName), uniqueName);
  }
}

function toImgList(imageUrl) {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', imageUrl, true);
  xhr.responseType = 'blob';
  xhr.onload = function (e) {
    if (this.status == 200) {
      var myBlob = this.response;
      var file = new File([myBlob], "image", { type: 'image/jpeg' });
      files = [];
      files.push(file);
      setImages(files);
    }
  };
  xhr.send();
}


$(document).ready(function () {
  var imageList = document.getElementById("load-img-list");
  hide_block(imageList);
  var imagesLinks = $('div.hidden').data('images');
  if (imagesLinks !== undefined && imagesLinks !== "") {
    imagesLinks = imagesLinks.split('|');
    for (let i = 0; i < imagesLinks.length; i++) {
      toImgList(imagesLinks[i]);
    }
  }
});

$('form input[type=file]').on('change', function () {
    if (this.name === 'images[]') {
        setImages(this.files);
    }
});

$('#submitNews').on('click', function () {
  array = getArray(images);
  var files = [];
  for (let i = 0; i < array.length; i++) {
    files[i] = array[i].value;
  }
  inputImages.files = new FileListItems(files);
});
