import { precacheAndRoute } from 'workbox-precaching';
// Файл с логикой Firebase
import './sw-firebase.js'; 

precacheAndRoute(self.__WB_MANIFEST || []);