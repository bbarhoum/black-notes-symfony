<template>
  <div class="row">
    <div class="col-12">
      <div class="form-check">
        <label class="form-check-label">
          <input class="checkbox" type="checkbox">
          {{ todo.title }}
          <i class="input-helper"></i>
        </label>
      </div>
      <i class="fa fa-close"></i>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  export default {
    name: "TodoItem",
    props: [
      'todo'
    ]
  }
</script>

<style scoped>

</style>
