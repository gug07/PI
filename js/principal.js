let currentImageIndex = 0;
const images = document.querySelectorAll('.carousel-images img');
const totalImages = images.length;
const imageWidth = images[0].offsetWidth + 30; // Ajusta a largura com o espaçamento lateral (30px)

document.querySelector('.left').addEventListener('click', () => {
    currentImageIndex = (currentImageIndex > 0) ? currentImageIndex - 1 : totalImages - 1;
    updateCarousel();
});

document.querySelector('.right').addEventListener('click', () => {
    currentImageIndex = (currentImageIndex < totalImages - 1) ? currentImageIndex + 1 : 0;
    updateCarousel();
});

function updateCarousel() {
    const offset = -currentImageIndex * imageWidth; // Desloca conforme o número de imagens
    document.querySelector('.carousel-images').style.transform = `translateX(${offset}px)`;
}
