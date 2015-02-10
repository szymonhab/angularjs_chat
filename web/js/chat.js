myApp.controller('ChatCtrl', function($scope, $http) {

  $scope.last_id  = 0;
  $scope.messages = [];

  $scope.send = function(message) {
    var postMessage = {
      'method': 'POST',
      'url': config.chat.url_post_message,
      'data': {
        'message': message
      }
    }
  
    $http(postMessage);
  }
  
  $scope.get = function() { 
    var getMessages = {
      method: 'POST',
      url: config.chat.url_get_messages,
      data: {
        'last_id': $scope.last_id
      }
    }
  
    $http(getMessages).success(
      function(data) {
        if(data.length > 0) {
          $scope.last_id  = data[data.length - 1].id;
          $scope.messages = $scope.messages.concat(data);  
        }
        $scope.scrollChat();
        $scope.get();
      }
    ).error(function() { $scope.get(); } );
  }
  
  $scope.scrollChat = function() {
    var chat = document.getElementById("chat");
    chat.scrollTop = chat.scrollHeight;
  }
    
});