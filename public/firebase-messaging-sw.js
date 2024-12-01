importScripts("https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js");

if ("serviceWorker" in navigator) {
    navigator.serviceWorker
    .register("/firebase-messaging-sw.js")
    .then((registration) => {
        console.log("Service Worker registered with scope:", registration.scope);
    })
    .catch((error) => {
        console.error("Service Worker registration failed:", error);
    });
}

const firebaseConfig = {
    apiKey: "AIzaSyBCCahGjQF9Ul8XOHhNEWg6NV7Wja3UnTU",
    authDomain: "shwepaukkan-73aab.firebaseapp.com",
    projectId: "shwepaukkan-73aab",
    storageBucket: "shwepaukkan-73aab.appspot.com",
    messagingSenderId: "28265284223",
    appId: "1:28265284223:web:d7bdac409dd5144272754a",
    measurementId: "G-YRZQ7T75RZ"
  };


// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

self.addEventListener("fetch", (event) => {
    // Handle fetch events here
    // console.log('fetching');
});
