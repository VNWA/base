<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
const props = defineProps<{
    to: string
}>()

</script>

<template>
        <Link :href="to" aria-label="Vinawebapp Link">
        <slot />
        </Link>
</template>
