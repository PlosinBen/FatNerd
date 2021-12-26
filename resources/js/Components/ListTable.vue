<template>
    <table class="table-auto w-full border rounded divide-y">
        <tr class="bg-blueGray-400 text-white">
            <th
                class="py-2 px-3"
                v-for="header in headerSetting"
                v-text="header.content"
            >
            </th>
        </tr>
        <tr
            v-for="(row, index) in list.data"
            class="hover:bg-blue-50"
            :class="getClass(index)"
        >
            <td
                class="py-1.5 px-2"
                v-for="(column, index) in columnSetting"
                :class="column.class"
            >
                <slot :name="`column_${index}`" :row="row">
                    {{ getContent(column.content, row) }}
                </slot>
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
        },
        colors: {
            type: Array,
            default: () => ['bg-white']
        }
    },
    setup({headers, columns, colors}) {
        const columnSetting = columns.map((column) => {
            return typeof column === 'object' ? column : {class: '', content: column}
        })
        const headerSetting = headers.map((header) => {
            return typeof header === 'object' ? header : {class: '', content: header}
        })

        const colorCount = colors.length

        return {
            headerSetting,
            columnSetting,
            colorCount
        }
    },
    data() {
        const size = []

        return {}
    },
    methods: {
        getContent(content, row) {
            return {}.toString.call(content) === '[object Function]' ? content(row) : row[content]
        },
        getClass(index) {
            return this.colors[index % this.colorCount];
        }
    }
}
</script>

<style scoped>

</style>
