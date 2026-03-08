// Hide the preloader after the page loads
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('preloaderMain').style.display = 'none';
});

// Back to top
const backToTopBtn = document.getElementById("btn-back-to-top");
window.onscroll = function () {
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
    backToTopBtn.style.display = "block";
    } else {
    backToTopBtn.style.display = "none";
    }
};
backToTopBtn.addEventListener("click", function () {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
});

// Set current year
document.getElementById("year").textContent = new Date().getFullYear();

function scrollTabs(distance) {
    document.getElementById('tabScroll').scrollBy({ left: distance, behavior: 'smooth' });
}

 document.addEventListener("DOMContentLoaded", function () {
    const currentPath = window.location.pathname;
    const tabs = document.querySelectorAll(".tab-link");

    tabs.forEach(tab => {
        // Match both exact and trailing slash variations
        if (tab.getAttribute("href") === currentPath || tab.getAttribute("href") + "/" === currentPath) {
            tab.classList.add("active");
        } else {
            tab.classList.remove("active");
        }
    });
});
