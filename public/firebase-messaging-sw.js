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
    apiKey: "AIzaSyDGxOFIU7qQ1Mg9dA__NAj12ruZATKay6k",
    authDomain: "shwesankan-f6f4d.firebaseapp.com",
    projectId: "shwesankan-f6f4d",
    storageBucket: "shwesankan-f6f4d.firebasestorage.app",
    messagingSenderId: "483726217887",
    appId: "1:483726217887:web:dce924196fe1794491c8aa",
    measurementId: "G-M5Y109ZTQX"
  };


// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

self.addEventListener("fetch", (event) => {
    // Handle fetch events here
    // console.log('fetching');
});
