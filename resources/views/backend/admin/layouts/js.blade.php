<script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>

<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        if (document.getElementById('backBtn')) {
            document.getElementById('backBtn').onclick = function () {
                window.history.back();
            };
        }

        $.extend(true, $.fn.dataTable.defaults, {
            dom: 'Bfrtip',
            buttons: [
                {
                    text: '<i class="fa-solid fa-sync"></i>',
                    className: 'btn btn-sm btn-dark m-0',
                    action: function (e, dt, node, config) {
                        dt.ajax.reload(); // Reload the table data
                    }
                }
            ],
            processing: true,
            serverSide: true,
            responsive: true,
            scrollX: true,
        });
    });
</script>

<script src="{{ asset('datatable/js/datatables.min.js') }}"></script>

