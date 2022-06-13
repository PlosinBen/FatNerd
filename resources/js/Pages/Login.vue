<template>
    <div class="flex-grow"></div>

    <FormPanel class="bg-white">
        <FormTitle class="border-b py-2">登入</FormTitle>

        <FormRow>
            <InertiaLink href="/login/facebook" class="btn-blue bg-blue-600 py-1.5 px-3">
                <i class="fab fa-facebook-f border-r pr-3 mr-2"></i>
                Sign in with Facebook
            </InertiaLink>
        </FormRow>
    </FormPanel>
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
