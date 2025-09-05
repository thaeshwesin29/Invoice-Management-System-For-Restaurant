<script>
    $(document).ready(function(){
        var successSession = "{{ session('success') }}";
        if (successSession) {
            toastr.success(successSession);
        }

        var errorSession = "{{ session('error') }}";
        if (errorSession) {
            toastr.error(errorSession);
        }
    });
</script>
