<template>
    <draggable v-model="mutableCourses" class="dragArea" :options="{group:'people'}" @start="drag=true" @end="drag=false">
        <div v-for="course in mutableCourses" class="course-list">
            <span v-for="description in course">{{ description }} </span>
        </div>
    </draggable>
</template>

<script>
    import draggable from "vuedraggable";
    export default {
        name: "DragonDrop",
        components: {draggable},
        props: {courses: Array},
        data: function () {
            return {
                mutableCourses: this.courses,
            }
        }
    }
</script>

<style scoped>

</style>