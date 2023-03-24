<template>
    <form>
        <h1 class="my-3">Tasks <span class="text-secondary">({{ completedTasks }})</span></h1>
        <div class="input-group mb-3">
            <input type="text" ref="input" class="form-control rounded-0 shadow-none" placeholder="New task"
                id="input-add-task" />
            <button ref="button" class="btn btn-success rounded-0" type="button" id="input-add-task"
                @click="addNewTask"><span v-if="loading" class="spinner-border text-light spinner-border-sm"
                    role="status"></span><span v-else>+ Add</span></button>
        </div>
        <list-tasks :tasks="tasks" @updateTask="updateCompletedTasks"></list-tasks>
    </form>
</template>

<script>
import ListTasks from './ListTasks.vue'
import http from '../http';

export default {
    name: 'FormAddTask',
    components: {
        ListTasks
    },
    emits: ["updateTask"],
    data() {
        return {
            tasks: [],
            loading: false,
            completedTasks: 0
        };
    },
    created() {
        http.get('api/tasks')
            .then(response => {
                this.tasks = response.data;
                this.completedTasks = this.tasks.filter(tasks => tasks.status === 'completed').length;
            })
            .catch(error => console.log(error));
    },
    methods: {
        addNewTask() {
            let text = this.$refs.input.value.trim();
            if (text.length === 0) {
                alert('The task should have a message.');
                return;
            }
            this.loading = true;
            http.post('api/tasks', { text: text })
                .then(response => {
                    this.tasks.push(response.data);
                    this.$refs.input.value = '';
                })
                .catch(error => console.log(error))
                .finally(() => this.loading = false);
        },
        updateCompletedTasks(value) {
            this.completedTasks += value;
        }
    }
}
</script>
