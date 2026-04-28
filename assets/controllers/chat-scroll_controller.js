import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["currentMessage"]; 

    connect() { 
        this.applyStyles();
        this.scroll(); 
    }
    
    preserve(event) {
        if (event.detail.newStream.action === 'append') {
            setTimeout(() => {
                this.applyStyles(); // Сначала красим новое сообщение
                this.scroll();      // Затем скроллим вниз
            }, 50);
        }
    }

    applyStyles() {
        const currentUserId = parseInt(this.element.dataset.currentUserId);
        
        this.currentMessageTargets.forEach(currentMessage => {
            const authorId = parseInt(currentMessage.dataset.authorId);
            
            if (authorId === currentUserId) {
                currentMessage.classList.add('msg-enviada', 'float-end', 'shadow-sm');
                currentMessage.classList.remove('msg-recebida', 'float-start');
            } else {
                currentMessage.classList.add('msg-recebida', 'float-start');
                currentMessage.classList.remove('msg-enviada', 'float-end', 'shadow-sm');
            }
        });
    }

    scroll() { 
        this.element.scrollTop = this.element.scrollHeight; 
    }
}