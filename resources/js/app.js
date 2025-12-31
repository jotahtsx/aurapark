import Alpine from "alpinejs";
import { createIcons, icons } from 'lucide';
import themeController from "./theme";

window.Alpine = Alpine;

Alpine.data("themeController", themeController);

Alpine.start();

createIcons({
    icons: icons
});