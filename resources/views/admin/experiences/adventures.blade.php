@extends((Auth::user()->isSuper()) ? 'layouts.dashboard':'layouts.platform')

@section('title', trans('messages.experiences'))
@section('section-title')
    <div class="ui huge breadcrumb">
        <div class="section">Experiencias</div>
        <i class="right chevron icon divider"></i>
        <div class="active section">{{$experience->title}}</div>
        <i class="right chevron icon divider"></i>
        <div class="section">Aventuras</div>


    </div>
@endSection
@prepend('init_scripts')
    <script>
        tree_info={!!  json_encode($adventures_tree) !!};
    </script>
@endprepend
@section('body')
        <div id="experience-detail" class="sixteen wide tablet ten wide computer column">

            @if(count($adventures_tree))
            <div class="row">
                <div class="sixteen wide column">
                    <apithy-adventures-tree ref="root_tree" :data="tree_info" :experience="{{$experience}}"></apithy-adventures-tree>
                </div>
            </div>
          @else
                <h3>Aun no tienes Aventuras Asignadas</h3>
          @endif
        </div>
@endsection