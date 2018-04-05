var config = {
    apiKey: apiKey,
    authDomain: authDomain,
    databaseURL: databaseURL,
    projectId: projectId,
    storageBucket: storageBucket,
    messagingSenderId: messagingSenderId
};
firebase.initializeApp(config);

const messaging = firebase.messaging();
messaging.requestPermission()
.then(function() {
    console.log('mave permission');
    return messaging.getToken();
})
.then(function(token) {
    console.log(token);
    $.ajax({
            url: baseUrl + '/user/ajax-add-token',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {token: token},
            success: function(data) {
                if (data.code == 200) {

                }
            }
        });
}) 
.catch(function(err) {
    console.log(err);
})
  
  
messaging.onMessage(function(payload) {
  var dataMessage = JSON.parse(payload.data.data);
  console.log(dataMessage);
  
  var dataSend = {
        title: '',
        message: dataMessage.message,
    	timeout: 1000000,
        // imageWidth: 70,
        position: 'bottomLeft',
        transitionIn: 'bounceInRight',
        // rtl: true,
        // iconText: 'star',
        onOpen: function(instance, toast){
            $
        },
        onClose: function(instance, toast, closedBy){
            console.info('closedBy: ' + closedBy);
        }
    }
  
  if (dataMessage.image != undefined) {
    dataSend.icon = dataMessage.image
  }
  
  iziToast.info(dataSend);
  //return self.registration.showNotification(notificationTitle, notificationOptions);
});

