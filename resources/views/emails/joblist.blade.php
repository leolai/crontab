<style type="text/css">
<!--
table{
	border-collapse:collapse;
	table-layout:fixed;
	word-break:break-all;
}
td{
	border:solid 1px #ccc;
}
-->
</style>

<table width="100%">
	<thead>
		<tr>
			<td width="5%">No.</td>
			<td width="30%">岗位名称</td>
			<td width="20%">公司名称</td>
			<td width="10%">工作地点</td>
			<td width="20%">薪资</td>
			<td width="15%">发布时间</td>
		</tr>
	</thead>
	<tbody>
	@if (count($data) > 0)
	
		@foreach ($data as $index => $one)
		    <tr>
		    	<td>{{ $index }}</td>
		    	<td><a href="{!! $one[0] !!}" target="_blank">{{ $one[1] }}</a></td>
		    	<td><a href="{!! $one[2] !!}" target="_blank">{{ $one[3] }}</a></td>
		    	<td>{{ $one[4] }}</td>
		    	<td>{{ $one[6] }}</td>
		    	<td>{{ $one[5] }}</td>
		    </tr>
		@endforeach
		
	@endif
	</tbody>
</table>