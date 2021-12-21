<template>
    <div>
        <div>
            <slot name="header"/>
        </div>
        <slot/>
        <div class="space-x-4 py-3">
            <slot name="footer"/>
        </div>
    </div>
</template>

<script>
export default {
    name: "Panel"
}
</script>

<style scoped>

</style>
