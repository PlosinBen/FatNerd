<template>
    <div class="space-y-3">
        <div class="text-right">
            <InertiaLink class="btn-green" href="/invest/futures/create">新增對帳單</InertiaLink>
        </div>
        <DivTable
            :list="list.data"
            :columns="columnsConfig"
        >
            <template #Period="{row}">
                <span class="text-blue-600">
                    {{ row.period }}
                </span>
            </template>
        </DivTable>
        <ListTable
            :list="list.data"
            :headers="tableHeader"
            :columns="tableColumns"
            :colors="['bg-white', 'bg-gray-100']"
        >
            <template #column_0="{row}">
                <InertiaLink class="border-b space-x-2" :href="`/invest/futures/${row.period}`">
                    <span class="text-blue-600">
                        {{ row.period }}
                    </span>
                    <i class="hidden fas fa-sm fa-external-link-alt"></i>
                </InertiaLink>
            </template>
        </ListTable>
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
        const tableConfig = {}

        const columnsConfig = [
            {
                header: '年月',
                slot: 'Period'
            },
            {
                header: '期末權益',
                content: (row) => moneyFormat(row.commitment)
            },
            {
                header: '未平倉損益',
                content: (row) => moneyFormat(row.open_interest)
            },
            {
                header: '沖銷損益',
                content: (row) => moneyFormat(row.cover_profit)
            },
            {
                header: '權益損益',
                content: (row) => moneyFormat(row.commitment_profit)
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
            moneyFormat,
            profitClass
        }
    }
}
</script>

<style scoped>

</style>
