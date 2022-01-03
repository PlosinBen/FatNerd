<template>
    <div class="flex flex-col h-screen">
        <section class="bg-white border-b shadow select-none">
            <Header/>
        </section>
        <section class="flex-grow mx-auto flex flex-col px-4 py-3">
            <slot></slot>
        </section>
        <Footer/>
    </div>
</template>

<script>
import Header from "@/Components/LayoutPartial/Header"
import Footer from "@/Components/LayoutPartial/Footer"

export default {
    name: "Blank",
    components: {
        Header,
        Footer
    }
}
</script>

<style scoped>

</style>
