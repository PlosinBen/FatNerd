<template>
    <div class="sm:px-1 md:px-3 lg:px-5 pt-2 pb-4">
        <slot/>
        <div v-if="$slots.hasOwnProperty('footer')" class="w-full space-x-4 py-3">
            <slot name="footer"/>
        </div>
    </div>
</template>

<script>
export default {
    name: "Panel",
    props: {
        title: String
    }
}
</script>

<style scoped>

</style>
