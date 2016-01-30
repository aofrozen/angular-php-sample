{% if ngView != 'false' %}
    {{ this.navigation.top() }}
    <div ui-view="top"></div>
{% else %}
    <div class="modal" tabindex="-1" role="dialog" ng-controller="messageController">
        <div class="message-dialog">
            <div class="contacts-list">
                <div class="search">
                    <input type="text" class="form-control" ng-model="contactSearch" placeholder="Search contact...">
                </div>
                <div class="contact-items">
                    <ul>
                        <li ng-class="{'selected' : contact.select, 'contact' : true}" ng-repeat="contact in mc.message.contacts | filter:contactSearch | reverse">
                            <a href="#" ng-bind="contact.name" chat-button data-friend-id="{{ '{{ contact.uid }}' }}"></a>
                    </ul>
                </div>
            </div>
            <div class="chat">
                <div class="body">
                    <div class="messages">
                        <div class="to">Developing message feature....</div>
                        <div class="sender"></div>
                    </div>
                    <div class="sender">
                        <input type="text" class="form-control" placeholder="Write a message..." ng-model="messageData">
                    </div>
                    <div class="clear-both"></div>
                </div>
            </div>

            <div class="users-list">
                <div class="search">
                    <input type="text" class="form-control" ng-model="userSearch.$" placeholder="Search friends...">
                </div>
                <div class="user-items">
                    <ul>
                        <li ng-repeat="friend in mc.message.friends | filter:userSearch:strict | reverse">
                            <a ng-href="#" ng-bind="friend.name+' '+friend.firebase.status" chat-button data-friend-id="{{ '{{ friend.uid }}' }}"></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
{% endif %}