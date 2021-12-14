<template>
    <nav class="px-2 text-gray-600 space-x-3">
        <InertiaLink href="/">
            <i class="fas fa-home"></i>
        </InertiaLink>
        <i class="fas fa-angle-right"></i>
        <InertiaLink href="/">
            投資
        </InertiaLink>
        <i class="fas fa-angle-right"></i>
        <span class="text-gray-400">歷史權益</span>
    </nav>
</template>

<script>
export default {
    name: "Breadcrumbs",
}
</script>

<style scoped>

</style>
