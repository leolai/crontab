<table class="table table-bordered table-responsive">
	<thead>
		<tr>
			<td width="5%">No.</td>
			<td width="15%">岗位名称</td>
			<td width="10%">公司名称</td>
			<td width="5%">工作地点</td>
			<td width="20%">要求</td>
			<td width="40%">岗位描述</td>
			<td width="5%">发布时间</td>
		</tr>
	</thead>
	<tbody>
	@if (count($data) > 0)
	
		@foreach ($data as $index => $one)
		    <tr>
		    	<td>{{ $index }}</td>
		    	<td><a href="{!! $one->url !!}" target="_blank">{{ $one->title }}</a></td>
		    	<td><a href="{!! $one->remark1 !!}" target="_blank">{{ $one->remark2 }}</a></td>
		    	<td>{{ $one->remark4 }}</td>
		    	<td>{{ $one->content }}</td>
		    	<td>{{ $one->desc }}</td>
		    	<td>{{ $one->remark4 }}</td>
		    </tr>
		@endforeach
		
	@endif
	</tbody>
</table>