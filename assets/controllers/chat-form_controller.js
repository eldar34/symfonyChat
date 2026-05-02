import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["input", "submitButton"];

     connect() {
        // Устанавливаем начальную высоту при загрузке
        this.resize();
    }

    // Очистка после отправки
    clear() { 
        this.inputTarget.value = ''; 
        this.resize();
    }; 
    
    resize() {
        const element = this.inputTarget;
        const maxHeight = 120; // Должно совпадать с textarea[data-chat-form-target="input"] - max-height в CSS

        element.style.height = 'auto'; // Сбрасываем для перерасчета

        if (element.scrollHeight > maxHeight) {
            element.style.height = maxHeight + 'px';
            element.style.overflowY = 'auto'; // Включаем скролл
        } else {
            element.style.height = element.scrollHeight + 'px';
            element.style.overflowY = 'hidden'; // Прячем скролл, если текста мало
        }

        this.toggleButton(); // Проверяем, нужно ли разблокировать кнопку
    };

    toggleButton() {
        // Если текста нет (или только пробелы) — кнопка неактивна
        const isEmpty = this.inputTarget.value.trim().length === 0;
        this.submitButtonTarget.disabled = isEmpty;
    };

    handleKeydown(event) {
        // Проверяем, что нажат именно Enter
        if (event.key === "Enter") {
            // Если Shift НЕ зажат — отправляем форму
            if (!event.shiftKey) {
                event.preventDefault(); // Запрещаем перенос строки
                
                // Проверяем, что поле не пустое перед отправкой
                if (this.inputTarget.value.trim().length > 0) {
                    this.element.requestSubmit(); 
                }
            }
        }
    };
}