<a class="ui red labeled icon button" onclick="showDeleteModal({{$id}})">
    {{ trans('messages.delete') }}
    <i class="trash icon"></i>
</a>
@push('modals')
<div class="ui modal standart delete-{{$id}}">
    <div class="header">
        {{ trans('messages.delete_user') }}
    </div>
    <div class="content">
        <sui-form method="POST" action="{{ $action }}">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <div class="actions">
            <sui-form-fields>
                <sui-form-field>
                    <sui-button type="submit" class="ui small positive labeled icon button">
                        {{ trans('messages.delete') }}
                        <i class="trash icon"></i>
                    </sui-button>
                </sui-form-field>
                <sui-form-field>
                    <a class="ui negative labeled icon button">
                        {{ trans('messages.cancel') }}
                        <i class="cancel icon"></i>
                    </a>
                </sui-form-field>
            </sui-form-fields>
            </div>
        </sui-form>
    </div>
</div>
@endpush