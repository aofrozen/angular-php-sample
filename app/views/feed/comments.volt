{% if ngView != 'false' %}
    {{ this.navigation.top() }}
    <div ui-view="top"></div>
{% else %}
    <div class="modal" tabindex="-1" role="dialog" ng-controller="messageController">
        <div class="dialog">
            Message here
        </div>
    </div>
{% endif %}