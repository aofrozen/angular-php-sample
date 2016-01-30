{% if ngView != 'false' %}
    {{ this.navigation.top() }}
    {{ content() }}
    <div ui-view="top"></div>
{% else %}
404
{% endif %}