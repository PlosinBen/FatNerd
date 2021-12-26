<template>
    <div>
        <FormPanel class="flex flex-wrap">
            <div class="w-full sm:w-1/2 sm:pr-4 sm:border-r">
                <FormTitle>對帳單資料</FormTitle>

                <div class="flex flex-col sm:flex-row mb-3">
                    <div class="flex-grow flex flex-row sm:flex-col items-center">
                        <div class="py-1 w-1/2 sm:w-auto text-center">
                            期別
                        </div>
                        <div class="flex-grow text-right sm:text-center">
                            {{ investFutures.data.period }}
                        </div>
                    </div>
                    <div class="flex-grow flex flex-row sm:flex-col items-center">
                        <div class="py-1 w-1/2 sm:w-auto text-center">
                            期末權益
                        </div>
                        <div class="flex-grow text-right sm:text-center">
                            {{ investFutures.data.commitment }}
                        </div>
                    </div>
                    <div class="flex-grow flex flex-row sm:flex-col items-center">
                        <div class="py-1 w-1/2 sm:w-auto text-center">
                            未平倉損益
                        </div>
                        <div class="flex-grow text-right sm:text-center">
                            {{ investFutures.data.open_interest }}
                        </div>
                    </div>
                    <div class="flex-grow flex flex-row sm:flex-col items-center">
                        <div class="py-1 w-1/2 sm:w-auto text-center">
                            沖銷損益
                        </div>
                        <div class="flex-grow text-right sm:text-center">
                            {{ investFutures.data.cover_profit }}
                        </div>
                    </div>
                </div>

                <FormTitle>損益計算過程資料</FormTitle>


                <FormRow label="實質權益(期末權益 - 未平倉損益)">
                    {{ investFutures.data.real_commitment }}
                </FormRow>
                <FormRow label="出入金淨額(當期總入金 - 當期總出金)">
                    {{ investFutures.data.net_deposit_withdraw }}
                </FormRow>
                <FormRow label="權益損益(實質權益 - 出入金淨額 - 上期實質權益)">
                    {{ investFutures.data.commitment_profit }}
                </FormRow>
                <FormRow label="最終損益 min(權益損益, 沖銷損益)">
                    {{ investFutures.data.profit }}
                </FormRow>
                <FormRow label="總分額數">
                    {{ investFutures.data.total_quota }}
                </FormRow>
                <FormRow label="每份額損益額">
                    {{ investFutures.data.profit_per_quota }}
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
                <InertiaLink class="btn-blue" :href="`/invest/futures/${investFutures.data.period}/edit`">編輯</InertiaLink>
                <InertiaLink class="btn-green" href="/invest/futures">回列表</InertiaLink>
            </template>
        </FormPanel>
    </div>
</template>

<script>
import Basic from "../../../Layouts/Basic";
import {FormPanel, FormTitle, FormRow} from "../../../Components/Form";
import ListTable from "../../../Components/ListTable";
import moment from "moment";

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
    }
}
</script>

<style scoped>

</style>
