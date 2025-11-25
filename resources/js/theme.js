export default function themeController() {
    return {
        theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',

        init() {
        },

        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            localStorage.setItem('theme', this.theme);

            document.documentElement.setAttribute('data-theme', this.theme);
            document.documentElement.classList.toggle('dark', this.theme === 'dark');
        }
    };
}
