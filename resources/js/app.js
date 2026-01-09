import Alpine from "alpinejs";
import mask from "@alpinejs/mask"; // 1. Importa o plugin
import { createIcons, icons } from "lucide";
import themeController from "./theme";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

// 2. Registra o plugin ANTES de qualquer outra coisa do Alpine
Alpine.plugin(mask);

window.Alpine = Alpine;
Alpine.data("themeController", themeController);

// 3. O start() tem que ser a ÚLTIMA coisa do Alpine
Alpine.start();

createIcons({ icons });

// Sua função de Flatpickr continua igual
function initDueDayPicker(pickerId, hiddenId, defaultDay = null) {
    const picker = document.getElementById(pickerId);
    const hidden = document.getElementById(hiddenId);

    if (!picker || !hidden) return;

    const instance = flatpickr(picker, {
        disableMobile: true,
        dateFormat: "d \\d\\e F",
        defaultDate: defaultDay ? new Date(2024, 0, defaultDay) : new Date(),
        onChange(selectedDates) {
            const date = selectedDates[0];
            if (!date) return;
            const day = date.getDate();
            if (day > 28) {
                alert("Selecione um dia entre 1 e 28");
                picker.value = "";
                hidden.value = "";
                return;
            }
            hidden.value = day;
        },
    });
    return instance;
}

document.addEventListener("DOMContentLoaded", () => {
    initDueDayPicker("due_day_picker", "due_day");
    window.editDuePicker = initDueDayPicker("edit_due_day_picker", "edit_due_day");
});