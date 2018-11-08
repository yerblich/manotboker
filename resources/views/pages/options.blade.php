@extends('layouts.main')

@section('content')

<style>
        #sortable { list-style-type: 1; margin: 0; padding: 0; width: 60%; }
        #sortable li { margin: 4px 3px 3px 20px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 50px; }
        #sortable li span { position: absolute; margin-left: -1.3em; }
        </style>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Options</a>
        </li>

      </ol>
      <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="routes-tab" data-toggle="tab" href="#routes" role="tab" aria-controls="home" aria-selected="true">מסלולים</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">hello</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false"></a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="routes" role="tabpanel" aria-labelledby="routes-tab">
                    <ol type="1" id="sortable">
                        @foreach($clients as $client)
                        <li id ={{$client->name}} class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>{{$client->name}}</li>
                        @endforeach
                        {!! Form::open(['action'=> ['OptionsController@store'], 'method' => 'POST']) !!}
                        {{ csrf_field() }}

                        {!! Form::hidden('route','',['class'=>'route']) !!}
                        {{Form::submit('Update Route', ['id' =>'getRoute', 'class' => 'btn btn-primary'])}}
                        {!! Form::close() !!}

                            {{-- <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 1</li>
                            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 2</li>
                            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 3</li>
                            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 4</li>
                            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 5</li>
                            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 6</li>
                            <li class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>Item 7</li> --}}
                          </ol>

            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
          </div>

    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->


@endsection

@section('options')
    <script>
        var token = '{{Session::token()}}';
        var url = '{{ route("options.store")}}'
    </script>
<script src={{ asset('storage/js/options.js') }}></script>




@stop
