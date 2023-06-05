<script setup lang="ts">
import Pagination from "@/Components/Pagination.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { onMounted } from "vue";

type Referee = {
    id: number;
    referrer_name: string;
    email: string;
    status: string;
    created_at: string;
};

type Link = {
    url: string;
    label: string;
    active: boolean;
};

let props = defineProps<{
    referrals: {
        data: Referee[];
        meta: {
            links: Link[];
            last_page: number;
        };
    };
}>();

onMounted(() => {
    console.log(props.referrals.meta);
});
</script>

<template>
    <Head title="Referrals List" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Referrals List
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div
                    class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg"
                >
                    <div
                        class="relative overflow-x-auto shadow-md sm:rounded-lg"
                    >
                        <table
                            class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                        >
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                            >
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Referrer
                                    </th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="referral in referrals.data"
                                    :key="referral.id"
                                    class="bg-white border-b last:border-none dark:bg-gray-900 dark:border-gray-700"
                                >
                                    <th
                                        scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                    >
                                        {{ referral.referrer_name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ referral.email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ referral.status }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ referral.created_at }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <Pagination
                    v-if="referrals.meta.last_page > 1"
                    :links="referrals.meta.links"
                ></Pagination>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
