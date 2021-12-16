<template>
    <table class="table-auto w-full border rounded divide-y">
        <tr class="bg-blueGray-400 text-white">
            <th
                class="py-2"
                v-for="header in headerSetting"
                v-text="header.content"
            >
            </th>
        </tr>
        <tr v-for="row in list.data">
            <td
                class="py-1.5 px-2"
                v-for="column in columnSetting"
                :class="column.class"
                v-text="getContent(column.content, row)"
            >
            </td>
        </tr>
    </table>
</template>

<script>
export default {
    name: "ListTable",
    props: {
        list: Object,

        // columns
        // [
        //     'Id',
        //     (row) => {
        //         return row.id.toString()
        //     },
        //     {
        //         class: '',
        //         content: 'Money'
        //     },
        //     {
        //         class: '',
        //         content(row) {
        //             return row.money / 2
        //         }
        //     }
        // ]
        columns: {
            type: Array,
            default: () => []
        },
        headers: {
            type: Array,
            default: () => []
        }
    },
    setup({headers, columns}) {
        const columnSetting = columns.map((column) => {
            return typeof column === 'object' ? column : {class: '', content: column}
        })
        const headerSetting = headers.map((header) => {
            return typeof header === 'object' ? header : {class: '', content: header}
        })


        return {
            headerSetting,
            columnSetting
        }
    },
    data() {
        const size = []

        return {}
    },
    methods: {
        getContent(content, row) {
            return {}.toString.call(content) === '[object Function]' ? content(row): row[content]
        }
    }
}
</script>

<style scoped>

</style>
