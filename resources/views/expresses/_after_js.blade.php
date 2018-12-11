
@section('scriptsAfterJs')
    <script>
        $('.viewExpress').click(function () {
            var src = $(this).attr('href');
            $('#myModal').modal('show');
            $('#myModal iframe').attr('src', src);
        });

        $('#myModal button').click(function () {
            $('#myModal iframe').removeAttr('src');
        });
    </script>
@endsection