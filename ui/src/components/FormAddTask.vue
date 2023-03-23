<template>
    <form>
        <h1 class="my-3">Tasks <span class="text-muted">({{ completedTasks }})</span></h1>
        <div class="input-group mb-3">
            <input type="text" ref="input" class="form-control rounded-0" placeholder="New task" id="input-add-task" />
            <button ref="button" class="btn btn-success rounded-0" type="button" id="input-add-task"
                @click="addNewTask"><span v-if="loading" class="spinner-border text-light spinner-border-sm"
                    role="status"></span><span v-else>+ Add</span></button>
        </div>
    </form>
    <div v-if="tasks.length === 0"><span class="spinner-border spinner-border-sm" role="status"></span> Loading...</div>
    <div v-for="task in tasks" :key="task.id" :id="`task-item-${task.id}`"
        class="task-item my-3 border p-0 mb-2 rounded-0 text-justify clearfix"
        :class="[task.status === 'completed' ? 'completed border-success alert alert-success text-decoration-line-through' : 'incompleted']">
        <span class="task-item-text" style="margin-left: 10px; width: 100%;" @click.prevent="markAsCompleted">{{ task.text }}</span> <span
            class="remove btn btn-danger float-end rounded-0" :id="`remove-task-item-${task.id}`"
            @click.prevent="removeTask">&times;</span></div>
</template>

<script>
import http from '../http';

export default {
    name: 'FormAddTask',
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
        removeTask(event) {
            const id = event.target.getAttribute('id').match(/remove-task-item-(\d+)/)[1];
            http.delete(`api/tasks/${id}`)
                .then(() => {
                    event.target.closest(".task-item").remove();
                })
                .catch(error => console.log(error));
        },
        markAsCompleted(event) {
            const parent = event.target.closest('.task-item');
            const id = parent.getAttribute('id').match(/task-item-(\d+)/)[1];
            const newStatus = /\bsuccess\b/.test(parent.className) ? 'incompleted' : 'completed';

            http.put(`api/tasks/${id}`, { status: newStatus })
                .then(({ data }) => {
                    const classes = "border-success alert alert-success text-decoration-line-through".split(" ");
                    for (let index in classes) {
                        parent.classList.toggle(classes[index]);
                    }

                    if (newStatus === 'completed') {
                        this.completedTasks += 1;
                    } else {
                        this.completedTasks -= 1;
                    }

                    console.log(data);
                    this.tasks.filter(task => {
                        task.id === id;
                    }).forEach((task) => {
                        task.status = newStatus;
                    })
                })
                .catch(error => console.log(error));
        }
    }
}
</script>

<style scoped>
.task-item-text {
    cursor: pointer;
}
</style>