<template>
    <div v-if="tasks.length === 0"><span class="spinner-border spinner-border-sm" role="status"></span> Loading...</div>
    <div v-for="task in tasks" :key="task.id" :id="`task-item-${task.id}`" @click="markAsCompleted"
        class="task-item border p-0 my-3 rounded-0 clearfix"
        :class="[task.status === 'completed' ? 'completed border-success alert alert-success text-decoration-line-through' : 'incompleted']">
        <span class="task-item-text">{{ task.text }}</span> <span class="remove btn btn-danger float-end rounded-0"
            :id="`remove-task-item-${task.id}`" @click.stop="removeTask">&times;</span>
    </div>
</template>

<script>
import http from '../http.js'

export default {
    name: 'ListTasks',
    emits: ["updateTask"],
    props: {
        tasks: Object
    },
    methods: {
        removeTask(event) {
            const element = event.target;
            const id = element.getAttribute('id').match(/remove-task-item-(\d+)/)[1];
            http.delete(`api/tasks/${id}`)
                .then(({ data }) => {
                    console.log(data)
                    element.closest(".task-item").remove();
                })
                .catch(error => console.log(error));
        },
        markAsCompleted(event) {
            const parent = event.target.closest('.task-item');
            const id = parent.getAttribute('id').match(/task-item-(\d+)/)[1];
            const newStatus = /\bsuccess\b/.test(parent.className) ? 'incompleted' : 'completed';

            http.put(`api/tasks/${id}`, { status: newStatus })
                .then(() => {
                    const classes = "border-success alert alert-success text-decoration-line-through".split(" ");
                    for (let index in classes) {
                        parent.classList.toggle(classes[index]);
                    }
                })
                .catch(error => console.log(error))
                .finally(() => this.$emit('updateTask', (newStatus === 'completed') ? 1 : -1));
        }
    }
}
</script>

<style scoped>
.task-item {
    cursor: pointer;
}

.task-item-text {
    position: relative;
    top: 5px;
    left: 10px;
}
</style>