/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.csss in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
require('bootstrap');

const $ = require('jquery');

const flatpickr = require('flatpickr')
flatpickr('#todo_dueDate', {
  enableTime: true,
})

const ClassicEditor = require('@ckeditor/ckeditor5-build-classic')
if (document.querySelector('#post_content')) {
  ClassicEditor.create(document.querySelector('#post_content')).catch(error => {
    console.error(error);
  })
}