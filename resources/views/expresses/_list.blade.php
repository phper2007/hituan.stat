<div class="panel-body">
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>收件人</th>
            <th>商品名称</th>
            <th>快递公司</th>
            <th>快递号</th>
            <th>快递状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($expressList as $item)
            <tr>
                <td>{{ $item['contact_name'] }} / {{ $item['contact_phone'] }}</td>
                <td>{{ $item['product_name'] }}</td>
                <td>{{ $item['company_name'] }}</td>
                <td>{{ $item['express_no'] }}</td>
                <td>
                    @if($item['status'] == 'signed')
                        <span class="text-success">{{ $expressStatusDict[$item['status']] }}</span>
                    @else
                        <span class="text-danger">{{ $expressStatusDict[$item['status']] }}</span>
                    @endif
                    @if($item['is_msg'])
                        <i class="icon-bell-alt"></i>
                @endif
                <td>
                    <a href="{{ $item['website_url'] }}" target="_blank" class="btn btn-info">打开新窗口</a>
                    <button type="button" class="viewExpress" href="{{ $item['website_url'] }}">查看详细</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>