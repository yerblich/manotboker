$(".checkAll").change(function () {
    $(this).closest('div').find("input:checkbox").prop('checked', this.checked);
});
