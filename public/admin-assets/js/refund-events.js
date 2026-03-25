var table = null;
let ddt = {
    id: 0,
    policy_number: 1,
    refund_state: 2,
    spayment_id: 3,
    cpw: 4,
    update_price: 5,
    first_name: 6,
    last_name: 7,
    email: 8,
    address: 9,
    updated_at: 10,
    is_blocked: 11,
};

if (jQuery('#myTable').length) {
    table = $('#myTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/admin/refund-events/data',
        bSortCellsTop: true,
        rowId: '0',
        dom: '<B<"datatable_dom_pull_left"f><t>lp>',
        initComplete: function () {
            setUpSearchFilters();
        },
        lengthMenu: [[50, 100, 200, 500, -1], [50, 100, 200, 500, 'All']],
        columns: [
            {
                data: 0,
                searchable: false,
                orderable: false,
                className: 'actions',
                render: function (data, type, row) {
                    if (parseInt(row[ddt.is_blocked] || 0, 10) === 1) {
                        return '<span class="p-2 badge badge-dark">Blocked</span>';
                    }
                    return `<button class="btn btn-sm btn-danger" onclick="blacklistCustomer(${data})"><i class="fa fa-ban"></i> Block</button>`;
                },
            },
            { data: 6, name: 'quotes.first_name', defaultContent: '' },
            { data: 7, name: 'quotes.last_name', defaultContent: '' },
            { data: 8, name: 'users.email', defaultContent: '' },
            {
                data: 4,
                name: 'cpw',
                render: function (value, type, row) {
                    // var base = parseFloat(value || 0);
                    var updatePrice = parseFloat(row[ddt.update_price] || 0);
                    var total = updatePrice.toFixed(2);
                    return '$' + total;
                }
            },
            {
                data: 10,
                name: 'quotes.updated_at',
                render: function (value) {
                    if (!value) return '';
                    var date = new Date(value);
                    if (isNaN(date.getTime())) {
                        return value;
                    }
                    return date.toLocaleDateString();
                }
            },
        ],
        order: [[5, 'desc']],
        buttons: ['csv'],
    });
}

function setUpSearchFilters() {
    $('#myTable thead tr:eq(1) th').each(function (i) {
        if ($(this).hasClass('sch')) {
            $(this).html('<input type="text" placeholder="search"/>');
            $('input', this).on('keyup change', function () {
                if (table.column(i).search() !== this.value) {
                    table.column(i).search(this.value).draw();
                }
            });
        }
    });
}

function blacklistCustomer(id) {
    $('#refund_blacklist_quote_id').val(id);
    $('#block_email').prop('checked', true);
    $('#block_address').prop('checked', false);
    $('#refund_blacklist_modal').modal('show');
}

function submitBlacklistSelection() {
    const id = $('#refund_blacklist_quote_id').val();
    const blockEmail = $('#block_email').is(':checked');
    const blockAddress = $('#block_address').is(':checked');

    if (!blockEmail && !blockAddress) {
        toastr.warning('Please select at least one option.');
        return;
    }

    $.ajax({
        type: 'POST',
        url: `/admin/refund-events/${id}/blacklist`,
        data: {
            block_email: blockEmail ? 1 : 0,
            block_address: blockAddress ? 1 : 0,
        },
        success: function (response) {
            toastr.success(response.message || 'Customer blacklisted.');
            $('#refund_blacklist_modal').modal('hide');
            table.ajax.reload();
        },
        error: function (xhr) {
            render_errors(JSON.parse(xhr.responseText), 'toast');
        },
    });
}