import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// ---- Vue -----

// import { createApp } from 'vue';
// import ProductList from '@/components/ProductList.vue';

// const app = createApp({});
// app.component('product-list', ProductList);
// app.mount('#app');

// --------


// import '@coreui/coreui/dist/js/coreui.bundle.min.js';

// Import all of CoreUI's JS
import * as coreui from '@coreui/coreui'

window.coreui = coreui

import { Tooltip, Toast, Popover } from '@coreui/coreui'

// import $ from 'jquery';
// import 'datatables.net-dt';

import Swal from 'sweetalert2';
window.Swal = Swal;

const CustomAlert = Swal.mixin({
    text: "Are you sure to delete?",
    width: 400,
    showCancelButton: true,
    confirmButtonText: 'Yes',
    confirmButtonColor: '#5856d6',
    cancelButtonText: 'No',
    cancelButtonColor: '#6b7785',
    customClass: {
        popup: 'p-0 card',
        actions: 'mt-1 mb-3 p-0',
        confirmButton: 'py-2',
        cancelButton: 'bg-secondary py-2',
    }
});
window.CustomAlert = CustomAlert;

const ProcessingAlert = Swal.mixin({
    text: "Processing..., Please wait!",
    width: 400,
    customClass: {
        popup: 'p-0 card',
        actions: 'mt-1 mb-3 p-0',
        confirmButton: 'py-2',
        cancelButton: 'bg-secondary py-2',
    },
    timerProgressBar: true,
    didOpen: () => {
        Swal.showLoading();
    }
});
window.ProcessingAlert = ProcessingAlert;

const DeleteAlert = Swal.mixin({
    text: "Are you sure to delete?",
    width: 350,
    showCancelButton: true,
    confirmButtonColor: '#e55353',
    cancelButtonColor: '#6b7785',
    confirmButtonText: 'Delete',
    customClass: {
        popup: 'p-0 card',
        actions: 'mt-1 mb-3 p-0',
        confirmButton: 'py-2',
        cancelButton: 'bg-secondary py-2',
    }
});
window.DeleteAlert = DeleteAlert;

import toastr from 'toastr';
window.toastr = toastr;

toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-bottom-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

