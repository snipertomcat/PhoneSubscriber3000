@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title','Assignations')

@section('body')
    <div class="experience-info">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <div class="card">
                    <div class="card-image">
                        <div class="card-image-inner">
                            <figure class="image is-4by3">
                                <img src="{{$experience->full_path_cover}}">
                            </figure>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-icon author-image">
                            <div class="image-author-container row align-items-center justify-content-center">
                                <img src="{{ $experience->company_author->full_path_logo }}"/>
                            </div>
                        </div>
                        <div class="media">
                            <div class="media-content">
                                <p class="title is-6">{{ $experience->title }}</p>
                            </div>
                        </div>
                        <div class="media">
                            <p class="subtitle is-7">Creado por: <a class="primary-color">{{ $author->full_name}}</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <apithy-buy-licences
                :experience="{{$experience}}"
                :cart-data="{{$cart_data}}"
                :company-data="{{$company_data}}"
                :only-buy="true">
        </apithy-buy-licences>
    </div>
    <br><br><br>
@endsection