<template>
    <div>
        <ListTable :list="list" :headers="listHeaders" :columns="listColumns"/>
    </div>
</template>

<script>
import Basic from "@/Layouts/Basic"
import ListTable from "@/Components/ListTable"
import moment from 'moment';

export default {
    layout: Basic,
    components: {
        ListTable
    },
    props: {
        list: Object
    },
    setup() {
        const listHeaders = [
            {
                content: '年月'
            },
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

        return {
            listHeaders,
            listColumns
        }
    }
}
</script>

<style scoped>

</style>
