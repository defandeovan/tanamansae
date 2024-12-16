let currentSlide = 0;


function moveSlide(direction) {
    const slider = document.querySelector('.slider');
    const slides = document.querySelectorAll('.slide');
    const totalSlides = slides.length;

    // Update current slide index
    currentSlide += direction;

    // Loop back to the first slide if it exceeds the total
    if (currentSlide < 0) {
        currentSlide = totalSlides - 1;
    } else if (currentSlide >= totalSlides) {
        currentSlide = 0;
    }

    // Move the slider
    const offset = currentSlide * -100;
    slider.style.transform = `translateX(${offset}%)`;
}

// Auto-slide every 3 seconds
setInterval(() => moveSlide(1), 3000);
function goToDetail(productId) {
    // Navigasi ke halaman detail produk dengan parameter ID
    window.location.href = `detail_produk.php?id=${productId}`;
}
function performSearch() {
    var searchInput = document.getElementById('searchInput');
    var searchQuery = searchInput.value.trim();
    var bannerContainer = document.getElementById('bannerContainer');
    console.log('Banner Container:', bannerContainer);

    // Sembunyikan banner
    if (bannerContainer) {
        bannerContainer.style.display = 'none';
    }

    if (searchQuery !== '') {
        window.location.href = 'shop.php?search=' + encodeURIComponent(searchQuery);


    } else {
        window.location.href = 'shop.php'

    }
}
