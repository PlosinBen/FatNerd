<template>
    <FormPanel class="flex flex-wrap">
        <FormPanel class="w-full flex flex-col sm:flex-row text-center space-y-2 sm:space-y-0">
            <div class="flex-grow flex sm:flex-col">
                <div class="flex-1">期別</div>
                <div class="flex-1" v-text="(investFutures.period)"></div>
            </div>
            <div class="flex-grow flex sm:flex-col">
                <div class="flex-1">期末權益</div>
                <div
                    class="flex-1"
                    v-text="moneyFormat(investFutures.commitment)"
                ></div>
            </div>
            <div class="flex-grow flex sm:flex-col">
                <div class="flex-1">
                    未平倉損益
                </div>
                <div
                    class="flex-1"
                    :class="profitAndLossStyle(investFutures.open_interest)"
                    v-text="moneyFormat(investFutures.open_interest)"
                ></div>
            </div>
            <div class="flex-grow flex sm:flex-col">
                <div class="flex-1">
                    沖銷損益
                </div>
                <div
                    class="flex-1"
                    :class="profitAndLossStyle(investFutures.cover_profit)"
                    v-text="moneyFormat(investFutures.cover_profit)"
                ></div>
            </div>
            <div class="flex-grow flex sm:flex-col">
                <div class="flex-1">
                    入金
                </div>
                <div
                    class="flex-1"
                    v-text="moneyFormat(investFutures.deposit)"
                ></div>
            </div>
            <div class="flex-grow flex sm:flex-col">
                <div class="flex-1">
                    出金
                </div>
                <div
                    class="flex-1"
                    v-text="moneyFormat(investFutures.withdraw)"
                ></div>
            </div>
        </FormPanel>
        <FormPanel class="w-full md:w-1/2 md:border-r space-y-3">
            <FormTitle>損益計算</FormTitle>

            <div
                v-for="(display, column) in futuresColumns"
                class="sm:flex"
            >
                <div>
                    {{ display.label }}
                    <div class="text-sm text-gray-500 inline-block sm:block">
                        <span>{{ display.comment }}</span>
                    </div>
                </div>
                <div
                    class="text-right pr-2 sm:flex-grow sm:flex sm:flex-row sm:items-center"
                >
                    <span class="flex-grow" v-text="moneyFormat(investFutures[column])"></span>
                </div>
            </div>
        </FormPanel>
        <FormPanel class="w-full sm:w-1/2">
            <FormTitle>損益拆帳明細</FormTitle>
            <ListTable
                class="text-right"
                :list="InvestBalances"
                :headers="tableHeader"
                :columns="tableColumns"
            ></ListTable>
        </FormPanel>
        <template #footer>
            <InertiaLink class="btn-blue" :href="`/invest/futures/${investFutures.period}/edit`">編輯
            </InertiaLink>
            <InertiaLink class="btn-green" href="/invest/futures">回列表</InertiaLink>
        </template>
    </FormPanel>
</template>

<script>
import Basic from "@/Layouts/Basic"
import {FormPanel, FormTitle, FormRow} from "@/Components/Form"
import ListTable from "@/Components/ListTable"

export default {
    layout: Basic,
    components: {
        FormPanel,
        FormTitle,
        FormRow,
        ListTable
    },
    props: {
        investFutures: Object,
        InvestBalances: Array
    },
    setup() {
        const tableHeader = [
            '暱稱',
            '份額',
            '損益'
        ]

        const tableColumns = [
            'invest_account_alias',
            'quota',
            (row) =>window.moneyFormat(row.profit)
        ]

        const futuresColumns = {
            real_commitment: {
                label: "實質權益",
                comment: "期末權益 - 未平倉損益"
            },
            net_deposit_withdraw: {
                label: "出入金淨額",
                comment: "當期總入金 - 當期總出金"
            },
            commitment_profit: {
                label: "權益損益",
                comment: "實質權益 - 出入金淨額 - 上期實質權益"
            },
            profit: {
                label: "最終損益",
                comment: "min(權益損益, 沖銷損益)"
            },
            total_quota: {
                label: "總權重數",
                comment: ""
            },
            profit_per_quota: {
                label: "每權重損益額",
                comment: ""
            },
        }

        const moneyFormatter = window.moneyFormatter();

        return {
            tableHeader,
            tableColumns,
            moneyFormatter,
            futuresColumns
        }
    },
    methods: {
        moneyFormat(money) {
            return this.moneyFormatter.format(money)
        },
        profitAndLossStyle(money) {
            console.log(money, money > 0)
            if (money > 0) {
                return 'income-profit'
            }

            if (money < 0) {
                return 'income-loss'
            }
        }
    }
}
</script>

<style scoped>

</style>
