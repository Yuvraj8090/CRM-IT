AOS.init({
    duration: 1000,
    easing: 'ease-in-out',
    once: true,
    offset: 100
});

// Mobile menu toggle
document.getElementById('mobile-menu-button').addEventListener('click', function () {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
});

// Sticky navbar
window.addEventListener('scroll', function () {
    const header = document.querySelector('header');
    if (window.scrollY > 100) {
        header.classList.add('shadow-lg', 'bg-slate-900/95');
    } else {
        header.classList.remove('shadow-lg', 'bg-slate-900/95');
    }
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();

        const targetId = this.getAttribute('href');
        if (targetId === '#') return;

        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            // Close mobile menu if open
            document.getElementById('mobile-menu').classList.add('hidden');

            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });
        }
    });
});

// Form submission
document.getElementById('contact-form').addEventListener('submit', function (e) {
    e.preventDefault();

    // Simple form validation
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;

    if (name && email && message) {
        // In a real application, you would send the form data to a server here
        // For this example, we'll just show a success message

        // Create a success notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        notification.innerHTML = `
 <div class="flex items-center">
     <i class="fas fa-check-circle mr-2"></i>
     <span>Thank you for your message! We will get back to you soon.</span>
 </div>
 `;
        document.body.appendChild(notification);

        // Remove notification after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);

        // Reset form
        this.reset();
    }
});

// Add floating animation to multiple elements
document.querySelectorAll('.floating').forEach((el, index) => {
    el.style.animationDelay = `${index * 2}s`;
});
