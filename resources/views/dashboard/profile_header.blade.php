
<div class="profile-header row justify-content-center">
    <div class="profile-image col-auto">
        <div class="image">
            <img class="" src="{{ $auth->user()->avatarUrl() }}" alt="label-image"/>
        </div>
    </div>
    <div class="profile-data col-4 col-md-3 col-lg-2 align-self-center">
        <div class="header" style="color:black !important">
            <span>{{ $auth->user()->full_name }}</span>
        </div>
        <div class="meta">
            <i class="fas fa-address-card"></i>
            <span>{{ $auth->user()->email }}</span>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .profile-image .image {
            width: 100px;
            height: 100px;
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
            overflow: hidden;
        }
        .profile-image .image img {
            height: 100%;
        }
        .tabs ul {
            align-items: center;
            border-bottom-color: #dbdbdb;
            border-bottom-style: solid;
            border-bottom-width: 1px;
            display: flex;
            flex-grow: unset !important;
            flex-shrink: unset !important;
            justify-content: unset !important;
            width: 700px;
            margin: 0 auto;
        }

        .column.has-vertically-aligned-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        /* Small devices (portrait phones, 320px and up) */
        @media (min-width: 320px) {
            .meta span {
                font-size: 0.6rem;
            }
        }

        /* Small devices (landscape phones, 576px and up) */
        @media (min-width: 576px) {
            .meta span {
                font-size: 1rem;
            }
        }
    </style>
@endpush