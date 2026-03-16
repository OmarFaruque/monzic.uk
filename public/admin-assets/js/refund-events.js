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
    updated_at: 9,
    is_blocked: 10,
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
                data: 9,
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
    if (!confirm('Blacklist this customer email?')) {
        return;
    }

    $.ajax({
        type: 'POST',
        url: `/admin/refund-events/${id}/blacklist`,
        success: function (response) {
            toastr.success(response.message || 'Customer blacklisted.');
            table.ajax.reload();
        },
        error: function (xhr) {
            render_errors(JSON.parse(xhr.responseText), 'toast');
        },
    });
}