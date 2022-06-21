<template>
    <div class="space-y-3">
        <div class="text-right">
            <InertiaLink class="btn btn-green" href="/invest/futures/create">新增對帳單</InertiaLink>
        </div>
        <DivTable
            class="max-w-sm mx-auto sm:max-w-full"
            v-bind="list"
            :columns="columnsConfig"
            :rowLink="tableRowLink"
        ></DivTable>
    </div>
</template>

<script>
import Basic from "@/Layouts/Basic"
import ListTable from "@/Components/ListTable"
import DivTable from "@/Components/DivTable";
import moment from "moment"

export default {
    layout: Basic,
    components: {
        ListTable,
        DivTable,
    },
    props: {
        list: Object
    },
    setup() {
        const tableRowLink = (row) => `/invest/futures/${row.period}`

        const columnsConfig = [
            {
                header: '年月',
                // slot: 'Period',
                content: 'period',
                contentClass: 'text-center'
            },
            {
                header: '期末權益',
                content: (row) => moneyFormat(row.commitment),
                contentClass: 'text-right'
            },
            {
                header: '未平倉損益',
                content: (row) => moneyFormat(row.open_interest),
                contentClass: 'text-right'
            },
            {
                header: '沖銷損益',
                content: (row) => moneyFormat(row.cover_profit),
                contentClass: 'text-right'
            },
            {
                header: '權益損益',
                content: (row) => moneyFormat(row.commitment_profit),
                contentClass: 'text-right'
            },
            {
                header: '分配損益',
                contentClass: (row) => ('text-right ' + profitClass(row.profit)),
                content: (row) => moneyFormat(row.profit)
            }
        ]

        const tableHeader = [
            '年月',
            '期末權益',
            '未平倉損益',
            '沖銷損益',
            '權益損益',
            '分配損益'
        ]

        const tableColumns = [
            {
                class: 'text-center',
                content: 'period'
            },
            {
                class: 'text-right',
                content: (row) => moneyFormat(row.commitment)
            },
            {
                class: 'text-right',
                content: (row) => moneyFormat(row.open_interest)
            },
            {
                class: 'text-right',
                content: (row) => moneyFormat(row.cover_profit)
            },
            {
                class: 'text-right',
                content: (row) => moneyFormat(row.commitment_profit)
            },
            {
                class: (row) => ('text-right ' + profitClass(row.profit)),
                content: (row) => moneyFormat(row.profit)
            }
        ]

        const moneyFormat = window.moneyFormat

        const profitClass = window.profitClass

        return {
            tableHeader,
            tableColumns,
            columnsConfig,
            tableRowLink,
            moneyFormat,
            profitClass
        }
    }
}
</script>

<style scoped>

</style>
