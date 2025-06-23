// Sidebar toggle untuk mobile
document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.createElement('button');
    sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
    Object.assign(sidebarToggle.style, {
        position: 'fixed',
        bottom: '20px',
        right: '20px',
        zIndex: '99',
        width: '50px',
        height: '50px',
        borderRadius: '50%',
        background: 'var(--primary-color)',
        color: 'white',
        border: 'none',
        boxShadow: '0 4px 12px rgba(0,0,0,0.2)',
        cursor: 'pointer',
        display: 'none',
        alignItems: 'center',
        justifyContent: 'center',
    });

    sidebarToggle.addEventListener('click', () => {
        document.querySelector('.sidebar')?.classList.toggle('active');
    });

    document.body.appendChild(sidebarToggle);

    function checkScreenSize() {
        if (window.innerWidth <= 992) {
            sidebarToggle.style.display = 'flex';
        } else {
            sidebarToggle.style.display = 'none';
            document.querySelector('.sidebar')?.classList.remove('active');
        }
    }

    window.addEventListener('resize', checkScreenSize);
    checkScreenSize();
});
