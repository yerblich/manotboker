@extends('layouts.main')

@section('content')


  <table class="table clientInfo td-right table-bordered" id="dataTable" width="100%" cellspacing="0" autosize="1">
    <thead>
      <tr>
          <td>&#8362; </td>
          <td>שעות</td>
          <td>תאריך</td>


</tr>
</thead>

<tbody>

@foreach ($array as $date => $time)
  <tr>

    <td>{{round(array_sum($time) * ($wage/3600), 2)}}</td>
  <td>{{ gmdate("H:i:s",array_sum($time))}}</td>

      <td>{{$date}}</td>





    </tr>
@endforeach







</tbody>
<tfoot>

  <tr class="bg-success">
    <td>{{round($totalTime * ($wage/3600), 2)}}</td>
    <td>{{gmdate("H:i:s",$totalTime)}}</td>
    <td>סה"כ</td>
</tr>
</tfoot>
</table>

@endsection
