



function ampliarImagen(element) {
    var modal = document.getElementById("imageModal");
    var modalImg = document.getElementById("imgAmpliada");
    var captionText = document.getElementById("caption");

    modal.style.display = "block";
    modalImg.src = element.src;
    captionText.innerHTML = element.alt;
}

// Cerrar el modal al hacer clic en la (X)
document.querySelector(".close").onclick = function () {
    document.getElementById("imageModal").style.display = "none";
}

// Cerrar también si hacen clic fuera de la imagen
window.onclick = function (event) {
    var modal = document.getElementById("imageModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
