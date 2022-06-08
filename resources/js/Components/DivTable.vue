<template>
    <div class="border divide-y">
        <div class="flex">
            <div
                v-for="header in headers"
                class="flex-1 text-center font-bold py-2 bg-blueGray-400 text-white"
                v-text="header.text"
            ></div>
        </div>
        <div
            class="flex py-2.5"
            v-for="row in list"
        >
            <div
                class="flex-1"
                v-for="content in contents"
            >
                <slot :name="content.slot" :row="row">
                    {{ getContent(content.text, row) }}
                </slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "DivTable",
    props: {
        list: Array,
        columns: Array
    },
    setup({columns}) {
        const headers = []
        const contents = []

        columns.forEach((column) => {
            headers.push({
                text: column.header,
                class: column.hasOwnProperty('headerClass') ? column.headerClass : ''
            })
            contents.push({
                text: column.content,
                slot: column.hasOwnProperty('slot') ? column.slot : '',
                class: column.hasOwnProperty('contentClass') ? column.contentClass : ''
            })
        })

        return {
            headers,
            contents
        }
    },
    methods: {
        getContent(content, row) {
            return {}.toString.call(content) === '[object Function]' ? content(row) : row[content]
        }
    }
}
</script>

<style scoped>

</style>
