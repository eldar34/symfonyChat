import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["menu", "talk", "link"];
    static classes = ["selected"];

    select(event) {
        // 1. Удаляем класс у всех ссылок в списке
        this.linkTargets.forEach(el => {
            el.classList.remove(this.selectedClass);
        });

        // 2. Добавляем класс только кликнутой ссылке
        // Используем event.currentTarget, чтобы попасть именно на <a>
        event.currentTarget.classList.add(this.selectedClass);

        // 3. Логика переключения (только мобильные)
        if (window.innerWidth < 768) {
            this.menuTarget.classList.add("d-none"); // Прячем список
            this.talkTarget.classList.remove("d-none"); // Показываем чат
            this.talkTarget.classList.add("d-flex"); // Для корректной работы flex-структуры
        }
    }

    backToList(event) {
        event.preventDefault();

        // 1. Логика переключения (только мобильные)
        if (window.innerWidth < 768) {
             // 2. Удаляем класс у всех ссылок в списке
            this.linkTargets.forEach(el => {
                el.classList.remove(this.selectedClass);
            });

            this.talkTarget.classList.add("d-none"); // Прячем чат
            this.talkTarget.classList.remove("d-flex");
            this.menuTarget.classList.remove("d-none"); // Показываем список
        }
    }
}