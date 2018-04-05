importScripts('https://www.gstatic.com/firebasejs/3.9.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/3.9.0/firebase-messaging.js');

var config = {
    apiKey: "AIzaSyASD09KY_hR6ZGulSDFhXKMLpDFI_udqNM",
    authDomain: "fdev-env-18691.firebaseapp.com",
    databaseURL: "https://dev-env-18691.firebaseio.com",
    projectId: "dev-env-18691",
    storageBucket: "dev-env-18691.appspot.com",
    messagingSenderId: "447242930089"
};
firebase.initializeApp(config);
const messaging = firebase.messaging();



messaging.setBackgroundMessageHandler(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  var dataMessage = JSON.parse(payload.data.data);
  console.log(dataMessage);
  console.log(dataMessage.payload.message.content);
  // Customize notification here
  const notificationTitle = dataMessage.payload.team;
  const notificationOptions = {
    body: dataMessage.payload.message.content,
    icon: dataMessage.image,
    click_action: dataMessage.click_action
  };

  return self.registration.showNotification(notificationTitle, notificationOptions);
});
