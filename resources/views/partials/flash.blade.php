@if (session()->has('flash_alert_message'))
    <div class="ui {{ session()->get('flash_alert_type') }} message">
        <i class="close icon"></i>
        <div class="header">
            {{ session()->get('flash_alert_header') }}
        </div>
        <p>{{ session()->get('flash_alert_message') }}</p>
    </div>
@endif
<sui-message v-if="messageData.visible"
             :class="messageData.messageClass"
             :icon="messageData.icon"
             :header="messageData.header"
             :content="messageData.content"
             dismissable
             @dismiss="handleDismissMessage"
>
</sui-message>

<sui-modal v-model="apithyModal.open">
    <sui-modal-header>@{{ apithyModal.header }}</sui-modal-header>
    <sui-modal-content>
        @{{apithyModal.content }}
    </sui-modal-content>
    <sui-modal-actions>
        <sui-button floated="right" positive @click.native="apithyModalOk">
            @{{apithyModal.button_ok_text }}
        </sui-button>
        <sui-button floated="right" positive @click.native="apithyModalCancel">
            @{{apithyModal.button_cacel_text }}
        </sui-button>
    </sui-modal-actions>
</sui-modal>

@if(count($errors))
    <div class="ui error message">
        <ul class="list">
            @foreach ($errors->all() as $error)
                <li>{!!  $error !!}</li>
            @endforeach
        </ul>
    </div>
@endif