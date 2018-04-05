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

//deleteToken();

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
  if (userId != dataMessage.payload.message.senderId) {
     var notification = notifyMe(
        dataMessage.payload.message.content,
        dataMessage.image,
        dataMessage.payload.team,
        dataMessage.click_action
    );
    var urlPage = baseUrl + '/user?page=1';
    paging(urlPage, {search: $('.search-input').val()});
    
    
    

    /*
        var dataSend = {
            title: dataMessage.payload.team;,
            message: dataMessage.payload.message.content,
            image: dataMessage.image
        	timeout: 1000000,
            // imageWidth: 70,
            position: 'bottomLeft',
            transitionIn: 'bounceInRight',
            // rtl: true,
            // iconText: 'star',
            onOpen: function(instance, toast){
                
            },
            onClose: function(instance, toast, closedBy){
                console.info('closedBy: ' + closedBy);
            }
        }
      
      if (dataMessage.image != undefined) {
        dataSend.icon = dataMessage.image
      }
      
      iziToast.info(dataSend);
      */
  }
  
  //return self.registration.showNotification(notificationTitle, notificationOptions);
});


function deleteToken() {
    messaging.getToken()
        .then(function(currentToken) {
            messaging.deleteToken(currentToken)
                .then(function() {
                    console.log('Token deleted.');
                })
                .catch(function(err) {
                    console.log('Unable to delete token. ', err);
                });
            // [END delete_token]
        })
        .catch(function(err) {
            console.log('Error retrieving Instance ID token. ', err);
        });
}


function notifyMe(theBody,theIcon,theTitle, url) {
    var options = {
      body: theBody,
      icon: theIcon
    }
    var notification;
    // Let's check if the browser supports notifications
    if (!("Notification" in window)) {
        alert("This browser does not support desktop notification");
    }
    // Let's check whether notification permissions have already been granted
    else if (Notification.permission === "granted") {
    // If it's okay let's create a notification
        var notification = new Notification(theTitle, options);
        notification.onclick = function(event){
            console.log(11111);   
            event.preventDefault(); // prevent the browser from focusing the Notification's tab
            if (url != undefined) {
                window.open(url);
            }
        };
    }
    // Otherwise, we need to ask the user for permission
    else if (Notification.permission !== "denied") {
        Notification.requestPermission(function (permission) {
        // If the user accepts, let's create a notification
        if (permission === "granted") {
            var notification = new Notification(theTitle, options);
            notification.onclick = function(event){
                console.log(11111);   
                event.preventDefault(); // prevent the browser from focusing the Notification's tab
                if (url != undefined) {
                    window.open(url);
                } 
            };
        }
    });
    
  }
}