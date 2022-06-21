<template>
    <div>
        <div class="border divide-y mb-2">
            <div class="hidden sm:flex">
                <div
                    v-for="header in headers"
                    class="flex-1 text-center font-bold py-2 bg-blueGray-400 text-white tracking-wider"
                    :class="getColumn(header.class)"
                    v-text="header.text"
                ></div>
            </div>
            <div
                class="block sm:flex py-2.5 px-3"
                :class="{'cursor-pointer hover:bg-blue-100': hasRowLink}"
                v-for="row in data"
                @click="rowClick(row)"
            >
                <template
                    v-for="(content, index) in contents"
                >
                    <div
                        class="inline-block w-1/3 text-center sm:hidden tracking-wide"
                        :class="getColumn(headers[index].class)"
                    >
                        {{ headers[index].text }}
                    </div>
                    <div
                        class="inline-block w-2/3 flex-1 px-1.5"
                        :class="getColumn(content.class, row)"
                    >
                        <slot :name="content.slot" :row="row">
                            {{ getContent(content.text, row) }}
                        </slot>
                    </div>
                </template>
            </div>
        </div>
        <div v-if="meta" class="mb-2">
            <ul
                class="flex justify-between sm:hidden items-center"
            >
                <li>
                    <InertiaLink :href="links.prev" class="btn w-24">
                        « Previous
                    </InertiaLink>
                </li>
                <li>
                    {{ meta.current_page }}
                    <span class="mx-0.5">/</span>
                    {{ meta.last_page }}
                </li>
                <li>
                    <InertiaLink :href="links.next" class="btn w-24">
                        Next »
                    </InertiaLink>
                </li>
            </ul>
            <div class="hidden sm:inline-block">
                <ul class="flex border divide-x rounded">
                    <li
                        v-for="link in meta.links"
                    >
                        <InertiaLink
                            class="inline-block py-1 px-3 hover:bg-blue-50"
                            :class="{'bg-gray-200': link.active}"
                            :href="link.url"
                            v-html="link.label"
                        >
                        </InertiaLink>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "DivTable",
    props: {
        data: Array,
        links: Object,
        meta: Object,

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
