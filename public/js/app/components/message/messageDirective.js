angular.module('socialSample.messageDirective', [])
    .directive('chatButton', ['$firebaseArray', 'messageService', function ($firebaseArray, messageService) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                element.bind('click', function (e) {
                    /*
                     Check if contact exists. When it doesn't exist then create a contact then create chat with contactId.
                     When it exists then get the contactId to get chat
                     */
                    /*
                     Always load contacts when open message
                     So contacts can be scanned for existing if not then create new one.


                     Should contacts be under mongodb or firebase *** due to if someone sent a message to other then firebase should handle

                     */
                    var exist = false;
                    var friendId = attrs.friendId;
                    var contacts = scope.mc.message.contactRef;
                    var contactsRef = scope.mc.message.contacts;
                    contactsRef.$loaded().then(function () {
                        if (contactsRef.length == 0) {
                            contactsRef.$add({'uid': friendId, selectTS: Firebase.ServerValue.TIMESTAMP, lastChatTS: Firebase.ServerValue.TIMESTAMP}).then(function (ref) {

                            });
                        } else {
                            contactsRef.forEach(function (contact) {
                                if (contact.uid == friendId) {
                                    exist = true;
                                    contacts.child(contact.$id).update({selectTS: Firebase.ServerValue.TIMESTAMP});
                                }
                            });
                            if (exist == false) {
                                contactsRef.$add({'uid': friendId, selectTS: Firebase.ServerValue.TIMESTAMP, lastChatTS: Firebase.ServerValue.TIMESTAMP}).then(function (ref) {

                                });
                            }
                        }
                    });
                });
            }
        }
    }]);