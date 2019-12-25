// any CSS you require will output into a single css file (app.csss in this case)
import '../css/app.scss'

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
import 'jquery'
import 'bootstrap'

import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import flatpickr from 'flatpickr'
import 'datatables.net-bs4'
import 'bootstrap-tagsinput'
import Bloodhound from "bloodhound-js";
require('typeahead.js')

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

  let tags = $('#post_tags')
  if (tags.length) {
    let source = new Bloodhound({
      local: tags.data('tags'),
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      datumTokenizer: Bloodhound.tokenizers.whitespace
    });
    source.initialize();

    tags.tagsinput({
      trimValue: true,
      focusClass: 'focus',
      typeaheadjs: {
        name: 'tags',
        source: source.ttAdapter()
      }
    });
  }
})