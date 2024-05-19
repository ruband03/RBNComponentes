document.addEventListener('DOMContentLoaded', function () {
    const profileImage = document.getElementById('profile-menu');
    const dropdownMenu = document.getElementById('dropdown-menu');

    profileImage.addEventListener('click', function () {
        dropdownMenu.style.display = dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '' ? 'block' : 'none';
    });

    window.addEventListener('click', function (e) {
        if (!profileImage.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.style.display = 'none';
        }
    });
});
