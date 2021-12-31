<template>
    <div class="space-y-3">
        <div>
            <InertiaLink class="btn-green" href="/futures/create">新增對帳單</InertiaLink>
        </div>
        <ListTable
            :list="list"
            :headers="tableHeader"
            :columns="tableColumns"
            :colors="['bg-white', 'bg-gray-100']"
        >
            <template #column_0="{row}">
                <InertiaLink class="border-b space-x-2" :href="`/futures/${row.period}`">
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
import moment from "moment"

export default {
    layout: Basic,
    components: {
        ListTable
    },
    props: {
        list: Object
    },
    setup() {
        const tableHeader = [
            '年月',
            '期末權益',
            '未平倉損益',
            '沖銷損益',
        ]

        const tableColumns = [
            {
                class: 'text-center',
            },
            {
                class: 'text-right',
                content: 'commitment'
            },
            {
                class: 'text-right',
                content: 'open_interest'
            },
            {
                class: 'text-right',
                content: 'profit'
            }
        ]

        return {
            tableHeader,
            tableColumns
        }
    }
}
</script>

<style scoped>

</style>
