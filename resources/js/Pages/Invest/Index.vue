<template>
    <div>
        <table class="table-fixed w-full border rounded divide-y text-center">
            <tr class="bg-coolGray-400 text-white">
                <th class="py-1.5">年月</th>
                <th>入金</th>
                <th>出金</th>
                <th>損益</th>
                <th>費用</th>
                <th>結餘</th>
            </tr>
            <template v-for="(investRecord, period) in investRecords">
                <tr>
                    <td class="px-3 py-2.5" v-text="period"></td>
                    <td class="px-3 py-2.5" v-text="investRecord.deposit || 0"></td>
                    <td class="px-3 py-2.5" v-text="investRecord.withdraw || 0"></td>
                    <td
                        class="px-3 py-2.5"
                        :class="profitClass('profit', investRecord.profit)"
                        v-text="investRecord.profit || 0"
                    ></td>
                    <td class="px-3 py-2.5" v-text="investRecord.expense || 0"></td>
                    <td class="px-3 py-2.5" v-text="investRecord.balance || 0"></td>
                </tr>
                <tr>
                    <td colspan="6" class="p-3">
                        <table class="table-fixed text-center w-full divide-y">
                            <tr>
                                <th class="py-1.5 w-40">日期</th>
                                <th class="py-1.5 w-32">類型</th>
                                <th class="py-1.5 w-40">金額</th>
                                <th class="py-1.5">備註</th>
                            </tr>
                            <tr v-for="record in investRecord.detail">
                                <td class="px-3 py-2" v-text="record.occurred_at"></td>
                                <td class="px-3 py-2" v-text="typeText[record.type]"></td>
                                <td
                                    class="px-3 py-2 text-right"
                                    :class="profitClass(record.type, record.amount)"
                                    v-text="record.amount"
                                ></td>
                                <td class="px-3 py-2" v-text="record.note"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </template>
        </table>
    </div>
</template>

<script>
import Basic from "@/Layouts/Basic"
import ListTable from "@/Components/ListTable"
import {FormRow} from "@/Components/Form"
import moment from 'moment';

export default {
    layout: Basic,
    components: {
        ListTable,
        FormRow
    },
    props: {
        investRecords: Object
    },
    setup() {
        const listHeaders = [
            '年月',
            '入金',
            '出金',
            '損益',
            '費用',
            '結餘',
            '備註'
        ]
        const listColumns = [
            {
                class: 'text-center',
                content(row) {
                    return moment(row.period).format('YYYY-MM')
                }
            },
            {
                class: 'text-right',
                content: 'deposit'
            },
            {
                class: 'text-right',
                content: 'withdraw'
            },
            {
                class: 'text-right',
                content: 'profit'
            },
            {
                class: 'text-right',
                content: 'expense'
            },
            {
                class: 'text-right',
                content: 'balance'
            },
            {
                content: 'note'
            },
        ]

        const typeText = {
            deposit: '入金',
            withdraw: '出金',
            profit: '損益分配',
            expense: '費用',
            transfer: '出金轉存'
        }

        return {
            listHeaders,
            listColumns,
            typeText
        }
    },
    methods: {
        profitClass(type, amount) {
            if (type !== 'profit') {
                return ''
            }

            return [
                'font-bold',
                amount > 0 ? 'text-red-600' : 'text-green-600'
            ]
        }
    }
}
</script>

<style scoped>

</style>
