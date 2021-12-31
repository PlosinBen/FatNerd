<template>
    <vue-final-modal
        v-slot="{ params, close }"
        v-bind="$attrs"
        classes="flex justify-center items-center"
        content-class="relative flex flex-col max-h-full mx-4 p-4 border dark:border-gray-800 rounded bg-white dark:bg-gray-900"
        :click-to-close="false"
    >
        <span class="mr-8 text-2xl font-bold">
            <slot name="title">
                {{ title }}
            </slot>
        </span>
        <div class="flex-grow overflow-y-auto">
            <slot :params="params"></slot>
        </div>
        <div v-if="false" class="flex-shrink-0 flex justify-center items-center pt-4 space-x-4">
            <button class="btn-blue" @click="$emit('confirm', close)">confirm</button>
            <button class="btn-red" @click="$emit('cancel', close)">cancel</button>
        </div>
        <button class="absolute top-0 right-0 mt-0.5 mr-2" @click="close">
            <i class="fas fa-times"></i>
        </button>
    </vue-final-modal>
</template>

<script>
export default {
    name: "Modal",
    props: {
        title: String
    }
}
</script>

<style scoped>

</style>
