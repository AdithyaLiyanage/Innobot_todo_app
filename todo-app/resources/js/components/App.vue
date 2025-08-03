<template>
    <div class="todo-container">
        <h2>Todo List</h2>
        <input
            v-model="newTask"
            @keyup.enter="addTask"
            placeholder="New Task"
            class="task-input"
        />
        <ul class="task-list">
            <li v-for="task in tasks" :key="task.id" class="task-item">
                <span v-if="!task.editing" :class="['task-title-text', task.completed ? 'completed' : '']">
                    {{ task.title }}
                </span>
                <input v-else v-model="task.title" class="task-title-edit" />
                <div class="task-actions">
                    <button @click="toggleComplete(task)" class="complete-btn">
                        {{ task.completed ? "completed" : "Mark Complete" }}
                    </button>
                    <button
                        v-if="!task.editing"
                        @click="task.editing = true"
                        class="edit-btn"
                    >
                        Edit
                    </button>
                    <button v-else @click="confirmEdit(task)" class="save-btn">
                        Save
                    </button>
                    <button @click="deleteTask(task.id)" class="delete-btn">
                        Delete
                    </button>
                </div>
            </li>
        </ul>
        <p v-if="error" class="error-msg">{{ error }}</p>
    </div>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            tasks: [],
            newTask: "",
            error: "",
        };
    },
    methods: {
        loadTasks() {
            axios.get("/api/tasks").then((res) => {
                this.tasks = res.data.map((task) => ({
                    ...task,
                    editing: false,
                }));
            });
        },
        addTask() {
            axios
                .post("/api/tasks", { title: this.newTask })
                .then((res) => {
                    this.tasks.push({ ...res.data, editing: false });
                    this.newTask = "";
                })
                .catch(() => (this.error = "error add task"));
        },
        confirmEdit(task) {
            axios
                .put(`/api/tasks/${task.id}`, {
                    title: task.title,
                    completed: task.completed,
                })
                .then(() => {
                    task.editing = false;
                })
                .catch(() => (this.error = "Error update"));
        },
        toggleComplete(task) {
            task.completed = !task.completed;
            this.confirmEdit(task);
        },
        deleteTask(id) {
            axios
                .delete(`/api/tasks/${id}`)
                .then(() => (this.tasks = this.tasks.filter((t) => t.id !== id)))
                .catch(() => (this.error = "Error deleting tasks"));
        },
    },
    mounted() {
        this.loadTasks();
    },
};
</script>

<style  scoped>
.todo-container{
    max-width: 500px;
    margin: 40px auto;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 24px;
}
h2{
    text-align: center;
    color: black;
    margin-bottom: 20px;

}

.task-input{
    width: 100%;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid white;
    font-size: 16px;
    margin-bottom: 20px;
}
.task-list{
    background: white;
    margin-bottom: 10px;
    border-radius: 6px;
    padding: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.task-title-text{
    display: block;
    font-size: 16px;
    color: black;
    margin-bottom: 10px;
}
.task-title-text.completed{
    text-decoration: line-through;
    color: #999;
}

.task-title-edit{
    width: 100%;
    font-size: 16px;
    padding: 6px 8px;
    border-radius: 4px;
    border: 1px solid #aaa;
    outline: none;
    margin-bottom: 10px;
}

.task-actions{
    display: flex;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 6px;
}

.edit-btn,
.save-btn,
.delete-btn,
.complete-btn{
    border: none;
    padding: 6px 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.2s;
}
.complete-btn{
    background: orange;
    color: black;
}
.complete-btn:hover{
    background: rgb(148, 96, 1);
    
}
.edit-btn{
    background: blue;
    color: white;
}
.edit-btn:hover{
    background: rgb(1, 43, 82);
    
}
.save-btn{
    background: green;
    color: white;
}
.save-btn:hover{
    background: rgb(1, 70, 1);
    
}
.delete-btn{
    background: red;
    color: white;
}
.delete-btn:hover{
    background: rgb(116, 0, 0);
    
}
.error-msg{
    color: red;
    text-align: center;
    margin-top: 12px;
    font-weight: bold;
}
</style>