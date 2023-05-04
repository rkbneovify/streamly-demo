// importScripts('https://www.gstatic.com/firebasejs/4.8.1/firebase-app.js');
// importScripts('https://www.gstatic.com/firebasejs/4.8.1/firebase-messaging.js');

// // Initialize Firebase
// var config = {
//   apiKey: "AIzaSyBUZy1NPWtNoFHp9FboPrbVXa26cjbzbDU",
//   authDomain: "streamlytv-b1918.firebaseapp.com",
//   databaseURL: "https://streamlytv-b1918-default-rtdb.firebaseio.com",
//   projectId: "streamlytv-b1918",
//   storageBucket: "streamlytv-b1918.appspot.com",
//   messagingSenderId: "664124239673",
//   appId: "1:664124239673:web:07d1f7678fda4d19f102e8",
//   measurementId: "G-61CXQDCLBH"
// };

// firebase.initializeApp(config);
// const messaging = firebase.messaging();
// messaging.setBackgroundMessageHandler(function (payload) {
//   console.log('Handling background message ', payload);

//   return self.registration.showNotification(payload.data.title, {
//     body: payload.data.body,
//     icon: payload.data.icon,
//     tag: payload.data.tag,
//     data: payload.data.link,
//   });
// });

// self.addEventListener('notificationclick', function (event) {
//   event.notification.close();
//   event.waitUntil(self.clients.openWindow(event.notification.data));
// });

// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
var config = {
    apiKey: "AIzaSyDZUPtq5LXm8bbcX5P-z0r-B08V65xq2aw",
    authDomain: "streamly-tv-android.firebaseapp.com",
    databaseURL: "https://streamly-tv-android.firebaseio.com",
    projectId: "streamly-tv-android",
    storageBucket: "streamly-tv-android.appspot.com",
    messagingSenderId: "187207008307",
    appId: "1:187207008307:web:40473f0b50d96ab0d76346",
    measurementId: "G-5DTH6FMY71"
};

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.

firebase.initializeApp(config);
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log("Message received.", payload);
    const title = "Hello world is awesome";
    const options = {
        body: "Your notificaiton message .",
        icon: "/firebase-logo.png",
    };
    return self.registration.showNotification(
        title,
        options,
    );
});