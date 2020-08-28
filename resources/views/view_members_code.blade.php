<table style="width:100%">
  <tr>
    <th>Pin Code</th>
    <th>Activation Code</th>
    <th>Date Claimed</th>
    <th>Before Adding</th>
    <th>After Adding</th>
  </tr>
  @foreach ($get_code as $gc)
    <tr>
      <td>{{$gc->pin_code}}</td>    
      <td>{{$gc->activation_code}}</td>    
      <td>{{date('F j, Y, g:i a', strtotime(Carbon\Carbon::parse($gc->date_claimed)->addHours(8))) }}</td>    
      <td>{{$gc->before_adding_time}}</td>    
      <td>{{$gc->after_adding_time}}</td>    
    </tr>
  @endforeach
</table>

<style type="text/css">
table, th, td 
{
  border: 1px solid black;
  border-collapse: collapse;
  text-align: left;
}
</style>