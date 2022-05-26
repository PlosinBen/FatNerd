<template>
    <div class="text-right">
        <InertiaLink v-if="isAdmin" class="btn-green" href="/invest/history/create">新增紀錄</InertiaLink>
    </div>

    <div class="flex flex-col md:flex-row">
        <FormPanel class="md:border-r pl-1 pr-2">
            <ul class="flex md:flex-col">
                <li class="text-center flex flex-col items-center justify-center hidden">
                    <i class="w-7 fas fa-angle-left"></i>
                </li>
                <li
                    v-for="investYear in investYears"
                    class="py-1 px-5"
                    :class="{'font-bold bg-blue-400 text-white':year === investYear, 'hover:bg-blue-100 hover:text-blue-900': year !== investYear}"
                >
                    <span v-if="year === investYear" v-text="investYear"></span>
                    <InertiaLink v-else :href="`/invest/history?year=${investYear}`" v-text="investYear"></InertiaLink>
                </li>
                <li class="text-center flex flex-col items-center justify-center hidden">
                    <i class="w-7 fas fa-angle-right"></i>
                </li>
            </ul>
        </FormPanel>

        <FormPanel class="flex-grow pl-2 pr-1 max-w-screen-lg">
            <div class="border divide-y">
                <FormRow
                    v-for="investBalance in investBalances"
                >
                    <div class="flex flex-col sm:flex-row px-3 space-y-1 sm:space-y-0 sm:text-center">
                        <div class="flex flex-col justify-center">
                            <div class="px-3 text-center" v-text="investBalance.period"></div>
                        </div>
                        <div class="flex sm:flex-1 sm:flex-col">
                            <div class="flex-1 sm:py-2">入金</div>
                            <div class="flex-1 text-right sm:text-center"
                                 v-text="moneyFormat(investBalance.deposit)"></div>
                        </div>
                        <div class="flex sm:flex-1 sm:flex-col">
                            <div class="flex-1 sm:py-2">出金</div>
                            <div class="flex-1 text-right sm:text-center"
                                 v-text="moneyFormat(investBalance.withdraw)"></div>
                        </div>
                        <div class="flex sm:flex-1 sm:flex-col">
                            <div class="flex-1 sm:py-2">損益</div>
                            <div
                                class="flex-1 text-right sm:text-center"
                                :class="profitClass('profit', investBalance.profit)"
                                v-text="moneyFormat(investBalance.profit)"
                            ></div>
                        </div>
                        <div class="flex sm:flex-1 sm:flex-col">
                            <div class="flex-1 sm:py-2">費用</div>
                            <div class="flex-1 text-right sm:text-center"
                                 v-text="moneyFormat(investBalance.expense)"></div>
                        </div>
                        <div class="flex sm:flex-1 sm:flex-col">
                            <div class="flex-1 sm:py-2">結餘</div>
                            <div class="flex-1 text-right sm:text-center"
                                 v-text="moneyFormat(investBalance.balance)"></div>
                        </div>
                    </div>
                </FormRow>
            </div>
        </FormPanel>
    </div>
    <FormPanel>

    </FormPanel>

    <Modal v-model="show">
        <div class="hidden sm:flex text-center border-b">
            <div class="py-1.5 w-28">日期</div>
            <div class="py-1.5 w-28">類型</div>
            <div class="py-1.5 w-28">金額</div>
            <div class="py-1.5 w-40">備註</div>
            <div v-if="isAdmin" class=""></div>
        </div>
        <div class="divide-y sm:divide-y-0">
            <div class="flex flex-wrap sm:flex-nowrap py-1.5" v-for="record in historyDetail">
                <div class="flex-grow sm:flex-grow-0 sm:w-28 py-0.5 text-center" v-text="record.occurred_at"></div>

                <div class="sm:order-last" v-if="isAdmin">
                    <button class="btn-red" @click="deleteDetail(record.id)">Delete</button>
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
import ListTable from "@/Components/ListTable"
import {FormPanel, FormTitle, FormRow} from "@/Components/Form"
import Modal from "@/Components/Modal"
import moment from 'moment'

export default {
    layout: Basic,
    components: {
        ListTable,
        FormPanel,
        FormTitle,
        FormRow,
        Modal
    },
    props: {
        isAdmin: Boolean,
        year: Number,
        investYears: Array,
        investRecords: Array,
        investBalances: Array

    },
    mounted() {
        this.props
    },
    setup() {
        const listHeaders = [
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
                content: 'period'
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

        const moneyFormat = window.moneyFormat

        return {
            listHeaders,
            listColumns,
            typeText,
            moneyFormat
        }
    },
    watch: {
        year() {
            let params = new URLSearchParams(location.search)

            params.set('year', this.year.toString())

            this.$inertia.get(`?${params.toString()}`)
        }
    },
    data() {
        return {
            show: false,
            historyDetail: undefined
        }
    },
    methods: {
        profitClass(type, amount) {
            if (type !== 'profit' || parseInt(amount) === 0) {
                return ''
            }

            return [
                'font-bold',
                amount > 0 ? 'text-red-600' : 'text-green-600'
            ]
        },
        showDetail(historyDetail) {
            console.log(historyDetail)
            this.historyDetail = historyDetail
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
