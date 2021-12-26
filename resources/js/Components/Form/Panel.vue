<template>
    <div>
        <slot/>
        <div class="space-x-4 py-3">
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
