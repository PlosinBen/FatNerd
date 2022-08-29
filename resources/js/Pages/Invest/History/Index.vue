<template>
    <div class="text-right">
        <InertiaLink v-if="this.$page.props.isAdmin" class="btn btn-green" href="/invest/history/create">
            新增紀錄
        </InertiaLink>
    </div>

    <div class="">
        <DivTable
            class="max-w-screen-xl mx-auto"
            v-bind="balances"
            :columns="balanceTable"

            :rowClick="showDetail"
            :rowHover="true"
        ></DivTable>
    </div>

    <Modal v-model="show">
        <div class="hidden sm:flex text-center border-b">
            <div class="py-1.5 w-28">日期</div>
            <div class="py-1.5 w-28">類型</div>
            <div class="py-1.5 w-28">金額</div>
            <div class="py-1.5 w-40">備註</div>
        </div>
        <div class="divide-y sm:divide-y-0">
            <div class="flex flex-wrap sm:flex-nowrap py-1.5" v-for="record in modelHistories">
                <div class="flex-grow sm:flex-grow-0 sm:w-28 py-0.5 text-center" v-text="record.occurred_at"></div>

                <div class="sm:order-last" v-if="this.$page.props.isAdmin">
                    <button class="btn btn-red btn-sm" @click="deleteDetail(record.id)">Delete</button>
                </div>

                <div class="w-1/2 sm:w-28 py-0.5 text-center" v-text="typeText[record.type]"></div>
                <div
                    class="w-1/2 sm:w-28 sm:px-3 py-0.5 text-right"
                    :class="profitClass(record.type, record.amount)"
                    v-text="moneyFormat(record.amount)"
                ></div>
                <div class="px-3 py-0.5 sm:w-40">
                    {{ record.note }}&nbsp;
                </div>
            </div>
        </div>
    </Modal>
</template>

<script>
import Basic from "@/Layouts/Basic"
import DivTable from "@/Components/DivTable"
import {FormPanel, FormTitle, FormRow} from "@/Components/Form"
import Modal from "@/Components/Modal"
import moment from "moment";

export default {
    layout: Basic,
    components: {
        DivTable,
        FormPanel,
        FormTitle,
        FormRow,
        Modal
    },
    props: {
        balances: Object,
        histories: Object
    },
    setup({histories}) {
        const typeText = {
            deposit: '入金',
            withdraw: '出金',
            profit: '損益分配',
            expense: '費用',
            transfer: '出金轉存'
        }

        const monthHistories = histories.group(({occurred_at}) => moment(occurred_at).format('Y-MM'))

        const moneyFormat = window.moneyFormat

        const profitClass = window.profitClass

        const balanceTable = [
            {
                header: "年月",
                headerClass: "pb-1 mb-1 border-b sm:pb-0 sm:mb-0 sm:border-b-0",
                content: "period",
                contentClass: "text-center pb-1 mb-1 border-b sm:pb-0 sm:mb-0 sm:border-b-0"
            },
            {
                header: "入金",
                headerClass: "mb-0.5 sm:mb-0",
                content: (row) => moneyFormat(row.deposit),
                contentClass: "text-right"
            },
            {
                header: "出金",
                headerClass: "mb-0.5 sm:mb-0",
                content: (row) => moneyFormat(row.withdraw),
                contentClass: "text-right"
            },
            {
                header: "損益",
                headerClass: "mb-0.5 sm:mb-0",
                content: (row) => moneyFormat(row.profit),
                contentClass: (row) => [
                    "text-right",
                    profitClass(row.profit),
                ]
            },
            {
                header: "費用",
                headerClass: "mb-0.5 sm:mb-0",
                content: (row) => moneyFormat(row.expense),
                contentClass: "text-right"
            },
            {
                header: "結餘",
                headerClass: "mb-0.5 sm:mb-0",
                content: (row) => moneyFormat(row.balance),
                contentClass: "text-right"
            }
        ]

        return {
            typeText,
            balanceTable,
            monthHistories,
            moneyFormat,
            profitClass
        }
    },
    data() {
        return {
            show: false,
            modelHistories: []
        }
    },
    methods: {
        showDetail(row) {
            this.modelHistories = this.monthHistories[row.period]

            this.show = true
        },
        deleteDetail(historyId) {
            this.$inertia.post(`/invest/history/${historyId}`, {
                _method: 'delete'
            })
        }
    },
}
</script>

<style scoped>

</style>
