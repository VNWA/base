<script setup lang="ts">
import {
    Card as BaseCard,
    CardContent as BaseCardContent,
    CardFooter as BaseCardFooter,
    CardHeader as BaseCardHeader,
    CardTitle as BaseCardTitle,
    CardDescription as BaseCardDescription,
} from '@/components/ui/card'

const props = defineProps<{
    title?: string
    description?: string
    class?: string
    loading?: boolean
}>()
</script>

<template>
    <BaseCard :class="props.class" class="relative p-3">
        <!-- Overlay khi loading -->
        <div v-if="props.loading"
            class="absolute inset-0 bg-white/70 dark:bg-gray-900/70 flex items-center justify-center z-10">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-500"></div>
        </div>

        <!-- Header -->
        <BaseCardHeader v-if="props.title || props.description || $slots.header" class="border-b mb-3 py-3">
            <div class="flex items-center justify-between gap-4">
                <h3 class="font-bold text-lg">{{ props.title }}</h3>
                <slot name="header" />
            </div>
            <BaseCardDescription v-if="props.description">{{ props.description }}</BaseCardDescription>
        </BaseCardHeader>

        <!-- Content -->
        <BaseCardContent>
            <slot></slot>
        </BaseCardContent>

        <!-- Footer -->
        <BaseCardFooter>
            <slot name="footer"></slot>
        </BaseCardFooter>
    </BaseCard>
</template>

<style scoped>
/* Spinner đơn giản */
.animate-spin {
    border-top-color: transparent;
    border-right-color: transparent;
    border-left-color: #3b82f6;
    /* Tailwind blue-500 */
    border-bottom-color: #3b82f6;
}
</style>
