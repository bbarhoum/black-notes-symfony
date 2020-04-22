<template>
  <div class="add-items d-flex">
    <input v-model="todo.title" placeholder="What do you need to do?" class="col-8 form-control"/>
    <input v-model="todo.dueDate" placeholder="When to do this?" class="col form-control flatpickr-input"/>

    <button class="add btn btn-primary font-weight-bold todo-list-add-btn" type="button" @click="addTodo">Add</button>
  </div>
</template>

<script>
  export default {
    name: "TodoForm",
    props: {
        todo: {
          title: '',
          dueDate: null
        }
    },
    methods: {
      addTodo() {
        if (this.todo.title !== '') {
          this.$emit('addTodo', this.todo)
        }
      }
    }
  }
</script>
