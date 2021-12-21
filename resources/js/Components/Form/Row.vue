<template>
    <div class="sm:flex pt-3 pb-4">
        <div v-if="label" class="flex-shrink-0 py-1.5 px-2 w-32">
            <span v-text="label"></span>
        </div>
        <div class="flex-grow">
            <slot/>
            <div v-if="error" class="text-red-500" v-text="error"></div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Row",
    props: {
        label: String,
        error: String
    }
}
</script>

<style scoped>

</style>
