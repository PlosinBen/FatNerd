<template>
    <FormPanel>
        <FormRow label="User" :error="form.errors.investAccountId">
            <select v-model="form.investAccountId">
                <option
                    v-for="investAccount in investAccounts"
                    :value="investAccount.id"
                    v-text="investAccount.alias"
                ></option>
            </select>
        </FormRow>
        <FormRow label="日期" :error="form.errors.occurredAt">
            <Datepicker
                class="cursor-pointer"
                v-model="form.occurredAt"
            />
        </FormRow>
        <FormRow label="類型" :error="form.errors.type">
            <select v-model="form.type">
                <option value="deposit">入金</option>
                <option value="withdraw">出金</option>
                <option value="transfer">出金轉存</option>
                <option value="expense">費用</option>
            </select>
        </FormRow>
        <FormRow label="金額" :error="form.errors.amount">
            <input type="number" v-model="form.amount">
        </FormRow>
        <FormRow label="備註">
            <input type="text" v-model="form.note">
        </FormRow>
        <template #footer>
            <button class="btn-blue" :disabled="form.processing" @click="submit">送出</button>
            <InertiaLink class="btn-red" href="/invest/history">取消</InertiaLink>
        </template>
    </FormPanel>
</template>

<script>
import Basic from "@/Layouts/Basic"
import Datepicker from 'vue3-datepicker'
import {useForm} from "@inertiajs/inertia-vue3"
import {FormRow, FormPanel} from "@/Components/Form"
import moment from "moment"

export default {
    layout: Basic,
    components: {
        Datepicker,
        FormRow,
        FormPanel
    },
    props: {
        investAccounts: Object,
        action: Object
    },
    setup({action}) {
        const form = useForm({
            _method: action.method,
            investAccountId: undefined,
            occurredAt: new Date(),
            type: undefined,
            amount: undefined,
            note: undefined
        })

        return {
            form
        }
    },
    methods: {
        submit() {
            this.form
                .transform((data) => ({
                    ...data,
                    occurredAt: moment(this.form.occurredAt).format(),
                }))
                .post(this.action.url)
        }
    }
}
</script>

<style scoped>

</style>
