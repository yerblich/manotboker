@extends('layouts.main')

@section('content')

<h1> {{$date}}</h1>

  <table class="table clientInfo td-right table-bordered" id="dataTable" width="100%" cellspacing="0" autosize="1">
    <thead>
      <tr>
          <td> פעולה</td>
          <td>שעות</td>
          <td>רְשׁוּמָה #</td>


</tr>
</thead>

<tbody>


@foreach ($array as $id => $time)
  <tr>

  <td style = "width :20px;">
    <a href="{{url('clocker/delete', $id)}}">
    <button class="btn btn-danger">הסר</button>
  </a>
  </td>
  <td>{{ $time}}</td>

      <td>{{$id}}</td>





    </tr>
@endforeach







</tbody>
<tfoot>


</tfoot>
</table>

@endsection
