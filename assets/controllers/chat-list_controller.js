import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["link"];
    static classes = ["selected"];

    select(event) {
        // 1. Удаляем класс у всех ссылок в списке
        this.linkTargets.forEach(el => {
            el.classList.remove(this.selectedClass);
        });

        // 2. Добавляем класс только кликнутой ссылке
        // Используем event.currentTarget, чтобы попасть именно на <a>
        event.currentTarget.classList.add(this.selectedClass);
    }
}