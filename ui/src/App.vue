<template>
  <task-toolbar></task-toolbar>
  <form-add-task></form-add-task>
</template>

<script>
import TaskToolbar from './components/TaskToolbar.vue'
import FormAddTask from './components/FormAddTask.vue'

export default {
  name: 'App',
  components: {
    TaskToolbar,
    FormAddTask,
  }
}
</script>