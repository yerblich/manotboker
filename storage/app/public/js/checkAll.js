$("#checkAll").change(function () {
    $("input:checkbox").prop('checked', this.checked);
});
