import { Controller } from '@hotwired/stimulus';
import { initializeApp } from "firebase/app";
import { getMessaging, getToken } from "firebase/messaging";

export default class extends Controller {
    static values = {
        vapidKey: String,
        firebaseConfig: Object
    }

    async connect() {
        // Инициализация Firebase
        const app = initializeApp(this.firebaseConfigValue);
        this.messaging = getMessaging(app);
    }

    async subscribe() {
        try {
            console.log('Метод subscribe вызван!');
            
            const permission = await Notification.requestPermission();
            
            if (permission === 'granted') {
                console.log('Ждем сервис-воркер...');
                const registration = await navigator.serviceWorker.ready;
                console.log('Сервис-воркер готов:', registration);
                
                const token = await getToken(this.messaging, {
                    vapidKey: this.vapidKeyValue,
                    serviceWorkerRegistration: registration
                });

                if (token) {
                    // await this.saveTokenToServer(token);
                   await console.log(token);
                }else{
                    await console.log(121212);
                }
            }
        } catch (error) {
            console.error('Ошибка при подписке:', error);
        }
    }

    async saveTokenToServer(token) {
        // Отправляем токен на ваш Symfony API
        await fetch('/api/pwa/store-token', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ token: token })
        });
        
        console.log('Токен успешно сохранен');
    }
}