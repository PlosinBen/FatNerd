<template>
    <div>
        <FormPanel>
            <FormRow label="年月" :error="futuresForm.errors.period">
                <input type="text" v-model="futuresForm.period" :readonly="investFutures.period !== undefined">
            </FormRow>
            <FormRow label="期末權益" :error="futuresForm.errors.commitment">
                <input type="number" v-model="futuresForm.commitment">
            </FormRow>
            <FormRow label="未平倉損益" :error="futuresForm.errors.open_interest">
                <input type="number" v-model="futuresForm.open_interest">
            </FormRow>
            <FormRow label="沖銷損益" :error="futuresForm.errors.cover_profit">
                <input type="number" v-model="futuresForm.cover_profit">
            </FormRow>
            <template #footer>
                <button class="btn-blue" @click="save">儲存</button>
                <InertiaLink class="btn-red" href="/invest/futures">取消</InertiaLink>
            </template>
        </FormPanel>
    </div>
</template>

<script>
import {useForm} from '@inertiajs/inertia-vue3'
import Basic from "@/Layouts/Basic"
import {FormPanel, FormRow} from "@/Components/Form"

export default {
    layout: Basic,
    components: {
        FormPanel,
        FormRow
    },
    props: {
        errors: Object,
        investFutures: {
            type: Object,
            default() {
                return { data: {}}
            }
        },
        action: Object
    },
    setup({action, investFutures}) {
        const futuresForm = useForm({
            _method: action.method,
            period: investFutures.period,
            commitment: investFutures.commitment,
            open_interest: investFutures.open_interest,
            cover_profit: investFutures.cover_profit,
        })

        const moneyFormatter = window.moneyFormatter();

        const moneyFormat = (money) => moneyFormatter.format(money)

        return {
            futuresForm,
            moneyFormat
        }
    },
    methods: {
        save() {
            this.futuresForm.post(this.action.url)
        }
    }
}
</script>

<style scoped>

</style>
