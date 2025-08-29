    function toggleTheme() {
    document.body.classList.toggle('dark');
    const isDark = document.body.classList.contains('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    updateThemeButton();
}

    function updateThemeButton() {
    const button = document.getElementById('theme-toggle');
    button.textContent = document.body.classList.contains('dark') ? '☀️' : '🌙';
}

    document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
    document.body.classList.add('dark');
}
    updateThemeButton();
});
