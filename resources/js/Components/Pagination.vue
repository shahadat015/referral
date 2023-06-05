<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

type LinkProp = {
    url: string;
    label: string;
    active: boolean;
};

defineProps<{
    links: LinkProp[];
}>();
</script>

<template>
    <div class="flex justify-center">
        <template v-for="link in links">
            <Link
                v-if="link.url"
                :href="link.url"
                v-html="link.label"
                class="px-1 dark:text-gray-400"
                :class="{
                    'text-gray-500': !link.url,
                    'font-bold': link.active,
                }"
            >
            </Link>
            <span
                v-else
                v-html="link.label"
                class="px-1 dark:text-gray-400"
                :class="{
                    'text-gray-500': !link.url,
                    'font-bold': link.active,
                }"
            >
            </span>
        </template>
    </div>
</template>
