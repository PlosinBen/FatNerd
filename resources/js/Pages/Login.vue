<template>
    <div class="flex-grow"></div>
    <div class="border border-b-0 rounded-t py-3 text-center bg-gray-200">
        <h3 class="text-2xl">登入</h3>
    </div>
    <div class="bg-white border rounded-b px-4 py-1.5">
        <FormRow>
            <InertiaLink href="/login/facebook" class="btn-blue bg-blue-600 py-1.5 px-3">
                <i class="fab fa-facebook-f border-r pr-3 mr-2"></i>
                Sign in with Facebook
            </InertiaLink>
        </FormRow>
    </div>
    <div class="flex-grow"></div>
    <div class="flex-grow"></div>
</template>

<script>
import Blank from "@/Layouts/Blank"
import {FormPanel, FormRow, FormTitle} from "@/Components/Form"

export default {
    layout: Blank,
    components: {
        FormTitle,
        FormRow,
        FormPanel
    }
}
</script>

<style scoped>

</style>
