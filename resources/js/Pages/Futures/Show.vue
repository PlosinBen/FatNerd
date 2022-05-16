<template>
    <FormPanel>
        <FormPanel class="flex flex-col sm:flex-row text-center mx-6 space-y-2">
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
                    其他調整
                </div>
                <div
                    class="flex-1"
                    v-text="moneyFormat(investFutures.cover_profit)"
                ></div>
            </div>
        </FormPanel>
        <FormPanel class="flex flex-wrap mt-2">
            <div class="w-full sm:w-1/2 sm:pr-4 sm:border-r">
                <FormTitle>損益計算</FormTitle>

                <FormRow label="實質權益(期末權益 - 未平倉損益)">
                    {{ moneyFormat(investFutures.real_commitment) }}
                </FormRow>
                <FormRow label="出入金淨額(當期總入金 - 當期總出金)">
                    {{ moneyFormat(investFutures.net_deposit_withdraw) }}
                </FormRow>
                <FormRow label="權益損益(實質權益 - 出入金淨額 - 上期實質權益)">
                    {{ moneyFormat(investFutures.commitment_profit) }}
                </FormRow>
                <FormRow label="最終損益 min(權益損益, 沖銷損益)">
                    {{ moneyFormat(investFutures.profit) }}
                </FormRow>
                <FormRow label="總分額數">
                    {{ moneyFormat(investFutures.total_quota) }}
                </FormRow>
                <FormRow label="每份額損益額">
                    {{ moneyFormat(investFutures.profit_per_quota) }}
                </FormRow>
            </div>
            <div class="w-full sm:w-1/2 sm:pl-4">
                <FormTitle>損益拆帳明細</FormTitle>
                <ListTable
                    class="text-right"
                    :list="investFuturesProfits"
                    :headers="tableHeader"
                    :columns="tableColumns"
                ></ListTable>
            </div>
            <template #footer>
                <InertiaLink class="btn-blue" :href="`/invest/futures/${investFutures.period}/edit`">編輯
                </InertiaLink>
                <InertiaLink class="btn-green" href="/invest/futures">回列表</InertiaLink>
            </template>
        </FormPanel>
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
        investFuturesProfits: Object
    },
    setup() {
        const tableHeader = [
            '暱稱',
            '份額',
            '損益'
        ]

        const tableColumns = [
            (row) => {
                return row.invest_account.alias
            },
            'quota',
            'profit'
        ]

        return {
            tableHeader,
            tableColumns
        }
    },
    computed: {
        numberformat() {
            return new Intl.NumberFormat('zh-TW', {
                trailingZeroDisplay: 'lessPrecision'
            })
        }
    },
    methods: {
        moneyFormat(money) {
            return this.numberformat.format(money)
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
