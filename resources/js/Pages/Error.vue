<template>
    <div class="flex-grow"></div>
    <div class="border rounded bg-white">
        <div class="text-center py-2 px-4 text-2xl bg-gray-200">
            <h4>錯誤</h4>
        </div>
        <div class="py-2 px-2.5">
            <p class="leading-relaxed" v-text="message"></p>
        </div>
    </div>
    <div class="flex-grow-3"></div>
</template>

<script>
import Blank from "@/Layouts/Blank"

export default {
    layout: Blank,
    props: {
        message: String,
        redirectTo: String,
        reciprocal: Number
    }
}
</script>

<style scoped>

</style>
