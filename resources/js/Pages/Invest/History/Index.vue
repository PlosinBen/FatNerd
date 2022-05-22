<template>
    <InertiaLink v-if="isAdmin" class="btn-green" href="/invest/history/create">新增紀錄</InertiaLink>

    <FormRow v-if="investYears.length > 1" label="年分">
        <select v-model="year">
            <option
                v-for="investYear in investYears"
                :value="investYear"
                v-text="investYear"
            ></option>
        </select>
    </FormRow>

    <div class="border border-t-0 divide-y">
        <div class="hidden sm:flex text-center py-1.5 border-t bg-coolGray-400 text-white">
            <div class="w-1/6">年月</div>
            <div class="w-1/6">入金</div>
            <div class="w-1/6">出金</div>
            <div class="w-1/6">損益</div>
            <div class="w-1/6">費用</div>
            <div class="w-1/6">結餘</div>
        </div>
        <div
            class="flex flex-wrap py-1.5 text-right"
            v-for="(investRecord, period) in investRecords"
        >
            <div class="w-full sm:w-1/6 flex items-center justify-center">
                <button class="border-b hover:text-sky-500" @click.stop="showDetail(investRecord.detail)">
                    {{ period }}
                    <i class="fas fa-external-link-alt pl-0.5 fa-xs"></i>
                </button>
            </div>
            <div class="w-5/12 sm:hidden py-0.5 text-center">入金</div>
            <div class="w-7/12 sm:w-1/6 py-0.5 px-3" v-text="investRecord.deposit || 0"></div>
            <div class="w-5/12 sm:hidden py-0.5 text-center">出金</div>
            <div class="w-7/12 sm:w-1/6 py-0.5 px-3" v-text="investRecord.withdraw || 0"></div>
            <div class="w-5/12 sm:hidden py-0.5 text-center">損益</div>
            <div
                class="w-7/12 sm:w-1/6 py-0.5 px-3"
                :class="profitClass('profit', investRecord.profit)"
                v-text="investRecord.profit || 0"
            ></div>
            <div class="w-5/12 sm:hidden py-0.5 text-center">費用</div>
            <div class="w-7/12 sm:w-1/6 py-0.5 px-3" v-text="investRecord.expense || 0"></div>
            <div class="w-5/12 sm:hidden py-0.5 text-center">結餘</div>
            <div class="w-7/12 sm:w-1/6 py-0.5 px-3" v-text="investRecord.balance || 0"></div>
        </div>
    </div>

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
                    v-text="record.amount"
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
import {FormRow} from "@/Components/Form"
import Modal from "@/Components/Modal"
import moment from 'moment'

export default {
    layout: Basic,
    components: {
        ListTable,
        FormRow,
        Modal
    },
    props: {
        isAdmin: Boolean,
        year: Number,
        investYears: Array,
        investRecords: Object
    },
    mounted() {
        this.props
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
            typeText,
        }
    },
    watch: {
        year() {
            this.$inertia.get(`?year=${this.year}`)
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
            if (type !== 'profit') {
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
