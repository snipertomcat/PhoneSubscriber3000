<a class="button is-small" @click="showRemove = true">
    <span class="icon is-small">
        <i class="fa fa-trash"></i>
    </span>
    <span>{{ trans('messages.delete') }}</span>
</a>

<div class="modal" :class="{'is-active': showRemove}">
    <div class="modal-background"></div>
    <div class="modal-container">
        <div class="modal-content box">
            <article class="message is-warning">
                <div class="message-body">
                    {{ trans('messages.delete_user') }}
                </div>
            </article>
            <form method="post" action="{{ $action }}">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <div class="field is-grouped">
                    <p class="control">
                        <button type="submit" class="button is-danger">{{ trans('messages.delete') }}</button>
                    </p>
                    <p class="control">
                        <button type="button" class="button is-outlined" @click="showRemove = false">{{ trans('messages.cancel') }}</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <button class="modal-close" @click="showRemove = false"></button>
</div>
