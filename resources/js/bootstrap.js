import _ from 'lodash';
window._ = _;

import * as Popper from '@popperjs/core';
window.Popper = Popper;

import '../sass/app.scss';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import $ from 'jquery';
window.$ = window.jQuery = $;

import DataTable from "datatables.net-bs5";
window.DataTable = DataTable;

import Swal from "sweetalert2";
window.Swal = Swal;
import 'sweetalert2/src/sweetalert2.scss';

import moment from 'moment';
window.moment = moment;

import select2 from 'select2';
import 'select2/dist/css/select2.css';
select2(window, $);

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';