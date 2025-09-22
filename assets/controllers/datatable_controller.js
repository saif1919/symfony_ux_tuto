import { Controller } from '@hotwired/stimulus';
import $ from "jquery";

export default class extends Controller {
    connect() {
        this.element.addEventListener('datatables:pre-connect', this._onPreConnect);
        this.element.addEventListener('datatables:connect', this._onConnect);
    }

    disconnect() {
        // You should always remove listeners when the controller is disconnected to avoid side effects
        this.element.removeEventListener('datatables:pre-connect', this._onPreConnect);
        this.element.removeEventListener('datatables:connect', this._onConnect);
    }

    _onPreConnect(event) {
        // The table is not yet created
        // You can access the config that will be passed to "new DataTable()"
        console.log(event.detail.config);

        if ($('#usersTable_wrapper').length) {
            $('#usersTable_wrapper').remove();
        }

        if ($('#countriesDataTable_wrapper').length) {
            $('#countriesDataTable_wrapper').remove();
        }

        // For instance you can define a render callback for a given column
        // event.detail.config.columns[0].render = function (data, type, row, meta) {
        //     return '<a href="' + data + '">Download</a>';
        // }
    }

    _onConnect(event) {
        // The table was just created
        // console.log(event.detail.table); // You can access the table instance using the event details

        // // For instance you can listen to additional events
        // event.detail.table.on('init', (event) => {
        //     /* ... */
        // };
        // event.detail.table.on('draw', (event) => {
        //     /* ... */
        // };
    }
}