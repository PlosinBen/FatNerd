<template>
    <div class="border divide-y">
        <div class="hidden sm:flex">
            <div
                v-for="header in headers"
                class="flex-1 text-center font-bold py-2 bg-blueGray-400 text-white tracking-wider"
                v-text="header.text"
            ></div>
        </div>
        <div
            class="block sm:flex py-2.5 px-3"
            :class="{'cursor-pointer hover:bg-blue-100': hasRowLink}"
            v-for="row in list"
            @click="rowClick(row)"
        >
            <template
                v-for="(content, index) in contents"
            >
                <div
                    class="inline-block w-1/2 text-center sm:hidden tracking-wide"
                >
                    {{ headers[index].text }}
                </div>
                <div
                    class="inline-block w-1/2 flex-1 px-1.5"
                    :class="getColumn(content.class, row)"
                >
                    <slot :name="content.slot" :row="row">
                        {{ getContent(content.text, row) }}
                    </slot>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    name: "DivTable",
    props: {
        list: Array,
        columns: Array,
        rowLink: Function
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
    computed: {
        hasRowLink() {
            return typeof this.rowLink === "function"
        }
    },
    methods: {
        getContent(content, row) {
            return {}.toString.call(content) === '[object Function]' ? content(row) : row[content]
        },
        getColumn(content, row) {
            return {}.toString.call(content) === '[object Function]' ? content(row) : content
        },
        rowClick(row) {
            if(this.hasRowLink) {
                let link = this.rowLink(row)
                this.$inertia.get(link)
            }
        }
    }
}
</script>

<style scoped>

</style>
