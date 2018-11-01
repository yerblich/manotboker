
<style>
        tr:nth-child(even) {background: #CCC}
      tr:nth-child(odd) {background: #FFF}
      table, th, td {
         border: 1px solid black;
      
      }
      .img{
        width:2%;
        height:2%;
      }
      .td-right td{
        text-align:right;
        border:none;
      }
      .clientInfo td{
        width: 50%;
        
      }
  
      </style>
<div class=""></div>

<div class=" heb wrapper">
<div class="">
       <h1 class=""><strong> משווק מנות בוקר</strong>
     
         
     
      @if(!$data['pdf']->orders == '')
        <div class="table-responsive row centerTable col-12 ">
            <table class="clientInfo td-right heb table  table" id="dataTable" width="100%" cellspacing="0">
              <tr>
                  <td> : דואר</td>
              <td>   {{$data['pdf']->client->name}}: שם לקוח</td>  
              </tr> 
              <tr>
                  <td> : כתובת</td> 
                  <td> {{$data['pdf']->client->id}}: מספר לקוח</td>  
                  
                  </tr> 
              <tr>
                  <td>: טלפון</td>
                  <td >{{$data['pdf']->to_date}}   -<strong>  תאריך:</strong>
                     {{$data['pdf']->from_date}}<strong> - ל  </strong> </td>
                     
                </tr>
              </table><br>
              <br>
        <table class=" heb table table-bordered table" id="dataTable" width="100%" cellspacing="0">
          <thead class="thead-light">
          
            <tr class="">
                <th>סה"כ לתשלום</th>
                <th>  <img  class="img" src="{{public_path('images/shekel.png')}}" >  מחיר</th>
                <th>סה"כ</th>
                <th>חזרות</th>
                <th>כמות</th>
                
              <th class="" >מוצר</th>
              
              
              
              
              
            
      </tr>
      </thead>
      
      <tbody>
      
        
         
              @foreach($data['pdf']->invoiceInfo as  $name => $infoArray)

        <tr>
             



            
             
              
             
                <td><img  class="img" src="{{public_path('images/shekel.png')}}" > {{$infoArray->totalToPayForProduct }}</td>
                
                <td>
                    @foreach($infoArray->price as $price => $amount)
                   
                    @if(count((array)$infoArray->price) > 1)
                   | {{$amount}} - <img  class="img" src="{{public_path('images/shekel.png')}}" > {{$price}} |
                   @else 
                   <img  class="img" src="{{public_path('images/shekel.png')}}" >{{$price}}
                   @endif
                    @endforeach
                  </td>
               
                <td>{{$infoArray->totalSold}}</td>
                <td>{{$infoArray->returns}}</td>
                <td>{{$infoArray->ordered}}</td>
                
                <td>{{$name}}</td>           
            </tr>
            
              
               
             
              @endforeach
            
      
       
       
      </tbody>
      <tfoot>
            
  {{-- <tr class="table-danger"> 
      <th >סה"כ</th>
     <td></td>
     <td></td>
     <td></td>
     <td></td>
    <td>&#8362;{{$data['pdf']->totalToPay}} </td>
        
  
    
        
  </tr>
  
  @if($data['pdf']->client->debt - $data['pdf']->totalToPay < 0 )
  <tr class="table-success">
      <th >    זכות </th>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
  <td>&#8362;{{$data['pdf']->client->debt - $data['pdf']->totalToPay}}</td>
  </tr>
  @endif
          <tr>
              <th>מוצר</th>
             <th>כמות</th>
              <th>חזרות</th>
              <th>סה"כ</th>
              <th>מחיר</th>
              <th>סה"כ לתשלום</th>
            
          </tr> --}}
          
      </tfoot>
      </table>
      </div> 
      @else 
      <br>
      No orders
      @endif
    </div>