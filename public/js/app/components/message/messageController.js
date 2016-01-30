
angular.module('socialSample.message', []).controller('messageController', ['$scope', 'messageService', 'userService', 'Idle', '$firebaseArray', function ($scope, messageService, userService, $idle, $firebaseArray) {
    userService._load(function (data) {
        console.info('Message is fired');
        $scope.mc = {};
        $scope.mc.user = {};
        $scope.mc.user = data;
        $scope.mc.message = {};
        $scope.mc.message.friends = [];
        $scope.mc.message.contacts = {};

        /* Friends List */
        var friendsRef = [];
        for (var x = 0; x < $scope.mc.user.friends.length; x++) {
            setFriendRef(x);
        }
        function setFriendRef(index) {
            /* Below data will still be written if either offline or online firebase */
            $scope.mc.message.friends[index] = {};
            $scope.mc.message.friends[index].avatar = $scope.mc.user.friends[index].fid.avatar;
            $scope.mc.message.friends[index].name = $scope.mc.user.friends[index].fid.name;
            $scope.mc.message.friends[index].uid = $scope.mc.user.friends[index].fid.fid;
            $scope.mc.message.friends[index].index = index;

            friendsRef[index] = new Firebase('https://mclive.firebaseio.com/users/presence/' + $scope.mc.user.friends[index].fidKey);
            friendsRef[index].on('value', function (snap) {
                if (snap.val()) {
                    $scope.$apply(function () {
                        $scope.mc.message.friends[index]['firebase'] = snap.val();
                    });
                }
            });
        }

        function getUserNameWithUserId(uid) {
            var name = uid;
            angular.forEach($scope.mc.user.friends, function (value, index) {
                var fid = value.fid.fid.toString();
                if (fid == uid) {
                    name = value.fid.name;
                }
            });
            return name;
        }


        /* Contacts */
        $scope.mc.message.contactRef = contactsRef = new Firebase('https://mclive.firebaseio.com/messages/contacts/' + $scope.mc.user.key);
        $scope.mc.message.contacts = $firebaseArray($scope.mc.message.contactRef.orderByChild('lastChatTS'));
        var selectTS = 0;
        var selectIndex = 0;
        var contactsWatch = $scope.mc.message.contacts.$watch(function (data) {
            angular.forEach($scope.mc.message.contacts, function (value, index) {
                if($scope.mc.message.contacts[index].selectTS > selectTS)
                {
                    selectTS = $scope.mc.message.contacts[index].selectTS;
                    selectIndex = index;
                }
                $scope.mc.message.contacts[index].select = false;
                $scope.mc.message.contacts[index].name = getUserNameWithUserId(value.uid);
            });
            $scope.mc.message.contacts[selectIndex].select = true;
            $scope.mc.message.contacts.select = $scope.mc.message.contacts[selectIndex];
        });
        $scope.$on("$destroy", function () {
            contactsWatch();
        });

    });

}])
    .controller('messageEventController', ['$scope', 'messageService', 'userService', 'idleService', function ($scope, messageService, userService, idleService) {
        userService._load(function (data) {
            console.info('message event');
            if (typeof data !== 'undefined') {
                $scope.mc = {};
                $scope.mc.user = {};
                $scope.mc.user = data;
                $scope.mc.message = {};
                var amOnline = new Firebase('https://mclive.firebaseio.com/.info/connected');
                var userRef = new Firebase('https://mclive.firebaseio.com/users/presence/' + $scope.mc.user.key); //require to authorize to prevent someone to modify
                amOnline.on('value', function (snap) {
                    //console.info(snapshot);
                    if (snap.val()) {
                        userRef.onDisconnect().update({
                            'end': Firebase.ServerValue.TIMESTAMP,
                            'online': false,
                            'status': 'away'
                        });
                        userRef.update({'online': true, 'status': 'here', 'began': Firebase.ServerValue.TIMESTAMP});
                    }
                });
                amOnline.on('value', function (users) {
                    if (users.val()) {
                    }
                });
                var awayCallback = function () {
                    console.log(new Date().toTimeString() + ": away");
                    userRef.update({'status': 'away'});
                };

                var awayBackCallback = function () {
                    console.log(new Date().toTimeString() + ": back");
                    userRef.update({'status': 'here'});
                };
                var onVisibleCallback = function () {
                    console.log(new Date().toTimeString() + ": now looking at page");
                };

                var onHiddenCallback = function () {
                    console.log(new Date().toTimeString() + ": not looking at page");
                };
                //this is one way of using it.
                /*
                 var idle = new Idle();
                 idle.onAway = awayCallback;
                 idle.onAwayBack = awayBackCallback;
                 idle.setAwayTimeout(2000);
                 idle.start();
                 */
                //this is another way of using it
                idleService.idle({
                    onHidden: onHiddenCallback,
                    onVisible: onVisibleCallback,
                    onAway: awayCallback,
                    onAwayBack: awayBackCallback,
                    awayTimeout: 180000 //away with 5 seconds of inactivity
                });
                idleService.start();

            }

        });

    }]);