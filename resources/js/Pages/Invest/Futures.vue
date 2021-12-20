<template>
    <div>
        <ListTable :list="list" :headers="tableHeader" :columns="tableColumns"/>
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
            '分配總額'
        ]

        const tableColumns = [
            {
                class: 'text-center',
                content(row) {
                    return moment(row.period).format('YYYY-MM')
                }
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
            },
            {
                class: 'text-right',
                content: 'distribution'
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
