<template>
    <div class="sm:flex pt-3 pb-4 items-center">
        <div
            v-if="label"
            class="flex-shrink-0 py-0.5 sm:py-1.5 px-2 sm:w-32"
            :class="labelClass"
        >
            <span v-text="label"></span>
            <slot name="label"/>
        </div>
        <div class="flex-grow py-0.5 sm:py-1.5 px-2">
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
        labelClass: {
            default: () => ''
        },
        contentClass: {
            default: () => ''
        },
        error: String,
        rwd: String,
    },
    setup({rwd}) {
        const rwdClass = {}
    }
}
</script>

<style scoped>

</style>
