<template>
  <div class="row container d-flex justify-content-center">
    <div class="col-lg-12">
      <div class="card px-3">
        <div class="card-body">
          <h4 class="card-title">Awesome Todo list</h4>

          <TodoForm :todo="newTodo" @addTodo="addTodo"/>

          <div class="list-wrapper">
            <ul class="d-flex flex-column-reverse todo-list">
              <li v-for="todo in todos">
                <TodoItem :todo="todo"/>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import TodoForm from "./TodoForm";
  import TodoItem from "./TodoItem";
  import axios from "axios";

  export default {
    name: "TodoList",
    components: {TodoItem, TodoForm},
    data() {
      return {
        todos: [],
        newTodo: {
          title: '',
          dueDate: null
        },
        token: ''
      }
    },
    mounted() {
      this.getToken().then(() => this.loadTodos())
    },
    methods: {
      getToken() {
        return axios.get("http://www.symfony-docker-dev.com/authentication_token")
          .then(result => {
            this.token = result.data.token
          })
      },
      loadTodos() {
        let config = {
          headers: {
            'Authorization': 'Bearer '+this.token
          }
        }
        axios.get("http://www.symfony-docker-dev.com/api/todos?page=1", config)
          .then(result => {
            this.todos = result.data['hydra:member']
          })
      },
      addTodo(todo) {
        let config = {
          headers: {
            'Authorization': 'Bearer '+this.token
          }
        }
        axios.post("http://www.symfony-docker-dev.com/api/todos", todo, config)
          .then(result => {
            this.loadTodos()
            this.newTodo = {
              title: '',
              dueDate: null
            }
          })
      }
    }
  }
</script>

<style scoped>

</style>
