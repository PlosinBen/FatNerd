<template>
    <div class="flex flex-col h-screen">
        <section class="bg-white border-b shadow select-none">
            <header class="container mx-auto flex px-4 justify-between items-center">
                <InertiaLink href="/" class="text-4xl text-white font-bold">
                    <h1>
                        <img class="h-16" src="https://www.lovelion.me/logo.png" alt="LoveLion">
                    </h1>
                </InertiaLink>
            </header>
        </section>
        <section class="flex-grow mx-auto flex flex-col px-4 py-3">
            <slot></slot>
        </section>
        <Footer />
    </div>
</template>

<script>
import Footer from "@/Components/LayoutPartial/Footer";

export default {
    name: "Blank",
    components: {
        Footer
    }
}
</script>

<style scoped>

</style>
