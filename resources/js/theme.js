export default function themeController() {
    return {
        // Inicializa com o valor salvo ou respeita a preferência do sistema
        theme: localStorage.getItem('theme') || 
               (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),

        init() {
            this.applyTheme();
        },

        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            this.applyTheme();
        },

        applyTheme() {
            localStorage.setItem('theme', this.theme);
            
            // 1. Para DaisyUI e outros frameworks baseados em atributos
            document.documentElement.setAttribute('data-theme', this.theme);
            
            // 2. Para Tailwind CSS modo 'class'
            if (this.theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    };
}