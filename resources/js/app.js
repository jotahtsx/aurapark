import Alpine from "alpinejs";
import themeController from "./theme";

window.Alpine = Alpine;

Alpine.data("themeController", themeController);

Alpine.start();
