// any CSS you require will output into a single css file (app.csss in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
let $  = require('jquery');
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import flatpickr from 'flatpickr'
require('bootstrap');
require('datatables.net-bs4')

$(document).ready(function() {
  flatpickr('#todo_dueDate', {
    enableTime: true,
  })

  $('#post-table').DataTable()

  if (document.querySelector('#post_content')) {
    ClassicEditor.create(document.querySelector('#post_content')).catch(error => {
      console.error(error);
    })
  }
})