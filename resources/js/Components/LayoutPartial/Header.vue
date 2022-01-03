<template>
    <header class="container mx-auto flex px-4 items-center">
        <InertiaLink href="/" class="text-4xl text-white font-bold">
            <h1>
                <img class="h-16" src="/lovelion.png" alt="LoveLion">
            </h1>
        </InertiaLink>

        <slot/>
    </header>
</template>

<script>
export default {
    name: "Header"
}
</script>

<style scoped>

</style>
