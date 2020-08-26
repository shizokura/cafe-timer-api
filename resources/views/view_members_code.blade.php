<table style="width:100%">
  <tr>
    <th>Pin Code</th>
    <th>Activation Code</th>
    <th>Date Claimed</th>
  </tr>
  @foreach ($get_code as $gc)
    <tr>
      <td>{{$gc->pin_code}}</td>    
      <td>{{$gc->activation_code}}</td>    
      <td>{{date('F j, Y, g:i a', strtotime($gc->date_claimed)) }}</td>    
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