<div class="container">
  <h1>Current Online : {{$count}}<h1>
  <table style="width:100%; font-size:30px;">
    <tr>
      <th>Username</th>
      <th>Time Remaining</th>
      <th>Points Remaining</th>
      <th>View Topup Codes</th>
      <th>View Claimed Points</th>
    </tr>
    @foreach ($get_member as $gm)
      <tr>
        <td>{{$gm->member_un}}</td>
        <td>{{date('H', mktime(0,$gm->remaining_minutes)) != 0 ? date('H', mktime(0,$gm->remaining_minutes)) . ' hours' : ''}} {{date('i', mktime(0,$gm->remaining_minutes))}} minutes </td>
        <td>{{number_format($gm->points,2)}}</td>
        <td><a href="/view_members_code?member_id={{$gm->member_id}}" target="_blank">View</a></td>
        <td><a href="/view_member_points?member_id={{$gm->member_id}}" target="_blank">View</a></td>
      </tr>
    @endforeach
  </table>

  </br>
  </br>
  </br>
  </br>
  </br>
  </br>

  <h1>Duplicate </h1>
  <table style="width:100%; float:left">
    <tr>
      <th>Pin Code</th>
      <th>Activation Code</th>
      <th>Times Used</th>
      <th>View</th>
    </tr>
    @foreach ($get_duplicate_code_viewed as $gdc)
      <tr>
        <td>{{$gdc->pin_code}}</td>    
        <td>{{$gdc->activation_code}}</td>    
        <td>{{$gdc->total}}</td>    
        <td><h2><a href="/view_duplicate_code?code_id={{$gdc->code_id}}" target="_blank">View</a><h2></td>   
      </tr>
    @endforeach
  </table>

  </br>
  </br>
  </br>
  </br>
  </br>
  </br>
  </br>
  </br>
  </br>
  </br>
  </br>
  </br>

  <table style="width:49%; float:left">
    <tr>
      <th>Pin Code</th>
      <th>Activation Code</th>
      <th>Times Used</th>
      <th>View</th>
    </tr>
    @foreach ($get_duplicate_code as $gdc)
      <tr>
        <td>{{$gdc->pin_code}}</td>    
        <td>{{$gdc->activation_code}}</td>    
        <td>{{$gdc->total}}</td>    
        <td><a href="/view_duplicate_code?code_id={{$gdc->code_id}}" target="_blank">View</a></td>   
      </tr>
    @endforeach
  </table>



  <table style="width:49%; float:right">
    <tr>
      <th>Username</th>
      <th>Amount</th>
      <th>Times Used</th>
      <th>View</th>
    </tr>
    @foreach ($get_duplicate_points as $gdp)
      <tr>
        <td>{{$gdp->member_un}}</td>      
        <td>{{$gdp->amount}}</td>    
        <td>{{$gdp->total}}</td>    
        <td><a href="/view_member_points?member_id={{$gdp->member_id}}" target="_blank">View</a></td>   
      </tr>
    @endforeach
  </table>
</div>
<style type="text/css">
table, th, td 
{
  border: 1px solid black;
  border-collapse: collapse;
  text-align: left;
}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
  
setInterval(myMethod, 3000);

function myMethod( )
{
  $(".container").load(location.href + " .container");
   // alert(1);
}
</script>