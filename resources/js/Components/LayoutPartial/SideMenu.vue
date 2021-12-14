<template>
    <div class="bg-blueGray-600 w-52 text-gray-400 max-w-xs border-r">
        <ul class="pb-2" v-for="itemGroup in menu">
            <li>
                {{ itemGroup.label }}
            </li>
            <li>
                <a class="block py-2.5 px-3 border-l-4 border-white hover:border-blue-100 hover:text-gray-50 hover:font-bold" href="#">
                    <i class="w-5 text-center fas fa-search-dollar"></i>
                    投資
                </a>
                <ul class="pb-2">
                    <li>
                        <a class="block py-2.5 pl-7 pr-3 border-l-4 border-blue-600 hover:font-bold" href="#">
                            記帳
                        </a>
                    </li>
                    <li>
                        <a class="block py-2.5 pl-7 pr-3 border-l-4 border-white hover:border-blue-100 hover:text-gray-800 hover:font-bold" href="#">
                            投資
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="block py-2.5 px-3 border-l-4 border-blue-600 hover:bg-blue-100 hover:font-bold" href="#">
                    <i class="w-5 text-center far fa-list-alt"></i>
                    記帳
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "SideMenu",
    props: {
        menu: Array
    }
}
</script>

<style scoped>

</style>
